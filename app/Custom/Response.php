<?php

namespace App\Custom;


use App\Employee;
use App\Task;
use Illuminate\Http\Request;
use App\Department;

class Response
{
    protected $request;
    protected $telegram;

    /**
     * Response constructor.
     * @param Request $request
     * @param TelegramApi $telegram
     */
    public function __construct(Request $request, TelegramApi $telegram)
    {
        $this->request = $request;
        $this->telegram = $telegram;
    }

    public function reply (Employee $employee)
    {
        if ($this->request->callback_query) {
            $this->setInlineMessage($employee, $this->request->callback_query);
        }
        else {
            $this->setMessage($employee, $this->request->message['text']);
        }
    }

    public function setMessage(Employee $employee, $messageFromUser)
    {
        switch ($messageFromUser) {

            //message on the first connection with the bot
            case "/start":
                $this->telegram->sendButtons(
                    $employee->telegram_id,
                    ['departments'],
                    __('departments.explanation')
                );
                break;

            //user want to see the list of departments:
            case (__('departments.departments')):

                //showing the departments like buttons:
                $this->telegram->sendButtons(
                    $employee->telegram_id,
                    Department::pluck('name')->toArray(),
                    __('departments.choose_the_department')
                );
                break;

            //user has pressed the button with the department
            case (Department::where('name', $messageFromUser)->exists()):
                //if there is a task "created", we delete it
                if ($task = Task::getTask($employee->id, 'created')) {
                    $task->delete();
                }
                //creating the new task:
                Task::createTask ($messageFromUser, $employee);
                //asking for the text of the task for that department:
                $this->telegram->sendText(
                    $employee->telegram_id,
                    __('departments.write_the_task', ['department' => $messageFromUser])
                );

                break;

                //for the text to send to another department:
            default:
                if ($task = Task::getTask ($employee->id, 'created')) {
                    //saving the message to send
                    $task->description = $messageFromUser;
                    $task->save();
                    //confirm the mailing
                    $this->telegram->sendInlineButtons(
                        $employee->telegram_id,
                        [
                            __('departments.send_to',
                                [
                                    'department' => Department::find($task->terminated_department_id)->name
                                ]
                            ) => 'send,'.$task->id,
                            __('departments.delete') => 'delete,'.$task->id
                        ],
                        $messageFromUser
                    );
                }
                else {
                    $this->telegram->sendButtons(
                        $employee->telegram_id,
                        Department::pluck('name')->toArray(),
                        __('departments.choose_the_department')
                    );
                }

        }
    }

    public function setInlineMessage(Employee $employee, $messageFromUser)
    {
        $message = explode(',', $messageFromUser['data'])[0];
        $taskId = explode(',', $messageFromUser['data'])[1];
        $this->telegram->sendText(
            423485916,
            $message.$taskId
        );
        switch ($message) {
            case 'delete':
                $task = Task::deleteTask($taskId, $employee->id);

                $this->telegram->deleteMessage(
                    $messageFromUser['message']['chat']['id'],
                    $messageFromUser['message']['message_id']
                );

                $this->telegram->sendText(
                    $employee->telegram_id,
                    __('task.task_deleted', ['task' => $task->description])
                );
                break;

            case 'send':
                if ($task = Task::sendTask($taskId)) {
                    //adding amount of task + 1
                    $employee->initialized_tasks += 1;
                    $employee->save();

                    $this->telegram->deleteMessage(
                        $messageFromUser['message']['chat']['id'],
                        $messageFromUser['message']['message_id']
                    );

                    $this->telegram->sendText(
                        $employee->telegram_id,
                        __('task.task_sent', ['task' => $task->description])
                    );

                    $department = Department::find($task->terminated_department_id);

                    //send to all employees from the indicated department:
                    foreach ($department->employees as $employeeDepartment) {
                        $message = $this->telegram->sendInlineButtons(
                            $employeeDepartment->telegram_id,
                            [
                                __('accept') => 'accept'.','.$task->id,
                                __('redirect') => 'redirect'.','.$task->id,
                                __('deny') => 'deny'.','.$task->id,
                            ],
                            $task->description
                        );

                        //add message_id to delete it in case of selection for each user
                        $employeeDepartment->tasks()->attach($task->id, ['message_id' => json_decode($message['data'], true)['result']['message_id']]);
                    }
                }
                break;

            case 'accept':
                if ($task = Task::acceptTask($taskId, $employee->id)) {
                    $employee->accepted_tasks += 1;
                    $employee->save();

                    foreach ($task->employees as $employeeTask) {
                        $this->telegram->deleteMessage(
                            $employeeTask->telegram_id,
                            $employeeTask->pivot->message_id
                        );
                    }
                    $this->telegram->sendText(
                        $employee->telegram_id,
                        __('task.task_accepted', ['task' => $task->description])
                    );
                }
                break;

            case 'deny':
                if ($task = Task::denyTask($taskId, $employee->id)) {
                    $employee->denied_tasks += 1;
                    $employee->save();
                    foreach ($task->employees as $employeeDepartment) {
                        $this->telegram->deleteMessage(
                            $employeeDepartment->telegram_id,
                            $employeeDepartment->pivot->message_id
                        );
                    }
                    $task->employees()->detach();
                    $task->save();
                    $this->telegram->sendText(
                        $employee->telegram_id,
                        __('task.task_denied', ['task' => $task->description])
                    );
                }
                break;

            case 'redirect':
                if ($task = Task::redirectTask($taskId, $employee->id)) {
                    $employee->redirected_tasks += 1;
                    $employee->save();
                    foreach ($task->employees as $employeeDepartment) {
                        $this->telegram->deleteMessage(
                            $employeeDepartment->telegram_id,
                            $employeeDepartment->pivot->message_id
                        );
                    }
                    $this->telegram->sendText(
                        $employee->telegram_id,
                        __('task.task_redirected', ['task' => $task->description])
                    );


                    $this->telegram->sendInlineButtons(
                        $employee->telegram_id,
                        Department::redirect($task->id),
                        __('departments.choose_the_department_redirect')
                    );
                }
                break;

            case (Department::where('name', $message)->exists()):
                if ($task = Task::sendRedirectedTask($taskId, $message, $employee->id)) {
                    $this->telegram->deleteMessage(
                        $messageFromUser['message']['chat']['id'],
                        $messageFromUser['message']['message_id']
                    );

                    $this->telegram->sendText(
                        $employee->telegram_id,
                        __('task.task_sent', ['task' => $task->description])
                    );

                    $department = Department::find($task->terminated_department_id);

                    //send to all employees from the indicated department:
                    foreach ($department->employees as $employeeDepartment) {
                        $message = $this->telegram->sendInlineButtons(
                            $employeeDepartment->telegram_id,
                            [
                                __('accept') => 'accept'.','.$task->id,
                                __('redirect') => 'redirect'.','.$task->id,
                                __('deny') => 'deny'.','.$task->id,
                            ],
                            $task->description
                        );

                        //add message_id to delete it in case of selection for each user
                        $employeeDepartment->tasks()->attach($task->id, ['message_id' => json_decode($message['data'], true)['result']['message_id']]);
                    }
                }
                break;
        }
    }
}