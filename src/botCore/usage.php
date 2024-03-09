<?php

function usage(): void
{
    global $chat;
    global $message;
    $msg="Easy as piece of pie\n
<pre>/piska</pre> for participating\n
<pre>/start</pre> for get your current size\n
<pre>/leaders</pre> for see whose is bigger";
    sendMessage("$msg", $chat['id'], $message['id'], $message['inChat_id']);

}
