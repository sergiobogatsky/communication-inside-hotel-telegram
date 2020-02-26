<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Employee::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'telegram_id' => 'required||digits:9|unique:employees,telegram_id'
        ]);

        $employee = new Employee;

        $employee->telegram_id = $request->telegram_id;

        $employee->save();

        return response($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response(Employee::findOrFail($id)->load('departments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id)->load('departments');

        $employee->allDepartments = Department::all();

        return $employee;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $employee->update(
            [
                'telegram_id' => $request->telegram_id,
            ]
        );

        //if no departments sent, so we detach all the departments:
        if ($request->departments == null) {
            $employee->departments()->detach();
        }

        else {
            //taking the ID´s of departments for sync method
            foreach ($request->departments as $department) {
                $ids[]=$department['id'];
            }

            $employee->departments()->sync($ids);
        }

        return $employee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->departments()->detach();
        $employee->delete();
    }
}
