<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">General Ledger</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberMonthlyControFrm" name="memberMonthlyControFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">  
                                        <div class="col-md-2 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Report &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="data[SocietyPayment][report_type]" required>
                                                    <option value="">Select</option>  
                                                    <option selected value="Detail" <?php if(isset($postData['SocietyPayment']['report_type']) && $postData['SocietyPayment']['report_type']=='Detail'){echo 'selected';} ?>>Detail</option>  
                                                    <!--<option value="Summary" <?php if(isset($postData['SocietyPayment']['report_type']) && $postData['SocietyPayment']['report_type']=='Summary'){echo 'selected';} ?>>Summary</option>-->  
                                                </select> 
                                            </div> 
                                        </div>      
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Ledger For &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-65" id="ledger_for" name="data[SocietyPayment][ledger_for]" onchange="checkLedger()" required>
                                                    <option value="">Select</option>  
                                                    <option value="Particular Subgroup" <?php if(isset($postData['SocietyPayment']['ledger_for']) && $postData['SocietyPayment']['ledger_for']=='Particular Subgroup'){echo 'selected';} ?>>Particular Subgroup</option>  
                                                    <option value="All" <?php if(isset($postData['SocietyPayment']['ledger_for']) && $postData['SocietyPayment']['ledger_for']=='All'){echo 'selected';} ?>>All</option>  
                                                    <option value="All(Excluding Bank,Cash,Member,Tariff)" <?php if(isset($postData['SocietyPayment']['ledger_for']) && $postData['SocietyPayment']['ledger_for']=='All(Excluding Bank,Cash,Member,Tariff)'){echo 'selected';} ?>>All(Excluding Bank,Cash,Member,Tariff)</option>  
                                                </select> 
                                            </div> 
                                        </div>  
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">A/c Name &nbsp;</div>  <?php //echo '<pre>'; print_r($societyLedgerHeads);?>
                                                <select class="account-report-form-box-field-dropdown-65" id="account_name" name="data[SocietyPayment][account_name]" <?php if(isset($postData['SocietyPayment']['ledger_for']) && $postData['SocietyPayment']['ledger_for']!='Particular Subgroup'){echo 'disabled="true"';} ?> required>
                                                    <option value="">Select</option> 
                                                    <?php
                                                        $num =count($societyLedgerHeads);
                                                        for ($i=0; $i<$num; $i++) {
                                                    ?>
                                                    <option value="<?php echo $societyLedgerHeads[$i]['SocietyLedgerHeads']['id'];?>" <?php if(isset($postData['SocietyPayment']['account_name']) && $postData['SocietyPayment']['account_name']==$societyLedgerHeads[$i]['SocietyLedgerHeads']['id']){echo 'selected';} ?>><?php echo $societyLedgerHeads[$i]['SocietyLedgerHeads']['title'];?></option> 
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                                <!--<input disabled="true" required type="text" class="account-report-form-box-field-70" id="account_name" name="data[SocietyPayment][account_name]" value="<?php //echo isset($postData['SocietyPayment']['account_name']) ? $postData['SocietyPayment']['account_name'] :'' ; ?>">-->
                                            </div> 
                                        </div>                                                       
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">From &nbsp;</div>  
                                                    <input type="date" class="account-report-form-box-field-date-75" id="" name="data[SocietyPayment][payment_date]" value="<?php echo isset($postData['SocietyPayment']['payment_date']) ? $postData['SocietyPayment']['payment_date'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>                                           
                                        <div class="col-md-2 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-field-box">   
                                                    <div class="account-report-form-box-label">To &nbsp;</div>  
                                                    <input type="date" class="account-report-form-box-field-date-75" id="" name="data[SocietyPayment][payment_date_to]" value="<?php echo isset($postData['SocietyPayment']['payment_date_to']) ? $postData['SocietyPayment']['payment_date_to'] :'' ; ?>">
                                                </div> 
                                            </div>
                                        </div>                                                      
                                    </div>  
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="account-report-form-field-box">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_general_ledger');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>                                          
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>      
                                    </div>
                                </div> 
                            </form> 

                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_general_ledger">
                                <div class="print-general-ledger">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row1">
                                        <div class="report-bill">General Ledger</div>
                                        <div class="report-bill"></div>
                                        <div class="text-center"></div>
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap1">
                                                <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                    <thead>
                                                        <tr>
                                                            <th width="12%" rowspan="2" class="text-center">Date</th>
                                                            <th colspan="2" class="text-center">Reference Type</th>
                                                            <th colspan="2"  class="text-center">Cheque</th>
                                                            <th rowspan="2" class="text-center">Dr. Amount</th>
                                                            <th rowspan="2" class="text-center">Cr. Amount</th>
                                                            <th rowspan="2" class="text-center">Balance</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Type</th>
                                                            <th class="text-center">No.</th>
                                                            <th class="text-center">No.</th>
                                                            <th class="text-center">Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php //TODO
                                                        $num = count($societyLedgerHeadsData);
                                                        if(isset($societyLedgerHeadsData) && $num > 0){

                                                        foreach($societyLedgerHeadsData as $ledgerHeadId => $ledgerHeadDetail){
                                                            $societyLedgerHeadsDetails = $ledgerHeadDetail['data'];
                                                            $title = $ledgerHeadDetail['title'];
                                                            
                                                            $totalDebit = 0;
                                                            $totalCredit = 0;

                                                        ?>
                                                            <tr><td colspan="8"><strong><?php echo $title;?></strong></td></tr>
                                                        <?php
                                                        $indBalance = 0;
                                                            if(isset($societyLedgerHeadsDetails['txn_type']) && $societyLedgerHeadsDetails['txn_type'] == 'debit') {
                                                            $openingDebit = !empty($societyLedgerHeadsDetails['SocietyLedgerHeads']['opening_amount']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['opening_amount'] : 0;
                                                            $openingCredit = 0;
                                                            $openingBalance = $openingDebit;
                                                            $posReg = ' Dr.';
                                                            $totalDebit += $openingDebit;
                                                            $indBalance += $openingBalance;
                                                        } else if(isset($societyLedgerHeadsDetails['txn_type']) && $societyLedgerHeadsDetails['txn_type'] == 'credit') {
                                                            $openingCredit = !empty($societyLedgerHeadsDetails['SocietyLedgerHeads']['opening_amount']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['opening_amount'] : 0;
                                                            $openingDebit = 0;
                                                            $openingBalance = $openingCredit;
                                                            $posReg = ' Cr.';
                                                            $totalCredit += $openingCredit;
                                                            $indBalance -= $openingBalance;
                                                        }
                                                        ?>
                                                        <tr id="">
                                                            <td class="opening-bal"><?php echo (date('m') < 4) ? '01/04/'.date('Y', strtotime('-1 year')) : '01/04'.date('Y');   ?></td>
                                                            <td class="opening-bal text-right" colspan="4">Opening Balance</td>
                                                            <td class="opening-bal text-right"><?php echo number_format($openingDebit,2);?></td>
                                                            <td class="opening-bal text-right"><?php echo number_format($openingCredit,2);?></td>
                                                            <td class="opening-bal text-right"><?php echo number_format($openingBalance,2).''.$posReg;?></td>
                                                        </tr>
                                                        <?php if(isset($societyLedgerHeadsDetails['ledgerPaymentData'])) {
                                                        $particularNote = 'To Payment';

                                                        if($societyLedgerHeadsDetails['SocietyLedgerHeads']['is_in_bill_charges'] == 1) {
                                                            $particularNote = 'By Bill';
                                                        }

                                                        

                                                        foreach($societyLedgerHeadsDetails['ledgerPaymentData'] as $paymentData) {
                                                            $credit = 0;
                                                            $debit = 0;

                                                            $particular = isset($paymentData['particularNote']) && !empty($paymentData['particularNote']) ? $paymentData['particularNote'] : $particularNote;
                                                            

                                                            if($paymentData['txn_type'] == 'credit') {
                                                               $credit = $paymentData['amount'];
                                                               $indBalance = $indBalance-$credit;
                                                               $posNeg = 'Cr';
                                                               $totalCredit += $credit;
                                                            } else if($paymentData['txn_type'] == 'debit') {
                                                               $debit = $paymentData['amount'];
                                                               $indBalance = $indBalance+$debit;
                                                               $posNeg = 'Dr';
                                                               $totalDebit += $debit;
                                                            }   
                                                            if($indBalance < 0) {
                                                                $posNeg = 'Cr';
                                                            } else { $posNeg = 'Dr';}
                                                        ?>
                                                        <tr id="">
                                                            <td><?php echo $paymentData['payment_date'];?></td>
                                                            <td><?php echo isset($particular) ? $particular : ''; ?></td>
                                                            <td><?php echo $paymentData['reference_no'];?></td>
                                                            <td><?php echo $paymentData['cheque_no'];?></td>
                                                            <td><?php echo $paymentData['cheque_date'];?></td>
                                                            <td class="text-right"><?php echo number_format($debit,2);?></td>
                                                            <td class="text-right"><?php echo number_format($credit,2);?></td>
                                                            <td class="text-right"><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;?></td>
                                                        </tr>
                                                        <?php }} ?>
                                                        <?php
                                                        if($indBalance < 0) {
                                                            $posNeg = 'Cr';
                                                        } else { $posNeg = 'Dr';}
                                                        ?>
                                                        <tr style="background-color: #d2d2d2;">
                                                            <td colspan="5" class="text-right">Total</td>
                                                            <td class="text-right"><?php echo number_format($totalDebit,2);?></td>
                                                            <td class="text-right"><?php echo number_format($totalCredit,2);?></td>
                                                            <td class="text-right"><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;?></td>
                                                        </tr>
                                                <?php  } }else{?>
                                                        <tr><td colspan="8"><center>No Data found. </center></td></tr>
                                                <?php }?>
                                                    </tbody> 
                                                </table> 
                                            </div>
                                        </div>  
                                    </div>  
                                </div>  
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function checkLedger() {
        var x = document.getElementById("ledger_for").value;
        if (x == 'Particular Subgroup') {
            document.getElementById("account_name").disabled = false;
        } else {
            document.getElementById("account_name").selectedIndex = 0;
            document.getElementById("account_name").disabled = true;
        }
    }
</script>