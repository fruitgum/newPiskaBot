<?php
require_once 'vendor/autoload.php';
include 'src/stuff/params.php';
include 'src/myLibs/logger.php';
include "src/stuff/auth.php";
include 'src/stuff/db.php';
include 'src/tglibs/getUpdates.php';
include 'src/tglibs/sendMessage.php';
include 'src/botCore/lottery.php';
include 'src/myLibs/history.php';
include 'src/botCore/usage.php';
include 'src/botCore/greeting.php';
include 'src/botCore/leaders.php';


$getUpdates=getUpdates();
$getUpdatesResult=$getUpdates['result'];

function isNewUser($user_id, $user, $user_name, $chat_id, $chat_title): string{
    global $mysqli;
    global $botName;

    $newUserCheckQuery=$mysqli->query("select count(*) as count from piska where user_id='".$user_id."'");
    $result=$newUserCheckQuery->fetch_row();

    if($result['0']=='0'){
        $msg="New user: ".$user." ".$user_id." from ".$chat_title." ".$chat_id;
        logger("$msg", "INFO");
        try {
            $mysqli->query("INSERT INTO piska values(
                      '" . $user_id . "',
                      '" . $chat_id . "',
                      '" . $user_name . "',
                      '" . $user . "',
                      '0',
                      date_add(now(), interval -30 DAY)
              )");
        }catch (mysqli_sql_exception $e){
            logger("$e", "FATAL");
            return "err";
        }
    }else{
        $msg="User: ".$user." ".$user_id." already exist";
        logger("$msg", "DEBUG");
    }
    return 'nul';
}

if(count($getUpdatesResult)==0){
    logger("No new messages", "DEBUG");
    exit();
}

for ($m = 0; $m < count($getUpdatesResult); $m++) {
    $entity=$getUpdatesResult[$m];

    $update_id=$entity['update_id'];

    if(array_key_exists("edited_message", $entity)){
        continue;
    }

    if(array_key_exists("message", $entity)) {
        if (array_key_exists("new_chat_member", $entity['message'])) {
            continue;
        }
    }

    if(array_key_exists("my_chat_member", $entity)){
        continue;
    }

    $message=array(
        'id' => $update_id,
        'date' => $entity['message']['date'],
        'text' => $entity['message']['text'],
        'inChat_id' => $entity['message']['message_id']
    );

    $user=array(
        'id'=>$entity['message']['from']['id'],
        'first_name'=>$entity['message']['from']['first_name'],
        'username'=>$entity['message']['from']['username']
    );

    $chat=array(
        'id'=>$entity['message']['chat']['id'],
        #'title'=>'',
        'type'=>$entity['message']['chat']['type']
    );

    if($chat['type']=='private'){
        $chat['title']=$user['first_name'];
    }
    else{
        $chat['title']=$entity['message']['chat']['title'];
    }

    $messageProceeded=history($update_id);
    #logger("Message ID: ".$update_id.", text ".$message['text'].". Proceed status: ".$messageProceeded, "DEBUG");


    if($messageProceeded){
        logger("Message $update_id already proceeded", "DEBUG");
    }
    else{
        logger("Got new message from ".$user['username']." with text ".$message['text'], "INFO");

        try {
            $mysqli->query("INSERT INTO history(chat_id, user_id, message_id, chat_message_id, proceeded, date) values(
                           '" . $chat['id'] . "',
                           '" . $user['id'] . "',
                           '" . $update_id . "',
                           '" . $message['inChat_id'] . "',
                           'false',
                           now()

            ) ON DUPLICATE KEY UPDATE date=now()
            ");
        }catch (mysqli_sql_exception $e){
            logger("$e", "FATAL");
            continue;
        }


        $newUser=isNewUser($user['id'],$user['username'], $user['first_name'], $chat['id'], $chat['title']);
        if($newUser=='err'){
            continue;
        }

        $request=explode("@", $message['text']);
        $command=$request[0];
        if(count($request)>1) {
            $at = $request[1];
            if ($at != $botName) {
                logger("Skipped command ".$message['text'], "INFO");
                $mysqli->query("update history set proceeded='true' where message_id='".$update_id."'");
                continue;
            }
        }else{
            logger("Skipped command ".$message['text'], "INFO");
            $mysqli->query("update history set proceeded='true' where message_id='".$update_id."'");
            continue;
        }
        switch ($command){
            case "/start":

                greetings($chat['id'], $user['first_name'], $user['id'], $message);
                break;
            case "/piska":

                doLottery();
                break;
            case "/help":

                usage();
                break;
            case "/leaders":
                getLeaders($chat['id'], $message['id'], $message['inChat_id']);
                break;
            default:
                logger("Unknown command from message ".$message['id'], "WARN");
                sendMessage("Yeah...", $chat['id'], $update_id, $message['inChat_id']);
                $mysqli->query("update history set proceeded='true' where message_id='".$update_id."'");
                break;
        }
    }

    unset($entity);
    unset($update_id);
    unset($message);
    unset($user);
    unset($chat);

}