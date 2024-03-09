<?php

function history($update_id): bool{
    global $mysqli;


    $getMessageStateQuery=$mysqli->query("select proceeded from history where message_id='".$update_id."'");
    $resultRow=$getMessageStateQuery->fetch_row();
    $result=$getMessageStateQuery->num_rows;
    if($result==0){
        return false;
    }else{
        if($resultRow[0]=='true') {
            return true;
        }else{
            return false;
        }
    }
    #return $result['0'];
}