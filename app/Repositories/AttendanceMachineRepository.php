<?php
namespace App\Repositories;

use App\Models\AttendanceMachine;

class AttendanceMachineRepository extends Repository
{
    public function getModel(): string
    {
        return AttendanceMachine::class;
    }

    public function getMachineById($machine_id) {
        $machine_id = AttendanceMachine::where('machine_id', $machine_id)->first();

        return $machine_id->toArray();
    }
}
