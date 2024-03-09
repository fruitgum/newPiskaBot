<?php

$DB_HOST=getenv('PISKA_DB_HOST');
$DB_USER=getenv('PISKA_DB_USER');
$DB_PASS=getenv('PISKA_DB_PASS');
$DB_NAME=getenv('PISKA_DB_NAME');

$mysqli = new mysqli(
    "$DB_HOST",
    "$DB_USER",
    "$DB_PASS",
    "$DB_NAME"
);

$bot_con_data = array(
'token' => '6510010197:AAHzRBh4OJ_V1GCgeWF6M_GGU7Rlp2pSY8U'
);