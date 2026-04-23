<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Resources\PatientResource;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    public function index(Request $reuqest){
        $query=Patient::query();

        if($reuqest->filled('search')){
            $query->where('name' , 'like' , '%'.$reuqest->search.'%');
        }
        if($reuqest->filled('age')){
            $query->where('age',$reuqest->age);
        }
        if($reuqest->filled('phone')){
            $query->where('phone',$reuqest->phone);
        }
        $patients=$query->paginate(10);

        return  PatientResource::collection($patients);


    }

    public function store(StorePatientRequest $reuqest){
        $patient=patient::create($reuqest->validate());

        return new PatientResource($patient);

    }

    public function show(Patient $patient){

        return new PatientResource($patient);
    }

    public function update(StorePatientRequest $reuqest,Patient $patient){
        $patient->update($reuqest->validate());
        return new PatientResource($patient);

    }

    public function destory(Patient $patient){
        $patient->delete();// soft delete
        return response()->json(['massage'=>'Patiant Deleted']);

    }

     public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return response()->json([
            'message' => 'Patient restored successfully'
        ]);
    }
}
