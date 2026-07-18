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
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bill</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                                            <form  id="Society_Member_Generate_Print" name="Society_Member_Generate_Print" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                    <div class="row">
                        <div class="col-md-3 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Building<span class="required">*</span></label>
                                <select name="building_id" class="form-control" onchange="getAllBuildingWings(this.value, '#print_bill_wing_id');">
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
                        <div class="col-md-2 padding-0">
                            <div class="form-group">
                                <label for="" class="control-label">Wing Name</label>                               
                                <select name="wing_id" id="print_bill_wing_id" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 padding-2">
                            <div class="form-group">
                                <label for="" class="control-label">Bill For</label>                               
                                <select name="month" id="month" class="form-control">
                                    <option value="">Select Month</option>
                                     <?php if(isset($societyParameters['SocietyParameter']['billing_frequency_id']) && count($billingFrequenciesArray[$societyParameters['SocietyParameter']['billing_frequency_id']]) > 0) {
                                    foreach($billingFrequenciesArray[$societyParameters['SocietyParameter']['billing_frequency_id']] as $MonthID => $MonthName){?>
                                    <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                    <?php }?>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Unit/Flat/Shop Number</label>
                                <input type="text" class="form-control" id="flat_no" name="flat_no">
                            </div>
                        </div>                       
                        <div class="col-md-1 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Floor Number</label>
                                <input type="text" class="form-control" id="floor_no" name="floor_no">
                            </div>
                        </div>                       
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="" class="control-label">Bill Format</label>                                                             
                                <select name="billFormat" id="" class="form-control">
                                    <option value="">Select Format</option>
                                    <option value="">Format 1</option>
                                    <option value="">Format 2</option>
                                    <option value="">Format 3</option>
                                    <option value="">Format 4</option>
                                    <option value="">Format 5</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="bill_from" class="control-label">Bill Number. From</label>
                                <input type="text" class="form-control" id="bill_from" name="bill_from">
                            </div>
                        </div>               
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="bill_to" class="control-label">Bill Number. To</label>
                                <input type="text" class="form-control" id="bill_to" name="bill_to">
                            </div>
                        </div>
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="receipt_from" class="control-label">Receipt From</label>
                                <input type="date" class="form-control" id="receipt_from" name="receipt_from">
                            </div>
                        </div>               
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="receipt_to" class="control-label">Receipt To</label>
                                <input type="date" class="form-control" id="receipt_to" name="receipt_to">
                            </div>
                        </div>
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="unit_type" class="control-label">Show Unit</label>
                                <select name="unit_type" id="unit_type" class="form-control">
                                    <option value="Unit No">Unit Number</option>
                                    <option value="Flat-Shop">Flat-Shop</option>
                                    <option value="Flat No">Flat Number</option>
                                    <option value="Shop No">Shop Number</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="remove_details" class="control-label">Remove details </label>
                                <select  id="remove_details"  class="form-control" name="remove_details[]" multiple="multiple">
                                    <option value="unit-area">Unit Area</option>
                                    <option value="unit-type">Unit type</option>
                                    <option value="wing">Wing</option>
                                    <option value="floor-number">Floor Number</option>
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="words_type" class="control-label">Words Language</label>
                                <select name="words_type" id="words_type" class="form-control">
                                    <option value="English">English</option>
                                    <option value="Marathi">Marathi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="bill_type" class="control-label">Bill Type</label>
                                <select name="bill_type" id="bill_type" class="form-control">
                                    <option value="reg">Regular</option>
                                    <option value="sup">Supplementary</option>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="bill_type" class="control-label">Bill Format Type</label>
                                <select name="bill_format_type" id="bill_format_type" class="form-control">
                                    <option value="-1">Select Bill Format Type</option>
                                    <option value="1">Without Interest</option>
                                </select>
                            </div>
                        </div>                          
                        <div class="col-md-2 padding-1">
                            <div class="form-group">
                                <label for="words_type" class="control-label">Supplementary</label><br>
                                <input type="checkbox" name="supplementary_check">
                                
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-6 padding-0">
                            <button type="button" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="societyMemberBillPrint();">Print Bill</button>
                            <a type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-anim btn-sm btns" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                        </div>
                    </div>            
                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>