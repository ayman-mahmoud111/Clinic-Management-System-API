<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medical_Records;
use App\Http\Resources\MedicalRecordResource;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;

class MedicalRecordController extends Controller
{
    
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Medical_Records::with(['patient','doctor']);

        
        if ($user->role->name == 'doctor') {
            $query->where('doctor_id', $user->id);
        }

        
        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $medical_records = $query->latest()->paginate(10);

        return MedicalRecordResource::collection($medical_records);
    }

    
    public function store(StoreMedicalRecordRequest $request)
    {
        $newRecord = Medical_Records::create([
            ...$request->validated(),
            'doctor_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Created successfully',
            'data' => new MedicalRecordResource(
                $newRecord->load(['patient','doctor'])
            )
        ]);
    }

    
    public function show(Medical_Records $medicalRecord)
    {
        return new MedicalRecordResource(
            $medicalRecord->load(['patient','doctor'])
        );
    }

    
    public function update(UpdateMedicalRecordRequest $request, Medical_Records $medicalRecord)
    {
        $medicalRecord->update($request->validated());

        return response()->json([
            'message' => 'Updated successfully',
            'data' => new MedicalRecordResource(
                $medicalRecord->load(['patient','doctor'])
            )
        ]);
    }

    
    public function destroy(Medical_Records $medicalRecord)
    {
        $medicalRecord->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }

    
    public function restore($id)
    {
        $record = Medical_Records::withTrashed()->findOrFail($id);
        $record->restore();

        return response()->json([
            'message' => 'Restored successfully'
        ]);
    }
}