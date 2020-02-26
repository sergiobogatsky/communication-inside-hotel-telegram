<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'telegram_id',
        'name',
        'created_at',
        'updated_at'
    ];

    public function employees()
    {
        return $this->belongsToMany('App\Employee');
    }

    static function redirect($taskId)
    {
        $departmentsRedirect = [];
        $departments = self::pluck('name')->toArray();
        foreach ($departments as $department) {
            $departmentsRedirect[$department] = $department.','.$taskId;
        }
        return $departmentsRedirect;
    }
}
