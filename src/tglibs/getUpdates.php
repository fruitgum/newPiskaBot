<?php

function getUpdates(): array{
    global $bot_con_data;
    global $mysqli;
//    $getOffset=$mysqli->query("select ifnull(min(message_id), 0) as message_id from history where proceeded='false'");
//    $offset=$getOffset->fetch_row();
//    if($offset[0]=="0"){
        $getOffset=$mysqli->query("select max(message_id) from history");
        $offset=$getOffset->fetch_row();
//    }

    $botToken=$bot_con_data['token'];

    if ($botToken==''){
        logger("No token provided. Please define it with PISKA_BOT_TOKEN os env", "FATAL");
        exit();
    }

    logger("Starting with offset ".$offset[0]+1, "DEBUG");
    $apiUrl = 'https://api.telegram.org/bot' . $botToken . '/getUpdates?offset='.$offset[0]+1;

    $getUpdates = curl_init();

    curl_setopt($getUpdates, CURLOPT_URL, $apiUrl);
    curl_setopt($getUpdates, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($getUpdates, CURLOPT_CONNECTTIMEOUT, 10);

    $response = curl_exec($getUpdates);

    if(curl_errno($getUpdates)){
        #echo "\n".'Curl error: ' . curl_error($getUpdates);
        logger('Curl error: ' . curl_error($getUpdates), "FATAL");
    }

    curl_close($getUpdates);

    return json_decode($response, true);
}
