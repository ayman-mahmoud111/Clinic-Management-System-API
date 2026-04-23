<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\Bill;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        Bill::create([
            'patient_id'=>$appointment->patient_id,
            'appointment_id'=>$appointment->id,
            'amount'=>$appointment->doctor->consultation_fee,
            'status'=>'pinding'
        ]);
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        //
    }
}
