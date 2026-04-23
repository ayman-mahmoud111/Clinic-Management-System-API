<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Http\Resources\AppointmentResource;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
{
    $user = $request->user();

    $query = Appointment::with(['patient', 'doctor']);

    
    if ($user->role->name === 'doctor') {
        $query->where('doctor_id', $user->id);
    }


    return AppointmentResource::collection(
        $query->latest()->paginate(10)
    );
}

    public function store(StoreAppointmentRequest $request){
        $schedule=DoctorSchedule::where('doctor_id',$request->doctor_id)->first();
        $start=Carbon::parse($request->start_time)->format('H:i:s');
        $end=Carbon::parse($request->end_time)->format('H:i:s');

        if(!$schedule|| $start < $schedule->start_time|| $end > $schedule->end_time){
            return response()->json([
                'massage'=>'the doctor not avilable at this time',
            ],400);
        }

        $conflict = Appointment::where('doctor_id', $request->doctor_id)
                ->where('start_time', '<', $request->end_time)
                ->where('end_time', '>', $request->start_time)
                ->exists();
        
                        
        if($conflict){
           return response()->json([
             'massage'=>'this time not avilable'
            ],400);                             
         }

         $appointment=Appointment::create($request->validated());
         return response()->json([
            'massage'=>'Create successfully',
            'data'=>new AppointmentResourse($appointment->load('patient','doctor'))
         ]);

                
    }

    public function show(Appointment $appointment)
    {
        return $appointment->load(['patient', 'doctor']);
    }

    public function update(UpdateAppointmentRequest $request,Appointment $appointment){
        $schedule=DoctorSchedule::where('doctor_id',$request->doctor_id)->first();
        $start=Carbon::parse($request->start_time)->format('H:i:s');
        $end=Carbon::parse($request->end_time)->format('H:i:s');

        if(!$schedule|| $start < $schedule->start_time|| $end > $schedule->end_time){
            return response()->json([
                'massage'=>'the doctor not avilable at this time',
            ],400);
        }

        $conflict = Appointment::where('doctor_id', $request->doctor_id)
                ->where('id','!=',$appointment->id)
                ->where('start_time', '<', $request->end_time)
                ->where('end_time', '>', $request->start_time)
                ->exists();
        
                        
        if($conflict){
           return response()->json([
             'massage'=>'this time not avilable'
            ],400);                             
         }

        $appointment->update($request->validated()); 

        return response()->json([
            'massage'=>'Update Successfully',
            'date'=>new AppointmentResource($appointment->load(['patient','doctor']))
        ]) ;

        

    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted'
        ]);
    }

    public function restore($id){
        $appointment=Appointment::withTrashed()->findOrFill($id);
        $appointment->restore();
        return response()->json([
            'massage'=>'Restore Successfully'
        ]);
    }
    
}
