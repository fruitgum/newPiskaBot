<?php


function sendMessage($response, $chat_id, $message_id, $replyTo): void
{
    global $bot_con_data;
    global $mysqli;
    $botToken=$bot_con_data['token'];
    $apiUrl = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';


    $data = [
        'chat_id' => $chat_id,
        'text' => $response,
        'parse_mode' => 'HTML',
        'reply_to_message_id'=> $replyTo
    ];


    $sendMessage = curl_init();
    curl_setopt($sendMessage, CURLOPT_URL, $apiUrl);
    curl_setopt($sendMessage, CURLOPT_POSTFIELDS, $data);
    curl_setopt($sendMessage, CURLOPT_RETURNTRANSFER, true);
    $curl_result=curl_exec($sendMessage);
    curl_close($sendMessage);

    logger("Response with text '".$response."' sent to ".$chat_id, "INFO");
    logger("$curl_result", "DEBUG");
    if (curl_errno($sendMessage)) {
        $msg='Curl error: ' . curl_error($sendMessage);
        logger("$msg", "FATAL");
        exit();
    }
    $mysqli->query("update history set proceeded='true' where message_id='".$message_id."'");

}