<?php

function getLeaders($chat_id, $message_chat_id, $message_id): void
{
    global $mysqli;
    $leaderBoard='';
    $getLeaders=$mysqli->query("select username, size from piska where last_run>date_add(now(), interval -1 month) and chat_id='".$chat_id."' order by size desc");
    $leadersRows=$getLeaders->num_rows;
    $l=0;
    if($leadersRows==0){
        sendMessage("No participants yet", $chat_id, $message_chat_id, $message_id);
        exit();
    }
    while($leaders=$getLeaders->fetch_assoc()){
        $username=$leaders['username'];
        $size=$leaders['size'];
        if($l==0) {
            $add = "🍆🍆🍆";
        }elseif ($l==1){
            $add = "🍆🍆";
        }elseif ($l==2){
            $add="🍆";
        }else{
            $add="";
        }
        $leaderRow="$username - $size"."cm"."      $add";
        $leaderBoard.=$leaderRow."\n";
        ($l++);
    }
    sendMessage("$leaderBoard", $chat_id, $message_chat_id, $message_id);
}

