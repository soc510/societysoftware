<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Receipt Voucher</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="Society_Receipt_Voucher_Print" name="Society_Receipt_Voucher_Print" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-12">    
                                            <div class="society-report-form-box-label">Receipt For &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="" name="data[MemberPayment][receipt_for]">
                                                <option value="">Select Category</option>
                                                <option value="General"<?php if(isset($postData['MemberPayment']['receipt_for']) && $postData['MemberPayment']['receipt_for']=='General'){echo 'selected';} ?>>General</option>
                                                <option value="Regular"<?php if(isset($postData['MemberPayment']['receipt_for']) && $postData['MemberPayment']['receipt_for']=='Regular'){echo 'selected';} ?>>Regular</option>
                                                <option value="Supplementary"<?php if(isset($postData['MemberPayment']['receipt_for']) && $postData['MemberPayment']['receipt_for']=='Supplementary'){echo 'selected';} ?>>Supplementary</option>
                                            </select> 
                                        </div>                                                             
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">    
                                            <div class="society-report-form-box-label">Receipt No &nbsp;</div>
                                            <input type="text" class="color-333" id="" name="data[MemberPayment][bill_no]" value="<?php echo isset($postData['MemberPayment']['bill_no']) ? $postData['MemberPayment']['bill_no'] :'' ; ?>">
                                        </div>
                                        <div class="col-md-6">    
                                            <div class="society-report-form-box-label">To &nbsp;</div>
                                            <input type="text" class="color-333" id="" name="data[MemberPayment][bill_no_to]" value="<?php echo isset($postData['MemberPayment']['bill_no_to']) ? $postData['MemberPayment']['bill_no_to'] :'' ; ?>">
                                        </div>                                                            
                                    </div>   
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">    
                                            <div class="society-report-form-box-label">From Date &nbsp;</div>                                                           
                                            <input type="date" class="society-report-form-box-field-date-45" id="" name="data[MemberPayment][bill_from]" value="<?php echo isset($postData['MemberPayment']['bill_from']) ? $postData['MemberPayment']['bill_from'] :'' ; ?>">
                                        </div>
                                        <div class="col-md-6">    
                                            <div class="society-report-form-box-label">To &nbsp;</div>                                                           
                                            <input type="date" class="society-report-form-box-field-date-45" id="" name="data[MemberPayment][bill_to]" value="<?php echo isset($postData['MemberPayment']['bill_to']) ? $postData['MemberPayment']['bill_to'] :'' ; ?>">
                                        </div>                                                            
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>   
                                                <button class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_society_receipt_voucher')"><i class="fa fa-print"></i><span class="btn-text"> Print Friendly</span></button> 
                                                <button class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text"> Export to PDF</span></button>
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btn-sm" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                                <div class="col-md-4 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select if required </div> 
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Building &nbsp;</div>   
                                                <select name="data[MemberPayment][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#society_receipt_voucher');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberPayment']['building_id']) && $postData['MemberPayment']['building_id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="society-report-form-box-field-dropdown" id="society_receipt_voucher" name="data[MemberPayment][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Flat No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberPayment][flat_no]" value="<?php echo isset($postData['MemberPayment']['flat_no']) ? $postData['MemberPayment']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberPayment][flat_no_to]" value="<?php echo isset($postData['MemberPayment']['flat_no_to']) ? $postData['MemberPayment']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
                                    </div>    
                                </div>  
                            </form> 
                            <div class="clearfix"></div>  
                            <div id="print_society_receipt_voucher">
                                 <?php 
                                    $GrandTotal = 0;
                                    
                                    //Prepare bank array
                                    $allBankListsArray = array();
                                    if(!empty($allBankLists))  {
                                        foreach($allBankLists as $bank) {
                                           $allBankListsArray[$bank['Bank']['id']] = $bank['Bank']['bank_name']; 
                                        }
                                    }
                                    
                                    if(isset($memberPaymentReceiptVoucherData) && count($memberPaymentReceiptVoucherData) > 0){
                                     $idCount = 1;  
                                     foreach($memberPaymentReceiptVoucherData as $receiptVoucherData){
                                        $receiptDate = isset($receiptVoucherData['MemberPayment']['payment_date']) ? $receiptVoucherData['MemberPayment']['payment_date'] :'' ; 
                                         
                                        ?>
                                        <div class="row print-society-receipt-voucher">
                                             <div class="receipt-voucher-section">
                                            <h5 class="text-center "><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                            <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                            <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                            
                                            
                                            <div class="report-bill">Receipt</div>
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-7">Receipt No : <?php echo isset($receiptVoucherData['MemberPayment']['receipt_id']) ? $receiptVoucherData['MemberPayment']['receipt_id'] :'' ; ?></div>
                                                    <div class="col-md-6 col-xs-5 text-right">Date : <?php echo date('d/m/Y',strtotime($receiptDate)); ?></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-10">Received with Thanks From : <?php echo isset($receiptVoucherData['Member']['member_prefix']) ? $receiptVoucherData['Member']['member_prefix'] :'' ;?><?php echo isset($receiptVoucherData['Member']['member_name']) ? $receiptVoucherData['Member']['member_name'] :'' ; ?></div>
                                                    <div class="col-md-6 col-xs-2">Unit No : <?php echo isset($receiptVoucherData['Member']['flat_no']) ? $receiptVoucherData['Member']['flat_no'] :'' ; ?></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">Rs. <?php echo isset($receiptVoucherData['MemberPayment']['amount_paid']) ? $receiptVoucherData['MemberPayment']['amount_paid'] :'' ; ?> (<?php echo isset($receiptVoucherData['MemberPayment']['amount_paid']) ? $utilObj->getAmountInRupees($receiptVoucherData['MemberPayment']['amount_paid']) :'' ; ?> )</div>                                                
                                                </div> 
                                                <div class="row">
                                                    <div class="col-md-12">Towards: Bill No:-<?php echo isset($receiptVoucherData['MemberPayment']['bill_generated_id']) ? $receiptVoucherData['MemberPayment']['bill_generated_id'] :'' ;?>, Bill Date:- <?php echo isset($receiptVoucherData['MemberPayment']['payment_date']) ? $utilObj->mysqlToDate($receiptVoucherData['MemberPayment']['payment_date'],'/') :'' ;?> For <?php echo isset($receiptVoucherData['MemberPayment']['payment_date']) ? $utilObj->getFinancialYearFromDate($receiptVoucherData['MemberPayment']['payment_date'],'/') :'' ;?></div>
                                                    <div class="col-md-12">By Cheque No: <?php echo isset($receiptVoucherData['MemberPayment']['cheque_reference_number']) ? $receiptVoucherData['MemberPayment']['cheque_reference_number'] :'' ;?>&nbsp;&nbsp;&nbsp;Dated on : <?php echo isset($receiptVoucherData['MemberPayment']['payment_date']) ? $utilObj->mysqlToDate($receiptVoucherData['MemberPayment']['payment_date'],'/') :'' ;?></div>                                                
                                                    <div class="col-md-12">Drawn On : <?php echo isset($receiptVoucherData['MemberPayment']['member_bank_id']) ? $allBankListsArray[$receiptVoucherData['MemberPayment']['member_bank_id']] :'' ;?></div>
                                                </div> 
                                                <div class="row">
                                                    <div class="col-md-12">Being Maintenance Received</div>
                                                    <div class="col-md-12 text-right">For <?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></div>
                                                    <div class="clearfix"></div>  <br><br>
                                                    <div class="col-md-12 text-right"><?php echo isset($societyDetails['Society']['authorised_person']) ? $societyDetails['Society']['authorised_person'] : ''; ?></div>                                        
                                                    <div class="col-md-12">This Receipt is Valid Subject to realisation of cheque.</div>
                                                </div>
                                            </div>
                                        </div>
                                 <?php }}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>