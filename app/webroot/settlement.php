<?php
set_time_limit(0);
mysql_connect('localhost','vedhtkjm_easylog','easyDatabase123') or die('cant connect');
mysql_select_db('vedhtkjm_easylogics');

$societies = mysql_query("SELECT * FROM societies ORDER BY id ASC");
while($rowSocieties = mysql_fetch_array($societies)) {
    $societyId = $rowSocieties['id'];
    
    echo '<hr>';
    echo $societyId.'<br>';
    
    $payments = mysql_query("SELECT * FROM member_payments WHERE society_id = $societyId ORDER BY cdate ASC");
    
    $srReceiptId = 1;
    while($rowPayments = mysql_fetch_array($payments)) {
        $oldReceiptId = $rowPayments['receipt_id'];
        $newReceiptId = $srReceiptId;
        $paymentId = $rowPayments['id'];
        $memberId = $rowPayments['member_id'];
        $date = $rowPayments['cdate'];

        echo $paymentId.'->'.$memberId.'->'.$date.'->'.$oldReceiptId.'->'.$newReceiptId.'<br>';
        
        /*if($oldReceiptId != $newReceiptId && $oldReceiptId == 10) {
            $update = mysql_query("UPDATE member_payments SET receipt_id = $newReceiptId WHERE id = $paymentId") or die('Updation error'.mysql_error());
            echo $paymentId.'->'.$memberId.'->'.$date.'->'.$oldReceiptId.'->'.$newReceiptId.'<br>';
        }*/
        $srReceiptId++;
    }
}
//
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

