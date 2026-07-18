<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left"><h6 class="panel-title txt-dark">Member Passbook</h6></div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="col-md-12 left-section"> 
                                <form class="" method="post" id="memberPassbook" name="memberPassbook" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                    <div class="row">
                                        <div class="col-md-2 nopadding">
                                            <div class="society-report-form-field-box">    
                                                <div class="society-report-form-box-label">As On &nbsp;</div>                                                                     
                                                <input type="date" class="society-report-form-box-field-date-70" id="" name="data[MemberPayment][payment_date]" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">
                                            <div class="society-report-form-field-box"> 
                                                <div class="society-report-form-box-label">Bill Type &nbsp;</div>                                                                     
                                                <select class="society-report-form-box-field-dropdown" id="" name="data[MemberPayment][bill_type]" required="">
                                                    <option value="">Select Bill Type</option>  
                                                    <option value="Both" <?php if(isset($postData['MemberPayment']['bill_type']) && $postData['MemberPayment']['bill_type'] == 'Both') { echo 'selected';}?>>Both</option>
                                                    <option value="Regular" <?php if(isset($postData['MemberPayment']['bill_type']) && $postData['MemberPayment']['bill_type'] == 'Regular') { echo 'selected';}?>>Regular</option>
                                                    <option value="Supplementary" <?php if(isset($postData['MemberPayment']['bill_type']) && $postData['MemberPayment']['bill_type'] == 'Supplementary') { echo 'selected';}?>>Supplementary</option>                                                  
                                                </select> 
                                            </div>
                                        </div>  
                                        <div class="col-md-3 nopadding">
                                            <div class="society-report-form-field-box"> 
                                                <div class="society-report-form-box-label">Building &nbsp;</div>                                                                     
                                                <select name="data[MemberPayment][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#member_passbook');">
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
                                        <div class="col-md-2 nopadding">
                                            <div class="society-report-form-field-box"> 
                                                <div class="society-report-form-box-label">Wing &nbsp;</div>                                                                     
                                                <select class="society-report-form-box-field-dropdown" id="member_passbook" name="data[MemberPayment][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div> 
                                        </div> 
                                        <div class="col-md-2 nopadding">
                                            <div class="society-report-form-field-box"> 
                                                <div class="society-report-form-box-label">Unit No&nbsp;</div>                                                                     
                                                <input type="text" class="society-report-form-box-field-60" id="" name="data[MemberPayment][flat_no]" value="<?php echo isset($postData['MemberPayment']['flat_no']) ? $postData['MemberPayment']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>                                                             
                                    </div>  
                                    <div class="clearfix"></div>  
                                    <div class="row">      
                                        <div class="col-md-2 nopadding">
                                            <div class="society-report-form-field-box"> 
                                                <div class="society-report-form-box-label">Dues Rs. &nbsp;</div>                                                                     
                                                <input type="text" class="society-report-form-box-field-60" id="" name="data[MemberPayment][due_amt]" value="<?php echo isset($postData['MemberPayment']['due_amt']) ? $postData['MemberPayment']['due_amt'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-2 nopadding">
                                            <div class="society-report-form-field-box"> 
                                                <div class="society-report-form-box-label">Adv. Rs. &nbsp;</div>                                                                     
                                                <input type="text" class="society-report-form-box-field-70" id="" name="data[MemberPayment][adv_amt]" value="<?php echo isset($postData['MemberPayment']['adv_amt']) ? $postData['MemberPayment']['adv_amt'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-8">                                
                                            <div class="form-group society-report-form">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Show</span></button>  
                                                <a type="button" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_member_passbook');"><i class="fa fa-print"></i><span class="btn-text"> Print Friendly</span></a>                                                                               
                                                <a type="button" class="btn btn-success btn-anim btns" href="javascript:void(0);"><i class="fa fa-file-excel-o"></i><span class="btn-text"> Export to PDF</span></a>    
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Summary</span></a>                                                     
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                              
                                            </div>
                                        </div>    
                                    </div>
                                </form> 
                            </div>                            
                            <div class="clearfix"></div> <br><br> 
                            <div id="print_member_passbook">
                            <div class="print-member-passbook">
                            <div class="row1">
                                <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                <div class="member-passbook-heading">Member Passbook AS on Date 31/03/2018</div>

                                <div class="table-wrap1">
                                    <table id="" class="table table-bordered padding-td-none padding-th-none">
                                        <thead>
                                            <tr>
                                                <th>Unit No</th>
                                                <th>Reference Date</th>
                                                <th>Bill No</th>
                                                <th>Bill No</th>
                                                <th>Principal</th>
                                                <th>Interest</th>
                                                <th>Tax</th>
                                                <th>Total</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>  
                                        <?php 
                                            if(isset($societyMemberPassbook) && count($societyMemberPassbook) > 0){
                                            $idCount = 1;                                                 
                                            foreach($societyMemberPassbook as $memberPassbookData){
                                            $AmountTotal = isset($memberPassbookData['MemberBillSummary']['amount']) ? $memberPassbookData['MemberBillSummary']['amount']: 0;
                                        ?>
                                            <tr id="">
                                                <td colspan="2" style="border:none !important;"><span class="font-weight-600">Unit No :- </span> <?php echo isset($memberPassbookData['MemberBillSummary']['flat_no']) ? $memberPassbookData['MemberBillSummary']['flat_no'] : ''; ?></td>                                                        
                                                <td colspan="7" style="border:none !important;"><span class="font-weight-600">Member Name :- </span><?php echo isset($memberPassbookData['Member']['member_name']) ? $memberPassbookData['Member']['member_name'] :'' ; ?></td>
                                            </tr>
                                            <tr id="">
                                                <td style="width:5%;"><?php echo isset($memberPassbookData['MemberBillSummary']['flat_no']) ? $memberPassbookData['MemberBillSummary']['flat_no']: '';  ?></td>
                                                <td style="width:8%;"><?php echo isset($memberPassbookData['MemberBillSummary']['bill_generated_date']) ? $memberPassbookData['MemberBillSummary']['bill_generated_date']: '';  ?></td>
                                                <td><?php echo isset($memberPassbookData['MemberBillSummary']['bill_no']) ? $memberPassbookData['MemberBillSummary']['bill_no']: '';  ?></td>
                                                <td><?php echo isset($memberPassbookData['MemberBillSummary']['bill_no']) ? $memberPassbookData['MemberBillSummary']['bill_no']: '';  ?></td>                                                 
                                                <td class="text-right"><?php echo isset($memberPassbookData['MemberBillSummary']['principal_balance']) ? $memberPassbookData['MemberBillSummary']['principal_balance']: '';  ?></td>
                                                <td class="text-right"><?php echo isset($memberPassbookData['MemberBillSummary']['interest_balance']) ? $memberPassbookData['MemberBillSummary']['interest_balance']: '';  ?></td>
                                                <td class="text-right"><?php echo isset($memberPassbookData['MemberBillSummary']['tax_balance']) ? $memberPassbookData['MemberBillSummary']['tax_balance']: '';  ?></td>
                                                <td class="text-right"><?php echo isset($memberPassbookData['MemberBillSummary']['amount_payable']) ? $memberPassbookData['MemberBillSummary']['amount_payable']: '';  ?></td>
                                                <td class="text-right"><?php echo isset($memberPassbookData['MemberBillSummary']['balance_amount']) ? $memberPassbookData['MemberBillSummary']['balance_amount']: '';  ?></td>
                                            </tr>
                                    <?php $idCount++;}}?>
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