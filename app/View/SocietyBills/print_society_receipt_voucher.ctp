<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                             <div class="report-print-btn"><a class="print-btn pull-right" href="javascript:void(0);" onclick=""><i class="fa fa-print"></i><span class=""> Print Friendly</span></a></div>  <br><br>
                                <?php 
                                    $GrandTotal = 0;
                                    if(isset($memberPaymentReceiptVoucherData) && count($memberPaymentReceiptVoucherData) > 0){
                                     $idCount = 1;                                                 
                                     foreach($memberPaymentReceiptVoucherData as $receiptVoucherData){ ?>
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="report-bill">Receipt</div>
                                        <div class="receipt-voucher-section"> 
                                            <div class="row">
                                                <div class="col-md-6 col-xs-7">Receipt No : <?php echo isset($receiptVoucherData['MemberPayment']['bill_generated_id']) ? $receiptVoucherData['MemberPayment']['bill_generated_id'] :'' ; ?></div>
                                                <div class="col-md-6 col-xs-5 text-right">Date : <?php echo isset($receiptVoucherData['MemberPayment']['payment_date']) ? $receiptVoucherData['MemberPayment']['payment_date'] :'' ; ?></div>
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
                                                <div class="col-md-12">Drawn On : <?php echo isset($receiptVoucherData['MemberPayment']['member_bank_id']) ? $receiptVoucherData['MemberPayment']['member_bank_id'] :'' ;?></div>
                                            </div> 
                                            <div class="row">
                                                <div class="col-md-12">Being Maintenance Received</div>
                                                <div class="col-md-12 text-right">For <?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></div>
                                                <div class="clearfix"></div>  <br><br>
                                                <div class="col-md-12 text-right">Authorised Signature</div>                                        
                                                <div class="col-md-12">This Receipt is Valid Subject to realisation of cheque.</div>
                                            </div>
                                        </div>
                                    </div>
                                 <?php }}?>
                            <center><button type="button" class="btn btn-success  btn-icon left-icon" onclick="javascript:window.print();"><i class="fa fa-print"></i><span> Print</span></button></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
