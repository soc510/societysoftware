<?php
set_time_limit(0);
$conn = mysqli_connect('localhost','vedhtkjm_easylog','easyDatabase123');
if(!$conn) { 
    echo 'Database connectivity issue '.mysqli_error($conn);    
exit;
    
}
mysqli_select_db($conn,'vedhtkjm_easy201920') or die('DB selection issue'.mysqli_error($conn));
// echo '<pre>';
if(!empty($_POST)) {    
    $societyId = $_POST['societyId'];      
    $sql = "select id from members where society_id='$societyId'";       
    $query = mysqli_query($conn,$sql);    
    $i = 0;      
    $members = array();      
    while($res = mysqli_fetch_assoc($query)){ 
        $members[$i] = $res['id'];   
        $i++;
    }           
    $memberArr = array();      
    
    foreach ($members as $k=>$id) {          
        // if($id != 8127) continue;
        
        echo "----------------------FOR $id ------------------------------------<br/>";
        $result = array();          
        $sql = "select principal_balance,interest_balance,tax_balance,balance_amount, member_id, bill_generated_date from member_bill_summaries WHERE society_id = '$societyId' AND member_id = '$id' ORDER BY bill_generated_date DESC LIMIT 0,1";             
        $query = mysqli_query($conn,$sql);   
        $result = mysqli_fetch_assoc($query);          
        $memberArr[$id]['principal_balance'] = 0.00;       
        if (isset($result['balance_amount'])){             
            $memberArr[$id]['principal_balance'] = $result['principal_balance'];          
        }           
        
        $memberArr[$id]['interest_balance'] = 0.00;      
        if (isset($result['balance_amount'])){            
            $memberArr[$id]['interest_balance'] = $result['interest_balance'];     
        }             
        
        $memberArr[$id]['tax_balance'] = 0.00;         
        if (isset($result['balance_amount'])){         
            $memberArr[$id]['tax_balance'] = $result['tax_balance'];     
        }
        
        echo "originalAmount".$memberArr[$id]['principal_balance']."--".$memberArr[$id]['interest_balance']."--".$memberArr[$id]['tax_balance']."<br/>";
            
        $psql = "select amount_paid,member_id,payment_date from member_payments where society_id='$societyId' and member_id = '$id' and payment_date > '".$result['bill_generated_date']."'";          
        $pquery = mysqli_query($conn,$psql);                          
        $payment = 0;        
        while($pres = mysqli_fetch_assoc($pquery)){                  
            $payment += $pres['amount_paid'];                          
        }            
            
        echo "$payment --".$memberArr[$id]['principal_balance']."--".$memberArr[$id]['interest_balance']."--".$memberArr[$id]['tax_balance']."<br/>";;
        
        $bDate = $result['bill_generated_date'].' 00:00:00';     
        $jsql = "select jv_amount_credited,jv_amount_debited,jv_credit_member_head_id,cdate from journal_vouchers where society_id='$societyId' and jv_credit_member_head_id = '$id' and cdate > '".$bDate."'";       
        $jquery = mysqli_query($conn,$jsql);                           
        $jvCredited = 0;
        $jvDebited = 0;
            
        while($jres = mysqli_fetch_assoc($jquery)){                    
            $jvCredited += $jres['jv_amount_credited'];                     
            $jvDebited += $jres['jv_amount_debited'];
        }       
        echo "$jvDebited  $jvCredited".$memberArr[$id]['principal_balance']."--".$memberArr[$id]['interest_balance']."--".$memberArr[$id]['tax_balance']."<br/>";;    
            $amount = $payment+$jvDebited-$jvCredited;
        echo "$amount -- ".$memberArr[$id]['principal_balance']."--".$memberArr[$id]['interest_balance']."--".$memberArr[$id]['tax_balance']."<br/>";;    
            if ($amount > 0) {                     
                if ($amount >= $memberArr[$id]['tax_balance']) {         
                    $amount = $amount - $memberArr[$id]['tax_balance'];       
                    $memberArr[$id]['tax_balance'] = 0.00;                  
                } else {                          
                    $memberArr[$id]['tax_balance'] = $memberArr[$id]['tax_balance'] - $amount;                 
                    $amount = 0;                     
                }                 
            }                
            
            echo "after tax deduction -- $amount ---".$memberArr[$id]['principal_balance']."--".$memberArr[$id]['interest_balance']."--".$memberArr[$id]['tax_balance']."<br/>";;    
            
            if ($amount > 0) {             
                if ($amount >= $memberArr[$id]['interest_balance']) {          
                    $amount = $amount - $memberArr[$id]['interest_balance'];         
                    $memberArr[$id]['interest_balance'] = 0.00;                 
                } else {                       
                    $memberArr[$id]['interest_balance'] = $memberArr[$id]['interest_balance'] - $amount;    
                    $amount = 0;                       
                }              
            }                  
            
            echo "after intrest -- $amount--".$memberArr[$id]['principal_balance']."--".$memberArr[$id]['interest_balance']."--".$memberArr[$id]['tax_balance']."<br/>";;    
            if ($amount > 0) {      
                if ($amount >= $memberArr[$id]['principal_balance']) {         
                    $amount = $amount - $memberArr[$id]['principal_balance'];       
                    $memberArr[$id]['principal_balance'] = 0.00;                     
                } else {                                                      
                    $memberArr[$id]['principal_balance'] = $memberArr[$id]['principal_balance'] - $amount;       
                    $amount = 0;                     
                }                  
            }        
            
            echo "after principal -- $amount--".$memberArr[$id]['principal_balance']."--".$memberArr[$id]['interest_balance']."--".$memberArr[$id]['tax_balance']."<br/>";;    
    }
    // echo "<br>";
    // print_r($memberArr);
     // exit;   
        if(!empty($memberArr)) {       
            mysqli_select_db($conn,'vedhtkjm_easylogics') or die('DB selection issue'.mysqli_error($conn));       
            foreach($memberArr as $memberId=>$memberOpening) {          
                //if(count($memberOpening))                  
                $updateQue = "UPDATE members SET op_principal = '".$memberOpening['principal_balance']."', op_interest = '".$memberOpening['interest_balance']."',op_tax = '".$memberOpening['tax_balance']."' WHERE id = '$memberId' and society_id = $societyId";         
                $update  = mysqli_query($conn,$updateQue);                  
                if(!$update) {                        
                    mysqli_close($conn);            
                    die('Error in member balance updation'. mysqli_error($conn));      
                }
            } 
        }
    
}
mysqli_close($conn);
echo 'Opening balances of members has been set correctly for next year';exit;