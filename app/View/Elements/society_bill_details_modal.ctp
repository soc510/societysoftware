<?php
$billingFrequenciesArray = array(
	1=>array("4" => "April","5" => "May", "6" => "June", "7" => "July", "8" => "August","9" => "September", "10" => "October", "11" => "November", "12" => "December","1" => "January", "2" => "February", "3" => "March"),
	2=>array("4" => "Apr-May","6" => "Jun-Jul", "8" => "Aug-Sep", "10" => "Oct-Nov", "12" => "Dec-Jan","2" => "Feb-Mar"),
	3=>array("4" => "Apr-May-Jun","7" => "Jul-Aug-Sep", "10" => "Oct-Nov-Dec", "1" => "Jan-Feb-Mar"),
	4=>array(),
	5=>array("4" => "Apr-Sep","10" => "Oct-Mar"),
	6=>array("4" => "April-March"),	
  );
$MonthArray = array("1" => "January", "2" => "February", "3" => "March", "4" => "April","5" => "May", "6" => "June", "7" => "July", "8" => "August","9" => "September", "10" => "October", "11" => "November", "12" => "December");
?>
<!-- Modal for Bill  -->
<div class="modal fade" id="bill_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                
                <button type="button" class="close"><a id="closelink" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>">&times;</a></button>
                <h6 class="modal-title modal-title-color">Member Bill</h6>
            </div>
            <ul class="pager1">
                <a class="previous previous-btn-list list-unstyled" id="society_member_bill_previous_button" href="javascript:void(0);" onclick="societyMemberBillPrevious();" style="display:none;"><<</a>
                <a class="next next-btn-list list-unstyled" id="society_member_bill_next_button" href="javascript:void(0);" onclick="societyMemberBillNext();">>></a>
            </ul>
            <div class="modal-body modal-body-bg">    
                <div class="row hidden" id="printviewid">
                    <a type="button" data-toggle="tooltip" data-original-title="Print View" class="btn btn-success btn-anim btns pull-right" href="javascript:void(0);" id="printbillview" onclick=""><i class="fa fa-print"></i><span class="btn-text">Print View</span></a>  
                </div>
                <form id="society_member_bill_summary" name="society_member_bill_summary" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                    <div class="row">
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <input type="hidden" id="society_member_summary_id" name="society_member_summary_id" value="">
                                <input type="hidden" id="society_member_bill_previous" name="society_member_bill_previous" value="">
                                <input type="hidden" id="society_member_bill_next" name="society_member_bill_next" value="">
                                <input type="hidden" id="society_member_bill_previous_month" name="society_member_bill_previous_month" value="">
                                <input type="hidden" id="society_member_bill_next_month" name="society_member_bill_next_month" value="">

                                <label for="" class="control-label">Bill For<span class="required">*</span></label>                               
                                <select name="data[MemberBillSummary][month]" id="member_bill_summary_month" class="form-control" required="" onchange="getAllMembersBillSummaryDetails('data[MemberBillSummary][month]=' + this.value);">
                                    <option value="">Select Month</option>
                                    <?php if(isset($societyParameters['SocietyParameter']['billing_frequency_id']) && count($billingFrequenciesArray[$societyParameters['SocietyParameter']['billing_frequency_id']]) > 0) {
                                    foreach($billingFrequenciesArray[$societyParameters['SocietyParameter']['billing_frequency_id']] as $MonthID => $MonthName){?>
                                    <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                    <?php }?>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Bill No.<span class="required">*</span></label>
                                <input type="text" id="member_bill_summary_bill_no" name="data[MemberBillSummary][bill_no]" onblur="getAllMembersBillSummaryDetails('data[MemberBillSummary][bill_no]=' + this.value);" class="form-control input-height-30" value="">
                            </div>
                        </div>
                        <!--<div class="col-md-1 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Sr.</label>                               
                                <input type="text" id="member_bill_summary_bill_no" name="" class="form-control input-height-30" value="">
                            </div>
                        </div>-->
                        <div class="col-md-2 padding-2">
                            <div class="form-group">
                                <label for="" class="control-label">Manual Bill No.</label>                               
                                <input type="text" id="member_bill_summary_manual_bill_no" name="" class="form-control input-height-30" value="">
                            </div>
                        </div>                       
                        <div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Bill Date</label> 
                                <input type="date" id="member_bill_summary_bill_generated_date" name="data[MemberBillSummary][bill_generated_date]" onblur="getAllMembersBillSummaryDetails('data[MemberBillSummary][bill_generated_date]=' + this.value);" class="form-control input-height-30" value="">
                            </div>
                        </div>
                        <div class="col-md-3 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Due Date</label> 
                                <input type="date" id="member_bill_summary_bill_due_date" name="data[MemberBillSummary][bill_due_date]   " class="form-control input-height-30" value="">
                            </div>
                        </div>
                    </div> 
                    <div class="row"> 
                        <div class="col-md-3 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Rebate Date</label> 
                                <input type="date" id="member_bill_summary_manual_bill_no" name="" class="form-control input-height-30" value="">
                            </div>
                        </div>
                        <div class="col-md-2 padding-2">
                            <div class="form-group">
                                <label for="" class="control-label">Member</label> 
                                <select class="form-control" id="member_bill_summary_member_id" name="data[MemberBillSummary][member_id]">
                                    <option value="">Select  Members </option>
                                        <?php
                                        if (isset($societyMemberListData) && count($societyMemberListData) > 0) :
                                            $s = 1;
                                            foreach ($societyMemberListData as $societyMemberId => $societyMemberName) { ?>
                                    <option value="<?php echo $societyMemberId; ?>"><?php echo "$societyMemberName"; ?></option>
                                            <?php $s++;
                                            } 
                                        endif;
                                        ?>  
                                </select>
                            </div>
                        </div>                       
                        <div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Building<span class="required">*</span></label>
                                <select name="data[MemberBillSummary][building_id]" class="form-control" id="member_bill_summary_building_id" onchange="getAllBuildingWings(this.value, '#member_bill_summary_bill_wing_id');">
                                    <option value="">Select building</option>
                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                    <?php if(isset($this->request->data['Member']['building_id']) && $this->request->data['Member']['building_id'] == $buildingID){?>
                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                    <?php }else {?>
                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                    <?php }?>
                                    <?php } }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Wing</label>                               
                                <select name="data[MemberBillSummary][wing_id]" id="member_bill_summary_bill_wing_id" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Unit No.</label> 
                                <input type="text" id="member_bill_summary_bill_flat_no" name="" class="form-control input-height-30" value="">
                            </div>
                        </div>
                        <div class="col-md-1 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Area</label> 
                                <input type="text" id="member_bill_summary_area" name="" class="form-control input-height-30" value="">
                            </div>
                        </div>
                        <div class="col-md-1 padding-2">
                            <div class="form-group">
                                <label for="" class="control-label">Rate</label> 
                                <input type="text" id="member_bill_summary" name="" class="form-control input-height-30" value="" placeholder="Factor">
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                    </div>
                    <div class="row">
                        <div  class="tab-struct custom-tab-2">
                            <ul role="tablist" class="nav nav-tabs" id="myTabs_15">
                                <li class="tab-active-bg" role="presentation"><a aria-expanded="true"  data-toggle="tab" role="tab" id="home_tab_15" href="#charges">Charges</a></li>
                                <li role="presentation" class="tab-bg"><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#interest" aria-expanded="false">Interest</a></li>
                                <li role="presentation" class="tab-bg"><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#supplementary-bill" aria-expanded="false">Supplementary Bill</a></li>
                                <!-- For sub menu as dropdown -->
                                <!--<li class="dropdown" role="presentation">
                                    <a data-toggle="dropdown" class="dropdown-toggle" id="myTabDrop_15" href="#" aria-expanded="false">Dropdown <span class="caret"></span></a>
                                    <ul id="myTabDrop_15_contents"  class="dropdown-menu">
                                        <li class=""><a data-toggle="tab" id="dropdown_29_tab" role="tab" href="#dropdown_29" aria-expanded="true">@fat</a></li>
                                        <li class=""><a data-toggle="tab" id="dropdown_30_tab" role="tab" href="#dropdown_30" aria-expanded="false">@mdo</a></li>
                                    </ul>
                                </li>-->
                            </ul>
                            <div class="tab-content" id="myTabContent_15">
                                <div id="charges" class="tab-pane fade active in tab-body-bg" role="tabpanel">                                            
                                    <div style="">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <table id="" class="table table-striped padding-th-none-zero padding-td-none-zero table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr.</th>
                                                            <th widht="40%">Particulars</th>
                                                            <th>Amount</th>
                                                            <!--<th><span style="font:normal 12px agency,arial;color:blue;cursor:pointer;" onclick="addTarrifMoreRows();">Add More</span></th>-->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="bill_member_tariff_details"> 

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div  id="interest" class="tab-pane fade tab-body-bg" role="tabpanel">
                                    <p></p>
                                </div>
                                <div  id="supplementary-bill" class="tab-pane fade tab-body-bg" role="tabpanel">
                                    <p></p>
                                </div>

                                <!-- dropdown data-->
                                <!--<div  id="dropdown_29" class="tab-pane fade " role="tabpanel">
                                    <p>Food truck fixie locavore,</p>
                                </div>
                                <div  id="dropdown_30" class="tab-pane fade" role="tabpanel">
                                    <p>Food truck fixie locavore,</p>
                                </div>-->
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div  class="tab-struct custom-tab-2">
                                <ul role="tablist" class="nav nav-tabs" id="myTabs_15">
                                    <li class="tab-active-bg" role="presentation"><a aria-expanded="true"  data-toggle="tab" role="tab" id="home_tab_15" href="#receipts">Receipts</a></li>
                                    <li role="presentation" class="tab-bg"><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#remarks" aria-expanded="false">Remark</a></li>
                                    <li role="presentation" class="tab-bg"><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#servicetax" aria-expanded="false">Service Tax</a></li>
                                </ul>
                                <div class="tab-content" id="myTabContent_15">
                                    <div  id="receipts" class="tab-pane fade active in tab-body-bg" role="tabpanel"> 
                                        <div class="row">
                                            <div class="col-md-6 padding-1">
                                                <div class="form-group">
                                                    <label for="" class="control-label input-height-30">Principle Amount</label> 
                                                    <input type="text" id="member_bill_summary_bill_monthly_principal_amount" name="" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6 padding-0">
                                                <div class="form-group">
                                                    <label for="" class="control-label input-height-30">Interest Free</label> 
                                                    <input type="text" id="member_bill_summary_bill_interest_on_due_amount" name="" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <p> &nbsp;&nbsp; Settlement status of Current Bill</p>   
                                            <div class="col-md-12 padding-2">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped padding-th-none padding-td-none table-hover">
                                                        <thead class="table-warning ">
                                                            <tr>
                                                                <th>Title</th>
                                                                <th>Receipt</th>
                                                                <th>Adjustment</th>
                                                                <th>Balance</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Principal</td>
                                                                <td id="bill_principle_paid">0.00</td>
                                                                <td id="bill_principal_adjusted">0.00</td>
                                                                <td id="bill_principal_bal">-1526.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Interest</td>
                                                                <td id="bill_interest_paid">390.00</td>
                                                                <td id="bill_interest_adjusted">0.00</td>
                                                                <td id="bill_interest_balance">0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tax</td>
                                                                <td id="bill_tax_paid">0.00</td>
                                                                <td id="bill_tax_adjusted">0.00</td>
                                                                <td id="bill_tax_balance">0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:none !important;"></td>
                                                                <td style="border:none !important;"></td>
                                                                <td>Total Rs.</td>
                                                                <td id="bill_balance_amount">0.00</td>
                                                            </tr>
                                                        </tbody>                                                
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div  id="remarks" class="tab-pane fade tab-body-bg" role="tabpanel">
                                        <div id="remarkmsg"></div>
                                        <p><textarea class="form-control" cols="20" id="bill_remarks" name="bill_remarks"></textarea><br><input type="hidden" name="bill_id" id="bill_id" /><button id="saveRemarkSum" type="button" class="btn btn-success btn-anim btn-sm" onclick="saveRemark();">Save</button></p>
                                    </div>
                                    <div  id="servicetax" class="tab-pane fade tab-body-bg" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped padding-th-none padding-td-none table-hover">
                                                <thead class="table-warning ">
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Tax</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>IGST</td>
                                                        <td id="bill_igst_total">0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>CGST</td>
                                                        <td id="bill_cgst_total">390.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>SGST</td>
                                                        <td id="bill_sgst_total">0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Rs.</td>
                                                        <td id="bill_tax_total">0.00</td>
                                                    </tr>
                                                </tbody>                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Total</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_monthly_principal_amount" name="" placeholder="" readonly="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Interest</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_interest_on_due_amount" name="" placeholder="" value="0.00">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Less Rebate</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_discount" name="" placeholder="" value="0.00">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Other Adjustments(Principal)</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_principal_adjusted" name="" placeholder="" value="0.00">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Other Adjustments(Interest)</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_interest_adjusted" name="" placeholder="" value="0.00">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="" style="color:blue;">Bill Amount(Rs)</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_monthly_bill_amount" name="" placeholder="" readonly="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Principal Arrears</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_principal_arrears" name="" placeholder="" readonly="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Interest Arrears</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_interest_balance" name="" placeholder="" readonly="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">S.Tax Arrears</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_op_tax_arrears" value="0.00" readonly="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="">Total Arrears</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_op_total_errears" name="" placeholder="" readonly="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="control-label mb-10 col-sm-4" for="" style="color:blue;"><span id="amtPayableExcess">Amount Payable</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control text-right" id="member_bill_summary_bill_amount_payable" name="" placeholder="" readonly="">
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <center>
                        <button id="updateMemSum" type="button" class="btn btn-success btn-anim btn-sm" onclick="updateMemberBillSummaryById();">Update</button>
                         <button id="InterestMemSum" type="button" class="btn btn-success btn-anim btn-sm" onclick="updateMemberBillSummaryById();">Interest menualy</button>
                        <button id="delMemSum" type="button" class="btn btn-danger btn-anim btn-sm hide" onclick="deleteMemberBillSummaryById();" ifpayment="0">Delete</button>
                        <a id="cancelMemSum" type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-anim btns btn-sm" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                        <button id="updateManualSum" type="button" class="btn btn-warning btn-anim btn-sm" onclick="updateManualInterest();">Manual Update Interest</button>
                        <div style="margin-top:10px;">
                            <span id="billLockedMsg" class="label label-warning" style="display:none;font-size:13px;padding:8px 15px;"><i class="fa fa-lock"></i> This bill is locked. Only last bill is editable. Enable "Allow Edit All Bills" in Society Parameter.</span>
                        </div>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ajaxComplete(function(event, xhr, settings) {
    if (settings.url && settings.url.indexOf('getAllMembersBillSummaryDetails') !== -1) {
        try {
            var resp = JSON.parse(xhr.responseText);
            if (resp.bill_locked == 1) {
                $('#billLockedMsg').show();
                $('#updateMemSum').prop('disabled', true).addClass('disabled');
                $('#InterestMemSum').prop('disabled', true).addClass('disabled');
                $('#delMemSum').addClass('hide');
                $('#updateManualSum').prop('disabled', true).addClass('disabled');
            } else {
                $('#billLockedMsg').hide();
                $('#updateMemSum').prop('disabled', false).removeClass('disabled');
                $('#InterestMemSum').prop('disabled', false).removeClass('disabled');
                $('#updateManualSum').prop('disabled', false).removeClass('disabled');
            }
        } catch(e) {}
    }
});
</script>