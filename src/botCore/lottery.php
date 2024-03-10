<?php

use JetBrains\PhpStorm\NoReturn;

include 'src/stuff/dictionary.php';

/**
 * @throws Exception
 */
function checkLastRun($user_id): string{
    global $mysqli;
    $lastRunQuery=$mysqli->query("select last_run from piska where user_id='".$user_id."'");
    $row=$lastRunQuery->fetch_row();
    $lastRun = new DateTime($row[0]);
    $now=new DateTime();
    $lastRun->modify("+24 hours");
    $diff=$lastRun->diff($now, true);
    $diff->invert=0;
    $countdown=$diff->h." hour(s), ".$diff->i." minute(s) and ".$diff->s." seconds";
    $lastRun=$lastRun->format("Y-m-d H:i:s");
    $now=$now->format("Y-m-d H:i:s");
    $diff=strtotime($now)-strtotime($lastRun);
    if($diff<86400){
        return "0@".$countdown;
    }else{
        return "1@null";
    }
}

function doRandom(): int{
    $random=rand(-10, 10);
    if($random==0){
            $random=rand(-10, 10); // another chance
    }
    return $random;
}

function doLottery(): int{


    global $mysqli;
    global $sizeDict;

    global $message;
    global $user;
    global $chat;

    $msg='';
    $response='';

    $checkUserQuery=$mysqli->query("select user_id from piska where user_id='".$user['id']."'");
    $checkUser=$checkUserQuery->num_rows;
    if($checkUser=='0'){
        logger("User ".$user['username']." not found", "WARN");
        exit();
    }

    $lastRunResult=checkLastRun($user['id']); #will return 0 or 1, depends on time passed since last run
    $lastRunExplode=explode("@", $lastRunResult);
    $isRunnable=$lastRunExplode[0];
    $countdown_message=$lastRunExplode[1];
    if($isRunnable==0){
        $msg="Defined interval didn't passed since last run for user ".$user['username'];
        logger("$msg", "INFO");
        $msg="Please wait $countdown_message more";
        sendMessage("$msg", $chat['id'], $message['id'], $message['inChat_id']);
        return 0;
    }

    $currentSizeQuery=$mysqli->query("SELECT size from piska where user_id='".$user['id']."'");
    $row=$currentSizeQuery->fetch_row();
    $currentSize=$row[0];
    $adjustSize=doRandom();
    $newSize=$currentSize+$adjustSize;

    if($newSize<0) {
        $newSize = 0;
    }
    $mysqli->query("UPDATE piska set size='".$newSize."', last_run=now() where user_id='".$user['id']."'");

    if($newSize==0){
        $response=$sizeDict['zero'][0]."\nSize: ".$adjustSize." whatever";
        $msg="User ".$user['username']." (".$user['id'].") "."got zero size, lol";
    }

    if($newSize==$currentSize){
        $phrase_id=rand(0,9);
        $response=$sizeDict['nodiff'][$phrase_id]."\nSize stays same: ".$newSize;
        $msg="User ".$user['username']." (".$user['id'].") "."got same size";
    }

    if($newSize>$currentSize){
        $phrase_id=rand(0,9);
        $response=$sizeDict['posdiff'][$phrase_id]."\n+".$adjustSize."cm. Size now is: ".$newSize;
        $msg="User ".$user['username']." (".$user['id'].") "."got +".$adjustSize."cm. Current size: ".$newSize."cm";
    }

    if($newSize<$currentSize){
        $phrase_id=rand(0,9);
        $response=$sizeDict['negdiff'][$phrase_id]."\n-".$adjustSize."cm. Size now is: ".$newSize;
        $msg="User ".$user['username']." (".$user['id'].") "."got -".$adjustSize."cm. Current size: ".$newSize."cm";
    }


        sendMessage("$response", $chat['id'], $message['id'], $message['inChat_id']);
        logger("$msg", "INFO");
# Commented code below someday will be run sending in parallel

//    $getQueueMessages=$mysqli->query("select message_id from history where proceeded='false'");
//    $queueMessages=$getQueueMessages->fetch_all();

//    for ($q = 0; $q < count($queueMessages); $q++) {
//        $pid = pcntl_fork();
//        if($pid==-1){
//            logger("Couldn't fork", "FATAL");
//            die();
//        }elseif($pid){
//            logger("$pid already running", "INFO")
//            continue;
//        }else {
//            $message_id = $queueMessages[$q][0];
//            $getSendMessageArgs = $mysqli->query("select chat_id, message_id, chat_message_id from history where message_id='" . $message_id . "'");
//            $sendMessageArgs = $getSendMessageArgs->fetch_assoc();
//            $chat['id'] = $sendMessageArgs['chat_id'];
//            $message['id'] = $sendMessageArgs['message_id'];
//            $message['inChat_id'] = $sendMessageArgs['chat_message_id'];
//            sendMessage("$response", $chat['id'], $message['id'], $message['inChat_id']);
//            logger("$msg", "INFO");
//        }
//   }


    return 0;

}

