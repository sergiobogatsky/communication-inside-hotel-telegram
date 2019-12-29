<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['telegram_id', 'name', 'created_at', 'updated_at'];
}
