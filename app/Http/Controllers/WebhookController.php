<?php

namespace App\Http\Controllers;

use App\Custom\Response;
use App\Employee;
use Illuminate\Http\Request;
use App\Custom\TelegramApi;

class WebhookController extends Controller
{

    public function handle(Request $request)
    {
        //initiating the custom class for telegram API
        $telegram = new TelegramApi(config('services.telegram.general-telegram-bot-token'));

        //checking if the webhook from telegram was set:
        $telegram->setTelegramWebhook(config('services.telegram.webhook-url'));

        //taking the chat from telegram:
        $chat = $this->getTelegramMessageChat($request);

        //checking if the user is inside of the list of the employees in the database:
        if ($employee = Employee::getEmployee($chat)) {

            //responding
            $response = new Response($request, $telegram);
            $response->reply($employee);
        }
        else {
            //explaining that first you has to connect to the database
            //by the administrator
            $telegram->sendText(
                $chat['id'],
                __('departments.user_not_exist', ['telegramId' => $chat['id']])
            );
            abort(401, 'Invalid id');
            return;
        }
    }

    function getTelegramMessageChat (Request $request)
    {
        if (isset($request->message['chat']['id'])) {
            return $request->message['chat'];
        }

        else if (isset($request->callback_query['message']['chat']['id'])) {
            return $request->callback_query['message']['chat'];
        }
        else {
            abort(401, 'Invalid id');
            return [];
        }
    }
}
