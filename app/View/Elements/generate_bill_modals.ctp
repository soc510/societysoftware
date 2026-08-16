<?php
	//1-Monthly, 2-BiMonthly, 3-Quarterly, 4-Quadruple,5-Half Yearly,6-Yearly
  $billingFrequenciesArray = array(
	1=>array("4" => "April","5" => "May", "6" => "June", "7" => "July", "8" => "August","9" => "September", "10" => "October", "11" => "November", "12" => "December","1" => "January", "2" => "February", "3" => "March"),
	2=>array("4" => "Apr-May","6" => "Jun-Jul", "8" => "Aug-Sep", "10" => "Oct-Nov", "12" => "Dec-Jan","2" => "Feb-Mar"),
	3=>array("4" => "Apr-May-Jun","7" => "Jul-Aug-Sep", "10" => "Oct-Nov-Dec", "1" => "Jan-Feb-Mar"),
	4=>array(),
	5=>array("4" => "Apr-Sep","10" => "Oct-Mar"),
	6=>array("4" => "April-March"),	
  );  
 ?>
<div class="modal fade" id="Member_Generate_Bill" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title modal-title-color">Generate Bill</h6>
            </div>
            <div class="modal-body modal-body-bg">
                <div id="show_bill_notify_error"></div>
                <form  id="Society_Member_Generate_Bill" name="Society_Member_Generate_Bill" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                    <div class="row">
                        <div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Building<span class="required">*</span></label>								
                                <select name="data[MemberBillGenerate][building_id]" class="form-control" onchange="getAllBuildingWings(this.value,'#bill_wing_id');">
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
                        <div class="col-md-3 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Wing Name</label>                               
                                <select name="data[MemberBillGenerate][wing_id]" id="bill_wing_id" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 padding-2">
                            <div class="form-group">
                                <label for="" class="control-label">Bill For<span class="required">*</span></label>                               
                                <select name="data[MemberBillGenerate][month]" id="bill_wing_id" class="form-control" onchange="getBillDateBillDueDate(this.value);" required="">
                                    <option value="">Select Month</option>
                                    <?php if(isset($societyParameters['SocietyParameter']['billing_frequency_id']) && count($billingFrequenciesArray[$societyParameters['SocietyParameter']['billing_frequency_id']]) > 0) {
                                    foreach($billingFrequenciesArray[$societyParameters['SocietyParameter']['billing_frequency_id']] as $MonthID => $MonthName){?>
                                    <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                    <?php }?>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 padding-2">
                            <div class="form-group">
<?php  if($financialYear == '2017-18') { $financialYear = '2018-19';} else if($financialYear == '2018-19') { $financialYear = '2019-2020';}?>

                                <label for="" class="control-label">Year<span class="required">*</span></label>
                                <select name="data[MemberBillGenerate][bill_year]" id="financialBillYear" class="form-control">
                                    <option selected=""><?php echo isset($financialYear) ? $financialYear : '2019-2020';?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Bill Date<span class="required">*</span></label>
                                <input type="date" class="form-control" id="financial_bill_date" name="data[MemberBillGenerate][bill_generated_date]" required="" value="<?php echo date('Y-m-d');?>">
                            </div>
                        </div>                        
                        <div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Due Date <span class="required">*</span></label>
                                <input type="date" class="form-control" id="financial_bill_due_date" name="data[MemberBillGenerate][bill_due_date]" required="">
                            </div>
                        </div>
                        <div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Starting Bill# </label>
                                <input type="test" class="form-control" id="financial_bill_starting_no" name="data[MemberBillGenerate][bill_starting_no]" >
                            </div>
                        </div>
                        <div class="col-md-3 padding-0">
                            <div class="form-group mr-top-25">
                                <input type="checkbox" name="data[MemberBillGenerate][bill_type]" id="supplementary_bill" value="suppl_bill">
                                <label for="" class="control-label">Supplementary Bill</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="member_supplementary_bill">
                            
                    </div>  
                    <div class="row">
                        <div class="col-md-6 padding-0" style="padding-top:23px;">
<!--<lable style="color: red;">Not Working Till 5-DEC-2019</lable>-->
                            <button type="button" class="btn btn-success btn-anim btn-sm" id="regular_bill_generate" href="javascript:void(0);" onclick="checkMemberBillGeneratedExistsByMonth('reg');">Generate Regular Bill</button>
                            <button type="button" style="display:none;" class="btn btn-success btn-anim btn-sm" id="supplementary_bill_generate" href="javascript:void(0);" onclick="checkMemberSupplementaryBillByMonth('sup');">Generate Supplementary Bill</button>
                            <a type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-anim btn-sm btns" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="bill_exist_warning" role="dialog" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Members Bill already exists in the system with the same month . Do you want to override this bill?</p>
                <!--<p class="text-warning"><small>If you don't save, your changes will be lost.</small></p>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="billGenerateStatus(0);">Close</button>
                <button type="button" class="btn btn-primary" onclick="billGenerateStatus(1);">Override Bill</button>
            </div>
        </div>
    </div>
</div>
