<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'status',
        'description',
        'initialized_employee_id',
        'terminated_employee_id'.
        'initialized_department_id',
        'terminated_department_id',
        'terminated_at',
    ];

    public function employees()
    {
        return $this->belongsToMany('App\Employee')->withPivot('message_id')->withTimestamps();
    }

    static function createTask ($messageFromUser, Employee $employee)
    {
        $task = new Task();
        $task->status = 'created';
        $task->terminated_department_id = Department::where('name', $messageFromUser)->first()->id;
        $task->initialized_employee_id = $employee->id;
        $task->save();
        return $task;
    }

    static function getTask ($employeeId, $status)
    {
        return self::where('initialized_employee_id', $employeeId)->where('status', $status)->first();
    }

    static function deleteTask ($taskId, $employeeId)
    {
        $task = self::find($taskId);
        $task->status = 'deleted';
        $task->terminated_employee_id = $employeeId;
        $task->terminated_at = Carbon::now();
        $task->save();
        return $task;
    }

    static function sendTask ($taskId)
    {
        $task = self::find($taskId);
        $task->status = 'sent';
        $task->save();
        return $task;
    }

    static function sendRedirectedTask ($taskId, $message)
    {
        $task = self::find($taskId);
        $task->status = 'sent';
        $task->terminated_department_id = Department::where('name', $message)->first()->id;
        $task->save();
        return $task;
    }

    static function acceptTask ($taskId, $employeeId)
    {
        $task = self::find ($taskId);
        $task->status = 'accepted';
        $task->terminated_employee_id = $employeeId;
        $task->save();
        return $task;
    }
    static function denyTask ($taskId, $employeeId)
    {
        $task = self::find($taskId);
        $task->status = 'denied';
        $task->terminated_employee_id = $employeeId;
        return $task;
    }
    static function redirectTask ($taskId, $employeeId)
    {
        $task = self::find($taskId);
        $redirection = new Redirection();
        $redirection->redirected_employee_id = $employeeId;
        $redirection->redirected_department_id = $task->terminated_department_id;
        $redirection->save();
        $task->status = 'redirected';
        $task->initialized_employee_id = $employeeId;
        $task->save();
        return $task;
    }
}
