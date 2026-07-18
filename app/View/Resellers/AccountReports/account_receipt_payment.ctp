<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Receipt & Payment</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="societyReportBillFrm" name="societyReportBillFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-12 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4">    
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">From date &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberPayment][payment_date]" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">    
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date-75" id="" name="data[MemberPayment][payment_date_to]" value="<?php echo isset($postData['MemberPayment']['payment_date_to']) ? $postData['MemberPayment']['payment_date_to'] :'' ; ?>">
                                            </div>  
                                        </div>  
                                        <div class="col-md-5">    
                                            <div class="form-group society-report-form">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                                                <button type="button" class="btn btn-success btn-anim btns" onclick="printAllReports('print_receipt_payment');"><i class="fa fa-print"></i><span> Print</span></button>
                                                <button type="button" class="btn btn-success btn-anim btns" onclick=""><span> Export to Excel</span></button>
                                            </div>
                                        </div>  
                                    </div> 
                                    <br>
                                </div>
                            </form> 
                            <div class="clearfix"></div><br><br>
                            <div id="print_receipt_payment">
                                <div class="print-receipt-payment">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="report-bill">Receipt & Payment
                                    <?php 
                                        if(!empty($postData['MemberPayment']['payment_date'])) echo "( ".date('d-m-Y',strtotime($postData['MemberPayment']['payment_date']))." To ";
                                        if(!empty($postData['MemberPayment']['payment_date_to'])) echo date('d-m-Y',strtotime($postData['MemberPayment']['payment_date_to']))." )";
                                    ?>                                    
                                    </div>
                                    <div class="report-bill"></div>  
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" style="width:100%;">
                                                
                                                    <tr>
                                                        <th valign="top">
                                                            <table id="" class="table table-bordered padding-td-none padding-th-none">
                                                                <thead>
                                                                    <th>Receipt</th>
                                                                    <th>Amount</th>
                                                                    <th>Amount</th>
                                                                </thead>
                                                                <tbody>
                                                        <tr>
                                                            <td><u>To Op. Cash & Bank Balances</u></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php
                                        
                                                        $diffReceipt = 0;
                                                        $diffPayment = 0;
                                                        $receiptheadCount = 0;
                                                        $countCashBankBalance = count($receipts['CashBankBalances']);
                                                        $countContriMember = count($receipts['MemberContribution']);
                                                        $receiptheadCount = $receiptheadCount + (($countCashBankBalance > 0) ? $countCashBankBalance + 2 : $countCashBankBalance + 1); 
                                                        $receiptheadCount = $receiptheadCount + (($countContriMember > 0) ? $countContriMember + 2 : $countContriMember + 1);
                                                        $countPayments = count($payments['Payments'])+1;
                                                        
                                                        $payCnt = 1;
                                                        $payCnt += count($payments['Payments']) > 0 ? count($payments['Payments'])+2 : count($payments['Payments'])+1;
                                                        $payCnt = $payCnt + count($BalanceData);
                                                        $payCnt = $payCnt + count($bankClosingData);                                                       
                
                                                        
                                                        if($countReceipts > $countPayments) {
                                                            $diffReceipt = $countReceipts-$countPayments;
                                                        } else if($countReceipts < $countPayments) {
                                                            $diffPayment = $countPayments-$countReceipts;
                                                        }
                                                        
                                                        $srCashBankBalance = 1;
                                                        $totalCashBankBalance = 0;
                                                        
                                                        foreach($receipts['CashBankBalances'] as $title=>$amount) {
                                                            
                                                            $totalCashBankBalance +=$amount; ?>
                                                            <tr>
                                                            <td><?php echo $title;?></td>
                                                            <td><?php echo $amount;?></td>
                                                            <td><?php
                                                            if($srCashBankBalance == $countCashBankBalance) { echo $totalCashBankBalance; }?></td>
                                                        </tr>
                                                        <?php $srCashBankBalance++; }

                                                        
                                                        ?>
                                                        <tr><td colspan="3">&nbsp;</td></tr>
                                                        <tr>
                                                            <td><u>To Contribution From Members</u></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php

                                                        $srContriMember = 1;
                                                        $totalContriMember = 0;
                                                        
                                                        foreach($receipts['MemberContribution'] as $title=>$amount) {
                                                                    
                                                                    $totalContriMember +=$amount; 
                                                        ?>
                                                            <tr>
                                                            <td><?php echo $title;?></td>
                                                            <td><?php echo $amount;?></td>
                                                            <td><?php  if($srContriMember == $countContriMember) { echo $totalContriMember; }?></td>
                                                        </tr>
                                                        <?php $srContriMember++;}  ?>
                                                        <?php

                                                        echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                        
                                                        if(!empty($receiptHeads)){
                                                            $allTotalReceipt = 0;
                                                            foreach($receiptHeads as  $heads){
                                                                $subHeadTotal = 0;
                                                                $headSubCat = $heads[0]['sub_head'];
                                                                echo "<tr><td><u>$headSubCat</u></td><td></td><td></td></tr>";    
                                                                foreach($heads as $index =>  $head){
                                                                    $headName = $head['title'];
                                                                    $headValue  = $head['amount_paid'];
                                                                    $paymentMode  = $head['mode'];
                                                                    $subHead  = $head['sub_head'];
                                                                    $subHeadTotal = $subHeadTotal + $headValue;
                                                                    $allTotalReceipt = $allTotalReceipt + $headValue;
                                                                    if(count($heads)-1 == $index)
                                                                      echo "<tr><td>$headName</td><td>$headValue</td><td>$subHeadTotal</td></tr>";    
                                                                    else
                                                                      echo "<tr><td>$headName</td><td>$headValue</td><td></td></tr>";    
                                                                $receiptheadCount++;      

                                                                }
                                                                echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                                $receiptheadCount =$receiptheadCount+2;

                                                            }
                                                            
                                                        }
                                                       $receiptCnttDiff = ( $payCnt > $receiptheadCount) ? $payCnt - $receiptheadCount : 0;  
                                                        for($i=0;$i<$receiptCnttDiff;$i++) {
                                                            echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><u>Total</u></td>
                                                            <td></td>
                                                            <td><?php
                                                            echo $totalCashBankBalance+$totalContriMember+$allTotalReceipt;?></td>
                                                        </tr>                                                        
                                                    </tbody>



                                                            </table>
                                                        </th>
                                                        <th valign="top">
                                                            <table id="" class="table table-bordered padding-td-none padding-th-none">
                                                                <thead>
                                                                    <th>Payment</th>
                                                                    <th>Amount</th>
                                                                    <th>Amount</th>
                                                                </thead>
                                                                <tbody>
                                                        <tr>
                                                            <td><u>By Expenditure</u></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php
                                                        $srPayment = 1;
                                                        $totalPayment = 0;
                                                        
                                                        foreach($payments['Payments'] as $title=>$amount) { $totalPayment +=$amount;?>
                                                            <tr>
                                                            <td><?php echo $title;?></td>
                                                            <td><?php echo $amount;?></td>
                                                            <td><?php if($srPayment == $countPayments-1) { echo $totalPayment; }?></td>
                                                        </tr>
                                                        <?php $srPayment++; }
                                                        ?>
                                                        <?php
                                                            echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                        ?>
                                                        
                                                        <tr>
                                                            <td><u>By Closing Balalnce of Cash & Bank</u></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <?php

                                                            $closingBalanceSum=0;
                                                            $totalPayment = $totalPayment + $BalanceData['cash_balance'] ;
                                                            $closingBalanceSum = $BalanceData['cash_balance'];

                                                            ?>
                                                            <td>Cash in hand</td>
                                                            <td><?= $BalanceData['cash_balance'];?></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php if(!empty($bankClosingData)) { 
                                                            $i = 0;
                                                            foreach($bankClosingData as $bankName => $closingValue){
                                                        ?>                                                       
                                                        <tr>
                                                            <?php 
                                                            $closingBalanceSum = $closingBalanceSum + $closingValue ; 
                                                            $totalPayment = $totalPayment + $closingValue ;?>
                                                            <td><?= $bankName ; ?></td>
                                                            <td><?= $closingValue ; ?></td>
                                                            <td>
                                                                <?php
                                                                if($i == count($bankClosingData)-1){
                                                                    echo $closingBalanceSum;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php $i++;}} 
                                                       $payCnttDiff = ($receiptheadCount > $payCnt) ? $receiptheadCount - $payCnt : 0;
                                                        for($i=0;$i<$payCnttDiff;$i++) {
                                                            echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                                                        }                                                        
                                                        ?>
                                                        <tr>
                                                            <td><u>Total</u></td>
                                                            <td></td>
                                                            <td><?php echo $totalPayment;?></td>
                                                        </tr>                                                        
                                                    </tbody>


                                                        

                                                            </table>
                                                        </th>
                                                    </tr>
                                                    
                                            </table>                                             
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <br>
                            <center>

                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

