<?php

namespace App\Custom;


use Illuminate\Support\Facades\File;

class TelegramApi
{
    protected $telegramBotToken;

    public function __construct($TelegramBotToken)
    {
        //general telegram bot token
        $this->telegramBotToken = $TelegramBotToken;
    }

    public function sendText ($telegramId, $text)
    {
        $postfields = array(
            'chat_id' => "$telegramId",
            'parse_mode'=> 'HTML',
            'text' => "$text",
        );

        $this->sendCurl(
            $postfields,
            'https://api.telegram.org/bot'.$this->telegramBotToken.'/sendMessage'
        );
    }


    public function sendButtons ($telegramId, $arrayTextButtons, $text)
    {
        $buttons = [];

        //how many buttons in one line:
        $columns = 2;

        foreach ($arrayTextButtons as $textButton) {
            // at the moment we do it without the links and without the second part
            //counting the last position of array:
            if (empty($buttons)) {
                $buttons[] = [['text'=>$textButton]];
            }
            else if (count($buttons[count($buttons) - 1]) == $columns-1) {
                array_push($buttons[count($buttons) - 1], ['text'=>$textButton]);
            }
            else {
                $buttons[] = [['text'=>$textButton]];
            }
        }

        $keyboard = array(
            "keyboard" => $buttons,
            "one_time_keyboard" => true,
            "resize_keyboard" => true
        );

        $postfields = array(
            'chat_id' => "$telegramId",
            'parse_mode'=> 'HTML',
            'text' => "$text",
            'reply_markup' => json_encode($keyboard)
        );

        $this->sendCurl(
            $postfields,
            'https://api.telegram.org/bot'.$this->telegramBotToken.'/sendMessage'
        );
    }


    public function sendInlineButtons ($telegramId, $arrayTextButtons, $text)
    {
        $buttons = [];

        //how many buttons in one line:
        $columns = 2;

        foreach ($arrayTextButtons as $key => $value) {
            if (empty($buttons)) {
                $buttons[] = [['text'=>$key, 'callback_data'=>$value]];
            }
            //counting the last position of array:
            else if (count($buttons[count($buttons) - 1]) == $columns-1) {
                array_push($buttons[count($buttons) - 1], ['text'=>$key, 'callback_data'=>$value]);
            }
            else {
                $buttons[] = [['text'=>$key, 'callback_data'=>$value]];
            }
        }

        $keyboard = array(
            "inline_keyboard" => $buttons
        );

        $postfields = array(
            'chat_id' => "$telegramId",
            'parse_mode'=> 'HTML',
            'text' => "$text",
            'reply_markup' => json_encode($keyboard)
        );

        $data = $this->sendCurl(
            $postfields,
            'https://api.telegram.org/bot'.$this->telegramBotToken.'/sendMessage'
        );
        return $data;
    }

    public function getFileUrl ($fileId)
    {
        //we get the file information
        $json_file = file_get_contents("https://api.telegram.org/bot" . $this->telegramBotToken . "/getFile?file_id=" . $fileId);
        $array_file = json_decode($json_file, true);

        //we get the route where the photo is
        $rute_file = $array_file['result']['file_path'];

        //we compose the complete url of our photo
        $file_path = 'https://api.telegram.org/file/bot' . $this->telegramBotToken . '/' . $rute_file;

        return $file_path;
    }

    public function deleteMessage ($chatId, $messageId)
    {
        $postfields = array(
            'chat_id'=> $chatId,
            'message_id' => $messageId
        );

        $this->sendCurl(
            $postfields,
            'https://api.telegram.org/bot'.$this->telegramBotToken.'/deleteMessage'
        );
    }

    /**
     *register the webhook of the bot in telegram
     * In case that you want to change it to new url
     * delete the file "registered.trigger" in main folder
     */
    public function setTelegramWebhook ($url)
    {
        if(!file_exists(base_path().'/registered.trigger')){
            /**
             * The registered.trigger file will be created after the bot is registered.
             * if this file does not exist, then the bot is not registered in the Telegram
             * or the problem with permissions
             */
            $response = $this->sendCurl(
                "",
                'https://api.telegram.org/bot'.
                $this->telegramBotToken.
                '/setWebhook?url='.
                $url
            );

            if($response['code']['http_code'] == 200
                && ($response['data'] == '{"ok":true,"result":true,"description":"Webhook was set"}'
                    || $response['data'] == '{"ok":true,"result":true,"description":"Webhook is already set"}')) {
                try {
                    // create a file in order to stop re-registration
                    File::put(base_path().'/registered.trigger', time());;
                    //file_put_contents("registered.trigger",time());
                    $this->sendText(
                        config('services.telegram.telegram-id-user-error'),
                        $response['data']. ' to '.
                        $url
                    );
                }
                catch (Exception $exception) {
                    $this->sendText(
                        config('services.telegram.telegram-id-user-error'),
                        'error '.$exception->getMessage().'. webhook '.config('services.telegram.general-telegram-bot-username').' was set to: '.
                        $url
                    );
                }
            }
            else {
                $this->sendText(
                    config('services.telegram.telegram-id-user-error'),
                    'wrong response from the server of Telegram. Check the TelegramApi.php class'
                );
            }
        }
    }

    protected function sendCurl ($postfields, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec ($ch);
        $httpcode = curl_getinfo($ch);
        curl_close ($ch);

        return ['code'=> $httpcode, 'data'=> $data];
    }
}