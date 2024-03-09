<?php
function greetings($chat, $user, $user_id, $message): int
{
    global $mysqli;
    $userSizeQuery=$mysqli->query("SELECT size from piska where user_id='".$user_id."'");
    $userSize=$userSizeQuery->fetch_row();


    $msg="Hello, $user!\nYour piska size is <i>$userSize[0] cm</i>";
    sendMessage("$msg", $chat, $message['id'], $message['inChat_id']);
    return 0;
}