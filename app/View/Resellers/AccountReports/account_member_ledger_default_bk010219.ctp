<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Ledger</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberLedgerFrm" name="memberLedgerFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">                                            
                                        <div class="col-md-4 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberBillSummary][report_type]">
                                                    <option value="">Select</option>  
                                                    <option value="Default" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Default'){echo 'selected';}?>>Default</option>  
                                                    <option value="Regular" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Regular'){echo 'selected';}?>>Regular</option>  
                                                    <option value="Supplementary" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Supplementary'){echo 'selected';}?>>Supplementary</option>  
                                                    <option value="Combined" <?php if(isset($postData['MemberBillSummary']['report_type']) && $postData['MemberBillSummary']['report_type']=='Combined'){echo 'selected';}?>>Combined</option>                                                     
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">From &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][payment_date]" value="<?php echo isset($postData['MemberBillSummary']['payment_date']) ? $postData['MemberBillSummary']['payment_date'] :'' ; ?>">
                                            </div> 
                                        </div>
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">To &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][payment_date_to]" value="<?php echo isset($postData['MemberBillSummary']['payment_date_to']) ? $postData['MemberBillSummary']['payment_date_to'] :'' ; ?>">
                                            </div> 
                                        </div>                                                         
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_member_ledger');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>  
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                                <div class="col-md-4 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select (if required) </div> 
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Building &nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberBillSummary][building_id]" onchange="getAllBuildingWings(this.value, '#member_ledger');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberBillSummary']['building_id']) && $postData['MemberBillSummary']['building_id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?> 
                                                </select> 
                                            </div>
                                        </div>  
                                        <div class="col-md-12">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="member_ledger" name="data[MemberBillSummary][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no]" value="<?php echo isset($postData['MemberBillSummary']['flat_no']) ? $postData['MemberBillSummary']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberBillSummary][flat_no_to]" value="<?php echo isset($postData['MemberBillSummary']['flat_no_to']) ? $postData['MemberBillSummary']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
                                    </div>    
                                </div>   
                            </form> 
                             <div class="clearfix"></div>
                            <br><br><br>

                            <div id="print_member_ledger">
                            <div class="print-member-ledger">
                                <div class="row">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                </div>
                                <br>
                                <div class="row1">
                                    <div class="report-bill"></div>
                                    <div class="report-bill">Member Ledger-Regular</div>
                                    <div class="text-center"></div> 
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none" >
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="text-center">Date</th>
                                                        <th colspan="2" class="text-center">Reference</th>
                                                        <th colspan="2" class="text-center">Cheque</th>
                                                        <th rowspan="2" class="text-center">Debit</th>
                                                        <th rowspan="2" class="text-center">Credit</th>
                                                        <th rowspan="2" class="text-center">Balance</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Type.</th>
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php //TODO                                                    
                                                    if(isset($accountMemberLedgerDetails) && count($accountMemberLedgerDetails) > 0){
                                                    $idCount = 1;                                                 
                                                    foreach($accountMemberLedgerDetails as $MemberLedgerData){
                                                        if(!isset($MemberLedgerData['Member'])){ continue; }
                                                    ?>
                                                        <?php $innerCounter = 0; if(isset($MemberLedgerData['Member'])){?>
                                                        <tr id="">
                                                            <td width="8%" colspan="1" style="border:none !important"><span style="display: block;">Unit No : <?php echo isset($MemberLedgerData['Member']['flat_no']) ? $MemberLedgerData['Member']['flat_no'] : ''; ?></span></td>
                                                            <td colspan="7" style="border:none !important"><span style="display: block;">Member Name : <?php echo isset($MemberLedgerData['Member']['member_name']) ? $MemberLedgerData['Member']['member_name'] : ''; ?></span></td>
                                                        </tr>
                                                        <tr id="">
                                                            <td colspan="8" class="border-none">Building : <?php echo isset($MemberLedgerData['Member']['building_name']) ? $MemberLedgerData['Member']['building_name'] : ''; ?></td>
                                                        </tr>
                                                        <tr id="">
                                                            <td colspan="8" class="border-none"><span style="display: block;">Wing : <?php echo isset($MemberLedgerData['Member']['wing_name']) ? $MemberLedgerData['Member']['wing_name'] : ''; ?></span></td>
                                                        </tr>
                                                        <?php }
                                                     foreach($MemberLedgerData['MemberBillSummary'] as $MemberLedgerDetails){
                                                         //print_r($MemberLedgerDetails);
                                                         $openingDueAmount = $amountPayble =  0;
                                                         $amountPayble = isset($MemberLedgerDetails['amount_payable']) ? $MemberLedgerDetails['amount_payable']: '0.00'
                                                        ?>
                                                        <?php if($innerCounter == 0){
                                                            $openingDueAmount = isset($MemberLedgerData['MemberBillSummary'][0]['op_due_amount']) ? $MemberLedgerData['MemberBillSummary'][0]['op_due_amount'] : '0.00';
                                                        ?>
                                                        <tr id="">
                                                            <td><?php echo isset($MemberLedgerDetails['MemberBillSummary']['bill_generated_date']) ? $utilObj->mysqlToDate($MemberLedgerDetails['MemberBillSummary']['bill_generated_date'],'/'): '';  ?></td>
                                                            <td><span style="display: block;">Opening Balance</span></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['op_due_amount']) && $MemberLedgerDetails['op_due_amount'] > 0  ? $MemberLedgerDetails['op_due_amount'].' Dr': '0.00'; ?></td>
                                                            <td class="text-right"><?php echo isset($MemberLedgerDetails['op_due_amount']) && $MemberLedgerDetails['op_due_amount'] < 0 ? $MemberLedgerDetails['op_due_amount'].' Cr': '0.00'; ?></td>
                                                            <td class="text-right"><?php if($openingDueAmount > 0){ echo $openingDueAmount .' Dr'; }else{  echo $openingDueAmount .' Cr'; } ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <td><?php echo isset($MemberLedgerDetails['bill_generated_date']) ? $utilObj->mysqlToDate($MemberLedgerDetails['bill_generated_date'],'/'): '';  ?></td>
                                                            <td>Bill<span style="display: block;">For <?php echo isset($MemberLedgerDetails['month']) ? $utilObj->getMonthWordFormat($MemberLedgerDetails['month']): '';  ?></span></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberLedgerDetails['bill_no']) ? $MemberLedgerDetails['bill_no']: '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberLedgerDetails['cheque_reference_number']) ? $MemberLedgerDetails['cheque_reference_number']: '0';  ?></td>
                                                            <td width="8%" class="text-center"><?php echo isset($MemberLedgerDetails['payment_date']) ? $MemberLedgerDetails['payment_date']: '-';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo $amountPayble; ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberLedgerDetails['creadit_total']) ? $MemberLedgerDetails['creadit_total']: '0.00'; ?></td>
                                                            <td width="8%" class="text-right"><?php echo $amountPayble;  ?></td>
                                                        </tr>
                                                        <?php if(isset($MemberLedgerDetails['MemberPayment'])){
                                                                $creaditAmt = 0;
                                                            foreach($MemberLedgerDetails['MemberPayment'] as $MemberPaymentData){
                                                                $creaditAmt = isset($MemberPaymentData['amount_paid']) ? $MemberPaymentData['amount_paid']: '0.00';
                                                                $amountPayble = $amountPayble - $creaditAmt;
                                                            ?>
                                                        <tr>
                                                            <td><?php echo isset($MemberPaymentData['payment_date']) ? $utilObj->mysqlToDate($MemberPaymentData['payment_date'],'/'): '';  ?></td>
                                                            <td>Receipt <span style="display: block;">For <?php echo isset($MemberPaymentData['bill_month']) ? $utilObj->getMonthWordFormat($MemberPaymentData['bill_month']): '';  ?></span></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['receipt_id']) ? $MemberPaymentData['receipt_id']: '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['cheque_reference_number']) ? $MemberPaymentData['cheque_reference_number']: '0';  ?></td>
                                                            <td width="8%" class="text-center"><?php echo isset($MemberPaymentData['payment_date']) ? $MemberPaymentData['payment_date']: '-';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['debit_total']) ? $MemberPaymentData['debit_total']: '0.00'; ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['amount_paid']) ? $MemberPaymentData['amount_paid']: '0.00'; ?></td>
                                                            <td width="8%" class="text-right"><?php echo $amountPayble;  ?></td>
                                                        </tr>
                                                        <?php }} ?>                                                         
                                             
                                            <?php } ?>
                                            <?php if(isset($MemberLedgerData['JVData'])){                                                                
                                                            foreach($MemberLedgerData['JVData'] as $MemberPaymentData){
                                                            $credit = 0;
                                                            $debit = 0;
                                                            
                                                            if($MemberPaymentData['type'] == 'Credit') {
                                                                $credit = $MemberPaymentData['amount_paid'];
                                                                $amountPayble = $amountPayble + $credit;
                                                            } else if($MemberPaymentData['type'] == 'Debit') {
                                                                $debit = $MemberPaymentData['amount_paid'];
                                                                $amountPayble = $amountPayble - $debit;
                                                            }
                                                            ?>
                                                        <tr>
                                                            <td><?php echo isset($MemberPaymentData['payment_date']) ? $utilObj->mysqlToDate($MemberPaymentData['payment_date'],'/'): '';  ?></td>
                                                            <td><?php echo isset($MemberPaymentData['particulars']) ? $MemberPaymentData['particulars'] : '';  ?></span></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['voucher_no']) ? $MemberPaymentData['voucher_no']: '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo isset($MemberPaymentData['cheque_reference_number']) ? $MemberPaymentData['cheque_reference_number']: '0';  ?></td>
                                                            <td width="8%" class="text-center"><?php echo isset($MemberPaymentData['payment_date']) ? $utilObj->mysqlToDate($MemberPaymentData['payment_date'],'/'): '';  ?></td>
                                                            <td width="8%" class="text-right"><?php echo $debit; ?></td>
                                                            <td width="8%" class="text-right"><?php echo $credit; ?></td>
                                                            <td width="8%" class="text-right"><?php echo $amountPayble;  ?></td>
                                                        </tr>
                                                        <?php }}?>
                                            <?php $idCount++;$innerCounter++; } }?>
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