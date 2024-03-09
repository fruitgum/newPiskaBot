<?php

$DB_HOST=getenv('PISKA_DB_HOST');
$DB_USER=getenv('PISKA_DB_USER');
$DB_PASS=getenv('PISKA_DB_PASS');
$DB_NAME=getenv('PISKA_DB_NAME');
$TOKEN=getenv('PISKA_BOT_TOKEN');

$mysqli = new mysqli(
    "$DB_HOST",
    "$DB_USER",
    "$DB_PASS",
    "$DB_NAME"
);

$bot_con_data = array(
'token' => $TOKEN
);