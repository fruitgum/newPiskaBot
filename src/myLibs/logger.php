<?php


function logger($log_message, $log_level_logger): void
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

        $date = date("d-m-Y H:i:s");
        echo "$date ".'['.$log_level_logger.']'." $space$log_message\n";
    }

}

