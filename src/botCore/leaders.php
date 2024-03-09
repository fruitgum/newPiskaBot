<?php

function getLeaders($chat_id, $message_chat_id, $message_id)
{
    global $mysqli;
    $leaderBoard='';
    $getLeaders=$mysqli->query("select username, size from piska where last_run>date_add(now(), interval -1 month)");
    $leadersRows=$getLeaders->num_rows;
    $l=0;
    while($leaders=$getLeaders->fetch_assoc()){
        $username=$leaders['username'];
        $size=$leaders['size'];
        if($l==0) {
            $add = "🍆🍆🍆";
        }elseif ($l==1){
            $add = "🍆🍆";
        }elseif ($l==2){
            $add="🍆";
        }elseif ($leadersRows-$l==2){
            $add="🍑";
        }elseif ($leadersRows-$l==1){
            $add="🍑🍑";
        }
        elseif ($leadersRows-$l==0){
            $add="🍑";
        }else{
            $add="";
        }
        $leaderRow="$username - $size"."cm"."      $add";
        $leaderBoard.=$leaderRow."\n";
        ($l++);
    }
    sendMessage("$leaderBoard", $chat_id, $message_chat_id, $message_id);
}

