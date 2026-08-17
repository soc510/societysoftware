<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn = mysql_connect('localhost','vedhtkjm_easylog','easyDatabase123');
//$conn = mysql_connect('localhost','root','');

if(!$conn) {
    echo 'Database connectivity issue '.mysql_error();
    exit;
}

mysql_select_db('vedhtkjm_easylogics',$conn) or die('DB selection issue'.mysql_error());
//echo '<pre>';
if(1) {
    
    $sql = "select id,society_id,opening_amount from society_ledger_heads_op_bal where 1";
    $query = mysql_query($sql,$conn);
    $i = 0;
    $result = array();
    while($res = mysql_fetch_assoc($query)){
        $result[$res['id']] = $res; 
        $i++;
    }
    
    foreach ($result as $k=>$arr) {
         $updateQue = "UPDATE society_ledger_heads SET opening_amount = '".$arr['opening_amount']."' WHERE id = '".$arr['id']."' and society_id = '".$arr['society_id']."'";
                
        $update  = mysql_query($updateQue,$conn);
        if(!$update) {
            mysql_close($conn);
            die('Error in ledger head updation'. mysql_error());
        }
    }
    
    $sql = "select op_principal,op_interest,op_tax,id,society_id from members_op_bal where 1";
    $query = mysql_query($sql,$conn);
    $i = 0;
    $resultMemb = array();
    while($res = mysql_fetch_assoc($query)){
        $resultMemb[$res['id']] = $res; 
        $i++;
    }
    
    
    foreach($resultMemb as $memberId=>$memberOpening) {
        $societyId = $memberOpening['society_id'];
        $updateQue = "UPDATE members SET op_principal = '".$memberOpening['op_principal']."', op_interest = '".$memberOpening['op_interest']."',op_tax = '".$memberOpening['op_tax']."' WHERE id = '$memberId' and society_id = $societyId";
        $update  = mysql_query($updateQue,$conn);
        if(!$update) {
            mysql_close($conn);
            die('Error in member balance updation'. mysql_error());
        } 
    }
    
}

mysql_close($conn);

echo 'Updated.';
exit;