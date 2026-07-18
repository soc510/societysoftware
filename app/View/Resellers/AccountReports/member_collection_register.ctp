<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Collection Register</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="societyReportBillFrm" name="societyReportBillFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-9 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4">    
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">Receipt date &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberPayment][payment_date]" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">    
                                            <div class="account-report-form-field-box"> 
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                                                     
                                                <input type="date" class="account-report-form-box-field-date-75" id="" name="data[MemberPayment][payment_date_to]" value="<?php echo isset($postData['MemberPayment']['payment_date_to']) ? $postData['MemberPayment']['payment_date_to'] :'' ; ?>">
                                            </div>  
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">From Slip No &nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberPayment][receipt_id]" value="<?php echo isset($postData['MemberPayment']['receipt_id']) ? $postData['MemberPayment']['receipt_id'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-2">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-70" id="" name="data[MemberPayment][receipt_id_to]" value="<?php echo isset($postData['MemberPayment']['receipt_id_to']) ? $postData['MemberPayment']['receipt_id_to'] :'' ; ?>">
                                            </div>
                                        </div>                                                                 
                                    </div> 
                                    <br>
                                    <div class="row">
                                        
                                    </div>
                                    <div class="form-group society-report-form">
                                        <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                                        <button type="button" class="btn btn-success btn-anim btns" onclick="printAllReports('print_member_collection_register');"><i class="fa fa-print"></i><span class="btn-text"> Print</span></button>
                                        <?php 
                                            $month = isset($postData['MemberPayment']['month']) && !empty($postData['MemberPayment']['month']) ? base64_encode($postData['MemberPayment']['month']) : '';
                                            $month_to = isset($postData['MemberPayment']['month_to']) && !empty($postData['MemberPayment']['month_to']) ? base64_encode($postData['MemberPayment']['month_to']) : '';
                                            $bill_date = isset($postData['MemberPayment']['payment_date']) && !empty($postData['MemberPayment']['payment_date']) ? base64_encode($postData['MemberPayment']['payment_date']) : '';
                                            $bill_date_to = isset($postData['MemberPayment']['payment_date_to']) && !empty($postData['MemberPayment']['payment_date_to']) ? base64_encode($postData['MemberPayment']['payment_date_to']) : '';
                                            $receiptID = isset($postData['MemberPayment']['receipt_id']) && !empty($postData['MemberPayment']['receipt_id']) ? base64_encode($postData['MemberPayment']['receipt_id']) : '';
                                            $receiptIdTo = isset($postData['MemberPayment']['receipt_id_to']) && !empty($postData['MemberPayment']['receipt_id_to']) ? base64_encode($postData['MemberPayment']['receipt_id_to']) : '';
                                            $building_id = isset($postData['MemberPayment']['building_id']) && !empty($postData['MemberPayment']['building_id']) ? base64_encode($postData['MemberPayment']['building_id']) : '';
                                            $flat_no = isset($postData['MemberPayment']['flat_no']) && !empty($postData['MemberPayment']['flat_no']) ? base64_encode($postData['MemberPayment']['flat_no']) : '';
                                            $flat_no_to = isset($postData['MemberPayment']['flat_no_to']) && !empty($postData['MemberPayment']['flat_no_to']) ? base64_encode($postData['MemberPayment']['flat_no_to']) : '';
                                            $wing_id = isset($postData['MemberPayment']['wing_id']) && !empty($postData['MemberPayment']['wing_id']) ? base64_encode($postData['MemberPayment']['wing_id']) : '';
                                            echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Export to Excel', array('controller' => 'excels_report', 'action' => 'download_member_collection_register', '?' => array('payment_date_to' => $bill_date_to, 'payment_date' => $bill_date, 'month' => $month, 'month_to' => $month_to, 'receipt_id' => $receiptID, 'receipt_id_to' => $receiptIdTo,'building_id' => $building_id,'wing_id' => $wing_id,'flat_no' => $flat_no,'flat_no_to' => $flat_no_to)), array('class' => 'btn btn-success btns', 'data-toggle' => 'tooltip', 'data-original-title' => 'Member Collection Register', 'escape' => false, 'target' => '_blank'));?>
                                    </div>
                                </div> 
                                <div class="col-md-3 right-section">
                                    <div class="row">   
                                        <div class="right-title">Select if required </div> 
                                        <div class="col-md-12">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Building &nbsp;</div>                                    
                                                <select name="data[MemberPayment][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#society_bill_report');">
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
                                                <select class="society-report-form-box-field-dropdown" id="society_bill_report" name="data[MemberPayment][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Unit No &nbsp;</div>                                   
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberPayment][flat_no]" value="<?php echo isset($postData['MemberPayment']['flat_no']) ? $postData['MemberPayment']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberPayment][flat_no_to]" value="<?php echo isset($postData['MemberPayment']['flat_no_to']) ? $postData['MemberPayment']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                    </div>    
                                </div> 
                            </form> 
                            <div class="clearfix"></div><br><br>
                            <div id="print_member_collection_register">
                                <div class="print-member-collection-register">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>
                                    <div class="report-bill">Member Collection Register</div>
                                    <div class="report-bill"></div>  
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                      <th>Sr No</th>
                                                        <th>Unit No</th>
                                                        <th>Name</th>
                                                        <th>Receipt Date</th>
                                                        <th>Chqe Date</th>
                                                        <th>Receipt No.</th>
                                                       
                                                        <th>Amount</th>
                                                        <th>Chq/Ref No</th>
                                                        <th>Society Bank</th>
                                                        <th>Memebr Bank</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                   
                                                    $totalAmt = 0; 
                                                    $srno=1;
                                                    
                                                    
                                                    if(isset($societyMemberPaymentCollectionData) && count($societyMemberPaymentCollectionData) > 0){
                                                        $idCount = 1;
                                                        foreach($societyMemberPaymentCollectionData as $collectionPaymentData){
                                                            if (isset($collectionPaymentData['MemberPayment']) && count($collectionPaymentData['MemberPayment']) > 0) {
                                                                foreach($collectionPaymentData['MemberPayment'] as $memberPaymentData){
                                                                   $totalAmt+=$memberPaymentData['amount_paid'];
                                                                ?>
                                                                <tr id="<?php echo $idCount; ?>">
                                                                    <td><?php echo $srno++ ?>
                                                                    <td class="text-center"><?php echo isset($collectionPaymentData['Member']['flat_no']) ? $collectionPaymentData['Member']['flat_no'] :'' ; ?></td>
                                                                   <td><?php echo isset($collectionPaymentData['Member']['member_prefix']) ? $collectionPaymentData['Member']['member_prefix'] :'' ; ?><?php echo isset($collectionPaymentData['Member']['member_name']) ? $collectionPaymentData['Member']['member_name'] :'' ; ?></td>
                                                                    
                                                                    <td><?php echo isset($memberPaymentData['payment_date']) ? $utilObj->getFormatDate($memberPaymentData['payment_date'],'d/m/Y') :'' ; ?></td>
                                                                    <td><?php echo isset($memberPaymentData['payment_date']) ? $utilObj->getFormatDate($memberPaymentData['entry_date'],'d/m/Y') :'' ; ?></td>
                                                                    <td class="text-center"><?php echo isset($memberPaymentData['receipt_id']) ? $memberPaymentData['receipt_id'] :'' ; ?></td>
                                                                   
                                                                    <td class="text-right"><?php echo isset($memberPaymentData['amount_paid']) ? $memberPaymentData['amount_paid'] :'' ; ?></td>
                                                                    <td class="text-center"><?php echo isset($memberPaymentData['cheque_reference_number']) ? $memberPaymentData['cheque_reference_number'] :'-' ; ?></td>
                                                                    <?php if(isset($memberPaymentData['payment_mode']) && $memberPaymentData['payment_mode'] == Configure::read('PaymentMode.Cash')) { ?>
                                                                     <td><?php echo isset($memberPaymentData['society_bank_id']) ? $societyCashBalanceHeadsLists[$memberPaymentData['society_bank_id']] : ''; ?></td>
                                                                     <?php } else { ?>
                                                                     <td><?php echo isset($memberPaymentData['society_bank_id']) ? $societyBankBalanceHeadsLists[$memberPaymentData['society_bank_id']] : ''; ?></td>
                                                                     <?php } ?>
                                                                    <td><?php echo isset($memberPaymentData['member_bank_id']) ? $memberPaymentData['member_bank_id'] :'-' ; ?></td>
                                                                     
                                                                     
                                                                </tr>
                                                                
                                                                <?php $idCount++; }
                                                            }
                                                        }
                                                    } ?>
                                                </tbody> 
                                                <tr style="font-weight: bolder; color: black; background: darkgray;">
                                                    <th colspan="6" style="text-align: right;">Total</th>
                                                    <th  style="text-align: right;"><?php  echo $totalAmt++; ?> </th>
                                                    <th colspan="3"></th>
                                                    
                                                    </tr>
                                            </table>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

