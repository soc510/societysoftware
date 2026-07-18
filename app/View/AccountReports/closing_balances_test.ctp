<?php 
$NextyearString = ($startYear+1).'-'.($endYear+1);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div id="print_trial_balance">
                            <div class="print-trial-balance">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                <div class="report-bill" id="msgDiv"></div>
                                <form method="post" id="frmForward">

                                <input type="hidden" name="societyId" id="societyId" value="<?php echo $societyDetails['Society']['id'];?>">
                                <input type="hidden" name="yearId" id="yearId" value="<?php echo $this->Session->read('Auth.year_id');?>">
                                
                                <div class="report-bill">Ledger Head Closing Balances</div>
                                <div class="report-bill">Financial Year <?= "$startYear- $endYear"; ?></div> 

                                <div class="report-bill" style="text-align: right;"><input type="submit" id="btnLedgerOpeningBalance" value="Save Initial Ledger Balance "></div>
                                <div class="report-bill" style="text-align: right;"><input type="submit" id="btnOpeningBalance" value="Save Initial OP. Balance "></div> 
                                <div class="report-bill" style="text-align: right;"><input type="submit" id="btnForwardBalance" value="Forward Balances to <?=$NextyearString; ?>"></div>
                                <div class="report-bill" style="text-align: right;"><input type="button" id="btnForwardMemberBalance" value="Forward Member Balances to <?=$NextyearString;?>"></div>
                                <br>
                                <form id="trfLedgerHeadsData" method="post" action="">
                                <div class="report-bill-outer-section">
                                    <div class="table-wrap1">
                                        <table id="" class="table table-bordered1 padding-td-none padding-th-none" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sr</th>    
                                                    <th class="text-center">Head Id</th>
                                                    <th class="text-center">Title</th>
                                                    <th class="text-center">Credit</th>
                                                    <th class="text-center">Debit</th>
                                                    <th class="text-center">Balance</th>
                                                </tr>                                                
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sr=0;
                                            //print_r($ledgerClosingBalances);
                                            foreach($ledgerClosingBalances as $id=>$details) {
                                                //$details = $details[0];
                                                //$id = $details['ledger_head_id'];
                                                $sr++;
                                                
                                                if($details['closing_balance'] < 0) {
                                                    $flag = 'Dr';
                                                    $debit = $details['closing_debit'];
                                                    $credit = 0;
                                                } else {
                                                    $flag = 'Cr';
                                                    $credit = $details['closing_credit'];
                                                    $debit = 0;
                                                }
                                                
                                                $details['closing_balance'] = abs($details['closing_balance']);
                                                
                                            ?>
                                                <tr><td class="text-center border-top-bottom-bold"><?php echo $sr;?></td>
                                                    <td class="text-center border-top-bottom-bold"><input type="hidden" name="ledger_head_id_<?php echo $id?>" value="<?php echo $id?>"><?php echo $id;?></td>
                                                    <td class="text-center border-top-bottom-bold"><input type="hidden"><?php echo $details['title'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><input type="hidden" name="op_credit_<?php echo $id?>" id="op_credit_<?php echo $id?>" value="<?php echo abs($details['closing_credit']);?>"><?php echo abs($details['closing_credit']);?></td>
                                                    <td class="text-center border-top-bottom-bold"><input type="hidden" name="op_debit_<?php echo $id?>" id="op_debit_<?php echo $id?>" value="<?php echo abs($details['closing_debit']);?>"><?php echo abs($details['closing_debit']); ?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php if ($flag == 'Cr'){?><?php } else {?><?php } ?><?php echo $details['closing_balance'].' '.$flag;;?></td>
                                                </tr> 
                                            <?php } ?>
                                            <tr style="background-color: #d2d2d2;">
                                                            <td colspan="3" class="text-center"><b>Grand Total</b></td>
                                                            <td class="text-center" id="grandTotalCredit"><?php 
                                                            echo number_format($totalLedgerCredit,2);
                                                            ?></td>
                                                            <td class="text-center" id='grandTotalDebit'><b ><?php echo number_format($totalLedgerDebit,2);?></b></td>
                                                            <td class="text-center" id='grandTotal'><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;?></td>
                                            </tr>                                                    
                                            </tbody>   
                                        </table> 
                                    </div>
                                </div> 
                                </form>
                                <br>
                                <div class="report-bill">Member Closing Balances</div>
                                <div class="report-bill">Financial Year <?= "$startYear- $endYear"; ?> (Regular Bill) </div>  
                                <div class="report-bill-outer-section">
                                    <div class="table-wrap1">
                                        <table id="" class="table table-bordered1 padding-td-none padding-th-none" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sr</th>    
                                                    <th class="text-center">Member Id</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Principal</th>
                                                    <th class="text-center">Interest</th>
                                                    <th class="text-center">Tax</th>
                                                    <th class="text-center">Total Balance</th>
                                                </tr>                                                
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sr=0;
                                            foreach($memberClosingBalances[0] as  $memdetails) {

                                                //$memdetails = $memdetails[0];
                                                if($memdetails['bill_type'] != 'reg') continue;
                                                $id = $memdetails['member_id'];
                                                $sr++;
                                                $memdetails['balance_amount'] = $memdetails['principal'] + $memdetails['interest'] +$memdetails['tax'];
                                            ?>
                                                <tr>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $sr;?></td>
                                                    <td class="text-center border-top-bottom-bold" id="regid_<?php echo $id?>"><?php echo $id;?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['member_name'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['principal'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['interest'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['tax'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['balance_amount'];?></td>
                                                </tr> 
                                            <?php } ?>                                                    
                                            </tbody>   
                                        </table> 
                                    </div>
                                </div>
                                <br>
                                <div class="report-bill">Member Closing Balances</div>
                                <div class="report-bill">Financial Year <?= "$startYear- $endYear"; ?> (Supplemenatry Bill) </div>  
                                <div class="report-bill-outer-section">
                                    <div class="table-wrap1">
                                        <table id="" class="table table-bordered1 padding-td-none padding-th-none" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sr</th>    
                                                    <th class="text-center">Member Id</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Principal</th>
                                                    <th class="text-center">Interest</th>
                                                    <th class="text-center">Tax</th>
                                                    <th class="text-center">Total Balance</th>
                                                </tr>                                                
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sr=0;
                                            if (!empty($memberClosingBalances[1])){
                                            foreach($memberClosingBalances[1] as  $memdetails) {
                                                //$memdetails = $memdetails[1];
                                                if($memdetails['bill_type'] != 'sup') continue;
                                                $id = $memdetails['member_id'];
                                                $sr++;
                                                $memdetails['balance_amount'] = $memdetails['principal'] + $memdetails['interest'] +$memdetails['tax'];
                                            ?>
                                                <tr>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $sr;?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $id;?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['member_name'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['principal'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['interest'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['tax'];?></td>
                                                    <td class="text-center border-top-bottom-bold"><?php echo $memdetails['balance_amount'];?></td>
                                                </tr> 
                                            <?php } }?>                                                    
                                            </tbody>   
                                        </table> 
                                    </div>
                                </div>
                                </form>
                            </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->webroot; ?>js/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(e) {
        var cSum=0;$('[id^="op_credit_"]').map(function(){cSum+=parseInt($(this).val());});console.log(cSum);
        var dSum=0;$('[id^="op_debit_"]').map(function(){dSum+=parseInt($(this).val());});console.log(dSum);
        //var gSum=0;$('[id^="op_credit_"]').map(function(){sum+=parseInt($(this).val());});console.log(sum);
        var gTotal = cSum-dSum;

if(gTotal > 0)
    gTotal = gTotal+' Cr';
else
    gTotal = gTotal+' Dr';
        $('#grandTotalCredit').text(cSum);
        $('#grandTotalDebit').text(dSum);
        $('#grandTotal').text(gTotal);

      
        $("#btnForwardMemberBalance").click(function() {
            var ids=[];var idtrs = $('[id^="regid_"]').map(function(){return this.id.replace('regid_','');}).get().join(',');
             $.post("transferMemberClosingBalance",{ids:idtrs})
            // Serialization looks good: name=textInNameInput&&telefon=textInPhoneInput etc
            .done(function(data) {
                $('#msgDiv').html('<b>'+data+'</b>');
            });

            return false;
        });
        $("#btnOpeningBalance").click(function() {
           
             $.post("transferMemberOpeningBalance",'')
            
            .done(function(data) {
                $('#msgDiv').html('<b>'+data+'</b>');
            });

            return false;
        });
        $("#btnLedgerOpeningBalance").click(function() {
		   var yearId=$('#yearId').val()-1;
            $.post("transferLedgerOpeningBalance",{yearId:yearId})
            
            .done(function(data) {
                $('#msgDiv').html('<b>'+data+'</b>');
            });

            return false;
        });
        $("#frmForward").submit(function() {
           
           //var formData=$(this).serialize();

             const array = $(this).serializeArray(); // Encodes the set of form elements as an array of names and values.
  const json = {};
  $.each(array, function () {
    json[this.name] = this.value || "";
  });
  
             $.post("/account_reports/ledger_data_transfer",{data:JSON.stringify(json)})
             .done(function(data) {
                $('#msgDiv').html('<b>'+data+'</b>');
            });

            return false;
            
        })
    });
</script>

