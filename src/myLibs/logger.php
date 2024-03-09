<?php

include 'src/stuff/params.php';
function logger($log_message, $log_level_logger)
{

    global $log_levels;
    global $LOG_LEVEL;

    $space="";

    $req_log_level = array_search($log_level_logger, $log_levels);
    $current_log_level = array_search($LOG_LEVEL, $log_levels);

    if ($req_log_level >= $current_log_level){

        if ($log_level_logger=="INFO" || $log_level_logger=="WARN"){
            $space=" ";
        }

        $date = date("H:i:s  d-m-Y");
        echo "$date ".'['.$log_level_logger.']'." $space$log_message\n";
    }

}

