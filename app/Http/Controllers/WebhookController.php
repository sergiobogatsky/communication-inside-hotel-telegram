<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Department;
use Telegram;

class WebhookController extends Controller
{

    public function setWebhook ()
    {

    }
    public function handle(Request $request)
    {
        try {
            $telegram = new Telegram\Bot\Api(config('services.telegram.general-telegram-bot-token'));
        }
        catch (\Telegram\Bot\Exceptions\TelegramSDKException $exception) {
            //echo $exception->getMessage();
            $this->enviarTexto(423485916, $exception->getMessage());
            return;
        }

        $result = $telegram -> getWebhookUpdates();

        if($result->getCallbackQuery() == null) {

            $text = $result->getMessage()->getText();
            //$date = $result->getMessage()->getDate();
            $departmentId = $result->getMessage()->getChat()->getId();
            //$userId = $result->getMessage()->getFrom()->getId();
            //$messageId = $result->getMessage()->getMessageId();
            //$previousMessageId = $result->getMessage()->getReplyToMessage()->getMessageId();
            //$userName = $result->getMessage()->getFrom()->getUsername();
            //$firstName = $result->getMessage()->getFrom()->getFirstName();
            //$lastName = $result->getMessage()->getFrom()->getLastName();

            if ($text == "/departments") {
                $reply_markup = $telegram->replyKeyboardMarkup([
                    'keyboard' => $this->keyboardDepartmentsTelegram(),
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]);

                $reply = "Choose the department where you would like to send the task: ";

                $response = $telegram->sendMessage([
                    'chat_id' => $departmentId,
                    'text' => $reply,
                    'reply_markup' => $reply_markup
                ]);

                $this->enviarTexto(423485916, 'bien');
            }
            else {
                $this->enviarTexto(423485916, 'else');
            }
        }

    }

    /**
     * making of menu based on departments from the database
     * to show inside of the telegram for all users
     * to select the right department to send the task
     *
     */
    function keyboardDepartmentsTelegram () {

        $departments = Department::all();

        $buttons = [];

        foreach ($departments as $department) {
            $buttons[] = ["text"=>$department->name, "callback_data"=>$department->telegram_id];
        }

        //$keyboard=["inline_keyboard"=>$buttons];

        $this->enviarTexto(423485916, json_encode($buttons));
        return $buttons;
    }

    //funcion para el envio de texto
    public function enviarTexto($idTelegram, $textoParaEnviar) {
        $postfields = array(
            'chat_id' => $idTelegram,
            'text' => $textoParaEnviar,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://api.telegram.org/bot929042783:AAFB3iry-fHHRxcZP5tsO_FHa_Z__bv1__M/sendMessage');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        curl_close ($ch);
    }
}
