<?php

function usage(): void
{
    global $chat;
    global $message;
    $msg="Easy as piece of pie\nUse <pre>/piska</pre> for participating";
    sendMessage("$msg", $chat['id'], $message['id'], $message['inChat_id']);

}
