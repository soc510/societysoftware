<?php error_reporting(0); ?>
<?php
/* $db = $this->Session->read('Auth.dbConfig');
 if ($db != 'default'){
    $op = '01/04/2020';
} else {
    $mon = date('m');

    if ($mon > 4) {
        $y = date('Y');
    } else {
        $y = date('Y', strtotime('-1 year'));
    }
    $op = '01/04/'.$y;
}*/



$op = date('d/m/Y',strtotime($this->Session->read('Auth.year_start_date')));
?>
<style>
.select2-container--default {
    width: 74% !important;
}
</style>
<!-- Modal for flat/shop Details -->
<div class="modal fade" id="flat_shop_details" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close"><a href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>">&times;</a></button>
                <h6 class="modal-title modal-title-color">Flat / Shop Details</h6>
            </div>

            <ul class="pager1">
                <a class="previous previous-btn-list list-unstyled" id="society_member_previous_button" href="javascript:void(0);" onclick="societyMemberPrevious();" style="display:none;"><<</a>
                <a class="next next-btn-list list-unstyled" id="society_member_next_button" href="javascript:void(0);" onclick="societyMemberNext();">>></a>
            </ul>

            <div class="modal-body modal-body-bg">
                <div id="modal_member_notify_error"></div>                
                <form method="post" id="societyMemberDetailsForm" name="societyMemberDetailsForm" autocomplete="off" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">

           


                    <div class="modal-flat-shop-form-box">
                        <div class="row">                           
                            <div class="col-md-6">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Member Name<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-70" id="society_member_name" name="data[Member][member_name]" required="" value="<?php echo isset($societyMemberLists['Member']['member_name']) ? $societyMemberLists['Member']['member_name'] :0; ?>">
                                </div>
                            </div>                           
                            <div class="col-md-2">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Unit sr.<span class="required">*</span> &nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="hidden" id="society_member_id" name="data[Member][id]" value="<?php echo isset($societyMemberLists['Member']['id']) ? $societyMemberLists['Member']['id'] :0; ?>">
                                    <input type="hidden" id="society_member_previous" name="society_member_previous" value="<?php echo isset($societyMemberLists['Member']['id']) ? $societyMemberLists['Member']['id'] :0; ?>">
                                    <input type="hidden" id="society_member_next" name="society_member_next" value="<?php echo isset($societyMemberNextRecord['Member']['id']) ? $societyMemberNextRecord['Member']['id'] :0; ?>">
                                    <input type="text" class="modal-flat-shop-form-box-field-40 text-right readonly" id="member_serial_no" name="member_serial_no" value="<?php echo isset($societyMemberLists['Member']['id']) ? $societyMemberLists['Member']['id'] :0; ?>" readonly>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Unit No. &nbsp;&nbsp;</div>  
                                  <!-- select2-hidden-accessible <input type="text" class="modal-flat-shop-form-box-field-40 text-right" id="member_unit_no" name="member_unit_no" value="<?php echo isset($societyMemberLists['Member']['flat_no']) ? $societyMemberLists['Member']['flat_no'] :0; ?>"> -->


    <select class="modal-flat-shop-form-box-field-40 select2  text-right" id="member_unit_no" onchange="memberunitno();" name="member_unit_no" style="width: 75%;">                                  
                <?php 
                    if(isset($societyMemberFlatList) && count($societyMemberFlatList) > 0){
                        foreach($societyMemberFlatList as $flatNo => $FlatList){ ?>
                            <option value="<?php echo $FlatList['Member']['flat_no'];?>"><?php echo $FlatList['Member']['flat_no'];?></option>
                    <?php
                    }
                }
                ?>
                                    </select>    
                                </div>
                            </div> 
                        </div>    
                        <div class="row">
                            <div class="col-md-5">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Building Name<span class="required">*</span> &nbsp;&nbsp;&nbsp;</div>                                                                        
                                    <select name="data[Member][building_id]" id="member_building_id" onchange="getAllBuildingWings(this.value, '#member_wing_id');" class="modal-flat-shop-form-box-field-dropdown" required>
                                        <option value="">Select building</option>
                                            <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                        foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                            <?php if(isset($societyMemberLists['Member']['building_id']) && $societyMemberLists['Member']['building_id'] == $buildingID){?>
                                        <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                             <?php }else {?>
                                        <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                            <?php }?>
                                            <?php } }?>
                                    </select>  
                                </div>
                            </div>
                            <div class="col-md-4">
                        <div class="modal-flat-shop-form-field-box">
                           <div class="modal-flat-shop-form-box-label">Wing<span class=""></span>&nbsp;&nbsp;&nbsp;</div>
                           <select class="modal-flat-shop-form-box-field-40 select2 select2-hidden-accessible text-right" id="member_unit_no" onchange="memberunitno();" name="member_wing_no" style="width: 75%;">
                              <option value="">Select Wing</option>
                              <?php     
                                 if(isset($societyBuildingLists) && count($societyBuildingLists) > 0){
                                     foreach($societyBuildingLists as $wingNo => $wingList){ ?>
                              <option value="<?php echo $wingList['Member']['wing_id'];?>"><?php echo $wingList['Member']['wing_name'];?></option>
                              <?php
                                 }
                                 }
                                 ?>
                           </select>
                        </div>
                     </div>                     
                            <div class="col-md-3">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Floor No.<span class="required">*</span>&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-60 text-right" id="member_floor_no" name="data[Member][floor_no]" required="" value="<?php echo isset($societyMemberLists['Member']['floor_no']) ? $societyMemberLists['Member']['floor_no'] :0; ?>">
                                </div>
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-11">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Unit Type<span class="required">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                    <?php 
                                        if(isset($societyMemberLists['Member']['unit_type']) && $societyMemberLists['Member']['unit_type'] == 'C') { $societyMemberLists['Member']['unit_type'] = 'Commercial';} else if(isset($societyMemberLists['Member']['unit_type']) && $societyMemberLists['Member']['unit_type'] == 'R') { $societyMemberLists['Member']['unit_type'] = 'Residential';} else if(isset($societyMemberLists['Member']['unit_type']) && $societyMemberLists['Member']['unit_type'] == 'B') { echo 'Both';}
                                    ?>
                                    <input type="text" class="modal-flat-shop-form-box-field-85" id="member_unit_type" name="data[Member][unit_type]" value="<?php echo isset($societyMemberLists['Member']['unit_type']) ? $societyMemberLists['Member']['unit_type'] :0; ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="modal-ledger-form-field-box">

                                </div>
                            </div>  
                        </div>  

                        <div class="row">                            
                            <div class="col-md-2">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Area<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-50 text-right" id="member_flat_area" name="data[Member][area]" value="<?php echo isset($societyMemberLists['Member']['area']) ? $societyMemberLists['Member']['area'] :0; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Carpet<span class="required">*</span>&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-50 text-right" id="member_carpet" name="data[Member][carpet]" value="<?php echo isset($societyMemberLists['Member']['carpet']) ? $societyMemberLists['Member']['carpet'] :0; ?>">
                                </div>
                            </div>                            
                            <div class="col-md-3">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Commercial<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-40 text-right" id="member_commercial" name="data[Member][commercial]" value="<?php echo isset($societyMemberLists['Member']['commercial']) ? $societyMemberLists['Member']['commercial'] :0; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Residential<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-40 text-right" id="member_residential" name="data[Member][residential]" value="<?php echo isset($societyMemberLists['Member']['residential']) ? $societyMemberLists['Member']['residential'] :0; ?>">
                                </div>
                            </div>                            
                            <div class="col-md-2">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Terrace<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-40 text-right" id="member_terrace" name="data[Member][terrace]" value="<?php echo isset($societyMemberLists['Member']['terrace']) ? $societyMemberLists['Member']['terrace'] :0; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-3">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Op.Principal<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-40 text-right" id="member_principal" name="member_principal" value="<?php echo isset($societyMemberLists['Member']['op_principal']) ? $societyMemberLists['Member']['op_principal'] :0; ?>">				  
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Interest<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-50 text-right" id="member_interest" name="member_Interest" value="<?php echo isset($societyMemberLists['Member']['op_interest']) ? $societyMemberLists['Member']['op_interest'] :0; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Tax<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-50 text-right" id="member_tax" name="member_tax" value="<?php echo isset($societyMemberLists['Member']['op_tax']) ? $societyMemberLists['Member']['op_tax'] :0; ?>">
                                </div>
                            </div>                               
                        </div>

                        <div class="row">
                               <div class="col-md-4">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Sup Op.Principal<span class="required">*</span></div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-50 text-right" id="member_sup_principal" name="member_sup_principal" value="<?php echo isset($societyMemberLists['Member']['supplementary_principal']) ? $societyMemberLists['Member']['supplementary_principal'] :0; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Sup Op.Interest<span class="required">*</span></div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-30 text-right" id="member_sup_interest" name="member_sup_Interest" value="<?php echo isset($societyMemberLists['Member']['supplementary_interest']) ? $societyMemberLists['Member']['supplementary_interest'] :0; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Sup Op.Tax<span class="required">*</span></div>                                    
                                    <input type="text" class="modal-flat-shop-form-box-field-30 text-right" id="member_sup_tax" name="member_sup_tax" value="<?php echo isset($societyMemberLists['Member']['supplementary_tax']) ? $societyMemberLists['Member']['supplementary_tax'] :0; ?>">
                                </div>
                            </div>                             
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="modal-flat-shop-form-field-box">
                                    <div class="modal-flat-shop-form-box-label">Supplementary bill update</div>                                    
                                    <input type="checkbox" class="modal-flat-shop-form-box-field-20 text-right" id="checkbox_supplementary" name="member_Interest">
                                </div>
                            </div>                            
                            <div class="col-md-3">
                                <button id='update_op' type="button" data-toggle="tooltip" data-original-title="Update Op. Principal" onclick="" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Update Bill</span></button>
                            </div>
                        </div>

                    <div class="line-break"></div>
                    
                    <div class="row">
                        <div  class="tab-struct custom-tab-2">
                            <div class="account-report-form">
                            <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns pull-right" href="javascript:void(0);" onclick="printFlatShopReports('print_individual_member_ledger','print_header_section');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>  
                            </div>
                            <ul role="tablist" class="nav nav-tabs" id="myTabs_15">
                                <li class="tab-active-bg" role="presentation"><a aria-expanded="true" data-toggle="tab" role="tab" id="home_tab_15" href="#ledger">Occupant</a></li>
                                <li  role="presentation"><a aria-expanded="true" data-toggle="tab" role="tab" id="home_tab_16" href="#old_ledger" onclick="loadOldMemberLedger();">Old Member Bill</a></li>
                                <li role="presentation" class="tab-bg"><a data-toggle="tab" id="profile_tab_15" role="tab" href="#tariff" aria-expanded="false">Tariff</a></li>
                            </ul>
                            <div class="tab-content" id="myTabContent_15">
                                <div id="ledger" class="tab-pane fade active in tab-body-bg" role="tabpanel">                                     
                                    <div class="row">                                         
                                        <div class="col-md-12 padding-2">
                                            <div style="padding: 8px;">
                                                <div class="table-wrap">
                                                   <div id="print_individual_member_ledger">
                                                   <div class="print-individual-member-ledger">
                                                   <div class="societyinfo-section" id="print_header_section">
                                                        <h5 class="text-center"><?php echo isset($societyMemberLists['Society']['society_name']) ? $societyMemberLists['Society']['society_name'] : ''; ?></h5>
                                                        <div class="report-address-heading"><?php echo isset($societyMemberLists['Society']['registration_no']) ? $societyMemberLists['Society']['registration_no'] : ''; ?></div>
                                                        <div class="report-address-heading"><?php echo isset($societyMemberLists['Society']['address']) ? $societyMemberLists['Society']['address'] : ''; ?></div>                                                                                                           
                                                        <div class="report-bill">Member Ledger</div>
                                                        <div class="building-name"><span>Building Name : </span><span id="print_header_section_bulding_name"><?php echo isset($societyMemberLists['Building']['building_name']) ? $societyMemberLists['Building']['building_name'] :0; ?></span></div>
                                                        <div class="wing-name"><span>Wing : </span><span id="print_header_section_bulding_wing"><?php echo isset($societyMemberLists['Wing']['wing_name']) ? $societyMemberLists['Wing']['wing_name'] :0; ?></span></div><br>
                                                        <div class="member-name"><span>Member Name : </span><span id="print_header_section_member_name"><?php echo isset($societyMemberLists['Member']['member_name']) ? $societyMemberLists['Member']['member_name'] :0; ?></span></div>
                                                        <div class="flat-no"><span>Unit No : </span><span id="print_header_section_unit_no"><?php echo isset($societyMemberLists['Member']['flat_no']) ? $societyMemberLists['Member']['flat_no'] :0; ?></span></div>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table id="datable_11" class="table table-striped table-hover table-bordered padding-th-none padding-td-none" >
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Particular</th>
                                                                    <th class="text-right">Dr.Amount</th>
                                                                    <th class="text-right">Cr.Amount</th>
                                                                    <th class="text-right">Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="flat_details">
                                                                <?php
                                                                $openingCr = 0;
                                                                $openingDr = 0;
                                                                $op_principal = isset($societyMemberLists['Member']['op_principal']) ? $societyMemberLists['Member']['op_principal'] * 1 : 0.00;
                                                                $op_interest = isset($societyMemberLists['Member']['op_interest']) ? $societyMemberLists['Member']['op_interest'] * 1 : 0.00 ;
                                                                $op_tax = isset($societyMemberLists['Member']['op_tax']) ? $societyMemberLists['Member']['op_tax'] * 1 : 0.00 ;
                                                                $op_balance =  $op_principal + $op_interest + $op_tax ; 
                                                                $indBalance = $op_balance;

                                                                $paymentTotalAmount = 0;
                                                                $monthlyTotalAmount = 0;

                                                                if($op_balance < 0) {
                                                                    $openingDr = abs($op_balance);
                                                                    $posNeg = 'Cr';
                                                                    $paymentTotalAmount = $op_balance;                                                                    
                                                                } else {
                                                                    $openingCr = abs($op_balance);
                                                                    $posNeg = 'Dr'; 
                                                                    $monthlyTotalAmount = $op_balance;                                                                    
                                                                }

                                                                ?>
                                                                <tr>
                                                                    <td><a href="javascript:void(0);" onclick="loadSocietyBillsModel('#bill_modal');"><?php echo $op;?></a></td>
                                                                    <td class="opening-bal">Opening Balance</td>
                                                                    <td class="text-right total_debit"><?php echo number_format((float)$openingCr, 2, '.', ''); ?></td>
                                                                    <td class="text-right total_credit"><?php echo number_format((float)$openingDr, 2, '.', ''); ?></td>
                                                                    <td class="text-right rat total_balance"><?php echo number_format((float)abs($op_balance), 2, '.', '').' '.$posNeg;?></td>
                                                                </tr>
                                                                <?php 
                                                                    //TODO
                                                                    if(isset($societyMemberBillPaymentData) && count($societyMemberBillPaymentData) > 0){
                                                                    $idCount = 1;
                                                                    //$paymentTotalAmount = 0;
                                                                    //$monthlyTotalAmount = 0;
                                                               
                                                                    foreach($societyMemberBillPaymentData as $paymentData){
                                                                    if(!isset($paymentData['MemberBillSummary']['monthly_bill_amount'])) {
                                                                            $paymentData['MemberBillSummary']['monthly_bill_amount'] = 0;
                                                                        }

                                                                    $monthlyTotalAmount = $monthlyTotalAmount + $paymentData['MemberBillSummary']['monthly_bill_amount'];
                                                                       
                                                                    $indBalance += $paymentData['MemberBillSummary']['monthly_bill_amount']; 
                                                                    
                                                                    if($indBalance < 0) {
                                                                        $posNeg = 'Cr';
                                                                    } else { $posNeg = 'Dr';}
                                                                   ?>
                                                                   <?php if(isset($paymentData['MemberBillSummary']['bill_generated_date']) && $paymentData['MemberBillSummary']['bill_generated_date'] != '') { ?>
                                                                    <tr id="" class="payment_entries_<?php echo $societyMemberLists['Member']['id']?>">
                                                                        <td type="date"><?php echo isset($paymentData['MemberBillSummary']['bill_generated_date']) ? $paymentData['MemberBillSummary']['bill_generated_date']: '';  ?></td>
                                                                        <td>
                                                                            <a href="javascript:void(0);" onclick="loadSocietyBillsModelFromFlatShop('#bill_modal',<?php echo $paymentData['MemberBillSummary']['id']?>,<?php echo $paymentData['MemberBillSummary']['month'];?>);"><?php echo isset($paymentData['MemberBillSummary']['bill_no']) ? 'To Bill No. '.$paymentData['MemberBillSummary']['bill_no'].' For '.$paymentData['MemberBillSummary']['monthName']: ''; ?></a>
                                                                        </td>
                                                                        <td class="text-right"><?php echo isset($paymentData['MemberBillSummary']['monthly_bill_amount']) ? $paymentData['MemberBillSummary']['monthly_bill_amount']: '';  ?></td>
                                                                        <td class="text-right">0.00</td>
                                                                        <td class="text-right"><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;  ?></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                    <?php if(isset($paymentData['MemberPayment']) && !empty($paymentData['MemberPayment'])){
                                                                        foreach($paymentData['MemberPayment'] as $memberReceiptsData){
                                                                                $paymentTotalAmount = $paymentTotalAmount + $memberReceiptsData['amount_paid'];
                                                                                
                                                                                $indBalance -= $memberReceiptsData['amount_paid'];
                                                                                if($indBalance < 0) {
                                                                        $posNeg = 'Cr';
                                                                    } else { $posNeg = 'Dr';}    
                                                                    ?>
                                                                        <tr id="" class="payment_entries_<?php echo $societyMemberLists['Member']['id']?>">
                                                                            <td type="date"><?php echo isset($memberReceiptsData['payment_date']) ? $memberReceiptsData['payment_date']: '';  ?></td>
                                                                            <td><?php echo isset($memberReceiptsData['cheque_reference_number']) ? $memberReceiptsData['cheque_reference_number']:'Journal Voucher Receipt'; ?></td>
                                                                            <td class="text-right">0.00</td>
                                                                            <td class="text-right"><?php echo isset($memberReceiptsData['amount_paid']) ? $memberReceiptsData['amount_paid']: '';  ?></td>
                                                                            <td class="text-right"><?php echo number_format((float)abs($indBalance),2,'.', '').' '.$posNeg;  ?></td>
                                                                        </tr>
                                                                    <?php $idCount++;}}?>    
                                                                <?php $idCount++;}?>
                                                                <tfoot id="flat_shop_footer"> 
                                                                    <tr>
                                                                        <?php 
                                                                            $totalBal = $monthlyTotalAmount - abs($paymentTotalAmount);
                                                                           // echo $totalBal;
                                                                           
                                                                            if($totalBal < 0) {
                                                                                $posNeg = 'Cr';
                                                                            } else { $posNeg = 'Dr';}
                                                                        ?>
                                                                        <td colspan="2" class="text-right">Total</td>
                                                                        <td id="total_debit_flat_shop_sum" class="text-right"><?php  echo number_format((float)$monthlyTotalAmount, 2, '.', '');?></td>
                                                                        <td id="total_credit_flat_shop_sum" class="text-right"><?php  echo number_format((float)abs($paymentTotalAmount),2,'.', ''); ?></td>
                                                                        <td id="total_balance_flat_shop_sum" class="text-right" style="font-weight:bold;"><?php echo number_format((float)abs($totalBal),2,'.', '').' '; echo $posNeg;?></td>
                                                                        
                                                                    </tr>
                                                                </tfoot>
                                                                <?php
                                                                }?>
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









                                <div id="old_ledger" class="tab-pane fade tab-body-bg" role="tabpanel">
    <div class="row"><div class="col-md-12 padding-2"><div style="padding: 8px;">
        <div class="table-wrap">
            <div class="report-bill" style="text-align:center;font-weight:bold;">Old Member Ledger</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered padding-th-none padding-td-none">
                    <thead><tr><th>Date</th><th>Particular</th><th class="text-right">Dr.Amount</th><th class="text-right">Cr.Amount</th><th class="text-right">Balance</th></tr></thead>
                    <tbody id="old_flat_details"><tr><td colspan="5" class="text-center">Loading...</td></tr></tbody>
                </table>
            </div>
        </div>
    </div></div></div>
</div>
<div id="tariff" class="tab-pane fade tab-body-bg" role="tabpanel">
                                    <p>Tariff Details Here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>


    function memberunitno(){
     
        var flat_no = $("#member_unit_no").val();
        var buildingId = $("#member_building_id").val();
        var wingId = $("#member_wing_id").val();

        var postString = 'flat_no=' + flat_no+'&building_id=' + buildingId+'&wing_id=' + wingId;
        $.ajax({
            type: "POST",
            url: webrootUrl + 'societys_ajax/getMemberDetailsByFlatNo',
            data: postString,
            dataType: 'json',
            success: function (jsonData) {
                hideLoader();
                if (jsonData.id != 0) {
                    getSocietyMemberDetails(jsonData.id);
                } else {
                    alert('This flat no does not exist!!!!');
                    return false;
                }
            }
        });
    }



$("#update_op").click(function(event) {
     showLoader();
            let updateSup = $('#checkbox_supplementary').is(':checked') ? 'Y' : 'N';
        	let memberId = $("#member_serial_no").val();
            let op_principal = $('#member_principal').val();
            let op_interest = $('#member_interest').val();
            let op_tax = $('#member_tax').val();
            let sup_op_principal = $('#member_sup_principal').val();
            let sup_op_interest = $('#member_sup_interest').val();
            let sup_op_tax = $('#member_sup_tax').val();
            

            let postString = 'memberId=' + memberId + '&op_principal=' +op_principal+ '&op_interest=' +op_interest+ '&op_tax=' +op_tax+'&sup_op_principal=' +sup_op_principal+ '&sup_op_interest=' +sup_op_interest+ '&sup_op_tax=' +sup_op_tax + '&updateSuplementary='+updateSup;
            $.ajax({
                type: "POST",
                url: webrootUrl + 'society_bills/fetch_bills_and_payments',
                data: postString,
                dataType: 'text',
                success: function (responseText) {
                    hideLoader();
                   location.reload(true);
                }
            });
});
</script>

<script type="text/javascript">
// Old Member Bill tab: fetch the previous owner's ledger (same member id, one
// transfer generation back) and render it, mirroring the Occupant renderer.
function loadOldMemberLedger() {
    if (!window.jQuery) { return; }
    var memberId = jQuery('#society_member_id').val();
    if (!memberId) { return; }
    jQuery('#old_flat_details').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
    jQuery.ajax({
        type: 'POST',
        url: webrootUrl + 'societys_ajax/getSocietyMemberDetails',
        data: 'societyMemberId=' + memberId + '&oldMember=1',
        dataType: 'json',
        success: function (resp) { renderOldMemberLedger(resp); },
        error: function () { jQuery('#old_flat_details').html('<tr><td colspan="5" class="text-center">Could not load old ledger.</td></tr>'); }
    });
}
function renderOldMemberLedger(resp) {
    var $ = jQuery, tb = $('#old_flat_details');
    tb.empty();
    if (!resp || !resp.Member) { tb.append('<tr><td colspan="5" class="text-center">No previous owner for this unit.</td></tr>'); return; }
    function np(v){ return (typeof checkNegativePositiveValue === 'function') ? checkNegativePositiveValue(v) : (v < 0 ? 'Cr' : 'Dr'); }
    function f(x){ x = parseFloat(x); return isNaN(x) ? 0 : x; }
    var opBal = f(resp.Member.op_principal) + f(resp.Member.op_interest) + f(resp.Member.op_tax)
              + f(resp.Member.supplementary_principal) + f(resp.Member.supplementary_penality)
              + f(resp.Member.supplementary_interest) + f(resp.Member.supplementary_tax);
    var openingCr = 0, openingDr = 0;
    if (opBal < 0) { openingDr = Math.abs(opBal); } else { openingCr = Math.abs(opBal); }
    var indBalance = opBal;
    tb.append('<tr><td>' + (resp.openingdate || '') + '</td><td class="opening-bal">Opening Balance</td><td class="text-right old_dr">' + openingCr.toFixed(2) + '</td><td class="text-right old_cr">' + openingDr.toFixed(2) + '</td><td class="text-right">' + Math.abs(opBal).toFixed(2) + ' ' + np(opBal) + '</td></tr>');
    if (resp.memberBillPayment && resp.memberBillPayment.length > 0) {
        $.each(resp.memberBillPayment, function (i, d) {
            indBalance = f(indBalance) + f(d.debit) - f(d.credit);
            tb.append('<tr><td>' + (d.formattedDate || '') + '</td><td>' + (d.particular || '') + '</td><td class="text-right old_dr">' + d.debit + '</td><td class="text-right old_cr">' + d.credit + '</td><td class="text-right">' + Math.abs(indBalance).toFixed(2) + ' ' + np(indBalance) + '</td></tr>');
        });
    }
    var td = 0, tc = 0;
    tb.find('.old_dr').each(function () { td += f($(this).text()); });
    tb.find('.old_cr').each(function () { tc += f($(this).text()); });
    var totBal = td - tc;
    tb.append('<tr style="font-weight:bold;"><td colspan="2" class="text-right">Total</td><td class="text-right">' + td.toFixed(2) + '</td><td class="text-right">' + tc.toFixed(2) + '</td><td class="text-right">' + Math.abs(totBal).toFixed(2) + ' ' + np(totBal) + '</td></tr>');
}
</script>
