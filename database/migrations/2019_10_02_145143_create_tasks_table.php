<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status', 255);
            $table->string('description', 1000)->nullable();
            $table->bigInteger('initialized_employee_id');
            $table->bigInteger('terminated_employee_id')->nullable();
            $table->bigInteger('initialized_department_id')->nullable();
            $table->bigInteger('terminated_department_id')->nullable();
            $table->timestamps();
            $table->dateTime('terminated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
