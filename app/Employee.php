<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'telegram_id',
        'first_name',
        'last_name',
        'username',
        'initialized_tasks',
        'redirected_tasks',
        'accepted_tasks',
        'denied_tasks',
        'created_at',
        'updated_at'
    ];

    public function departments()
    {
        return $this->belongsToMany('App\Department');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task')->withPivot('message_id')->withTimestamps();
    }

    static function getEmployee ($chat)
    {
        $employee = Employee::where('telegram_id', $chat['id'])->first();
        if (!$employee) {
            return null;
        }
        $employee->first_name = $chat['first_name'] ?? null;
        $employee->last_name = $chat['last_name'] ?? null;
        $employee->username = $chat['username'] ?? null;
        $employee->save();
        return $employee;
    }
}
