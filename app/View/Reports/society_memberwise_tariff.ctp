<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Memberwise Tariff</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="memberTariffForm" name="memberTariffForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">From &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="" name="data[MemberTariff][month]" required="">
                                                <option value="">Select Month</option>
                                                    <?php if(isset($billingFrequenciesArray) && count($billingFrequenciesArray) > 0) {
                                                        foreach($billingFrequenciesArray as $MonthID => $MonthName){?>
                                                        <?php if(isset($postData['MemberTariff']['month']) && $postData['MemberTariff']['month'] == $MonthID){?>
                                                <option selected="" value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php }else{?>
                                                <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php } }?>
                                                     <?php }?>
                                            </select> 
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">To &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="" name="data[MemberTariff][month_to]" required="">
                                                <option value="">Select Month</option>
                                                    <?php if(isset($billingFrequenciesArray) && count($billingFrequenciesArray) > 0) {
                                                        foreach($billingFrequenciesArray as $MonthID => $MonthName){?>
                                                        <?php if(isset($postData['MemberTariff']['month_to']) && $postData['MemberTariff']['month_to'] == $MonthID){?>
                                                <option selected="" value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php }else{?>
                                                <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                        <?php } }?>
                                                     <?php }?>
                                            </select> 
                                        </div>
                                        <div class="col-md-4">    
                                            <div class="society-report-form-box-label">Tariff &nbsp;</div>                                                                     
                                            <select class="society-report-form-box-field-dropdown" id="" name="data[MemberTariff][ledger_head_id]" required="">
                                                <option value="">Select Tariff</option>
                                                    <?php if(isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) {
                                                        foreach($societyLedgerHeadList as $tariffInfo){?>
                                                         <?php if(isset($postData['MemberTariff']['ledger_head_id']) && $postData['MemberTariff']['ledger_head_id'] == $tariffInfo['SocietyLedgerHeads']['id']){?>
                                                <option selected="" value="<?php echo $tariffInfo['SocietyLedgerHeads']['id']; ?>"><?php echo $tariffInfo['SocietyLedgerHeads']['title']; ?></option>
                                                            <?php }else{?>
                                                <option value="<?php echo $tariffInfo['SocietyLedgerHeads']['id']; ?>"><?php echo $tariffInfo['SocietyLedgerHeads']['title']; ?></option>
                                                            <?php }}?>
                                                     <?php }?>
                                            </select> 
                                        </div>                                                              
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-8">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button> 
                                                <a type="button" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);" onclick="printAllReports('print_memberwise_tariff');"><i class="fa fa-print"></i><span class="btn-text"> Print Friendly</span></a>                                                                               
                                                <a type="button" class="btn btn-success btn-anim btn-sm" href="javascript:void(0);"><i class="fa fa-file-excel-o"></i><span class="btn-text"> Export to PDF</span></a>                                          
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
                                                <select name="data[MemberTariff][building_id]" id="wingBuildingName" class="society-report-form-box-field-dropdown" onchange="getAllBuildingWings(this.value, '#memberTariff_report_wing_id');">
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($postData['MemberTariff']['building_id']) && $postData['MemberTariff']['building_id'] == $buildingID){?>
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
                                                <select class="society-report-form-box-field-dropdown" id="memberTariff_report_wing_id" name="data[MemberTariff][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">Flat No &nbsp;&nbsp;</div>  
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberTariff][flat_no]" value="<?php echo isset($postData['MemberTariff']['flat_no']) ? $postData['MemberTariff']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="society-report-form-field-box">
                                                <div class="society-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="society-report-form-box-field-50" id="" name="data[MemberTariff][flat_no_to]" value="<?php echo isset($postData['MemberTariff']['flat_no_to']) ? $postData['MemberTariff']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div><br>
                                    </div>    
                                </div>  

                            </form>  
                            <div class="clearfix"></div>
                            <br><br><br>

                            <div id="print_memberwise_tariff">
                            <div class="print-memberwise-tariff">
                                <div class="row1">
                                    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>

                                    <div class="report-bill">Tariff-wise Detail Reports</div>
                                    <div class="report-bill-outer-section">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th width="6%">Sr.</th>
                                                        <th width="10%">Unit No</th>
                                                        <th width="10%">Bill No</th>
                                                        <th>Member Name</th>
                                                        <th width="8%">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>  
                                                    <tr id="">
                                                        <td colspan="5" style="border:none !important;"><span class="font-weight-600">Month :- </span> <?php echo (isset($postData['MemberTariff']['month']) && !empty($postData['MemberTariff']['month'])) ? date("F", mktime(0, 0, 0, $postData['MemberTariff']['month'],10)) : ''; ?></td>                                                        
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" style="border:none !important;" ><span class="font-weight-600">Building :- </span> <?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></td>
                                                    </tr>   
                                                    <tr id="">
                                                        <td colspan="5" style="border:none !important;"><span class="font-weight-600">Wing :- </span></td>
                                                    <tr>
                                                <?php 
                                                $GrandTotal = 0;
                                                if(isset($societyMemberTariffDetails) && count($societyMemberTariffDetails) > 0){
                                                 $idCount = 1;                                                 
                                                 foreach($societyMemberTariffDetails as $societyMemberData){
                                                    $AmountTotal = isset($societyMemberData['MemberBillGenerate']['amount']) ? $societyMemberData['MemberBillGenerate']['amount']: 0;
                                                    $GrandTotal += $AmountTotal;
                                                    ?>
                                                    <tr id="<?php echo $idCount; ?>">
                                                        <td><?php echo $idCount; ?></td>
                                                        <td><?php echo isset($societyMemberData['Member']['flat_no']) ? $societyMemberData['Member']['flat_no'] :'' ; ?></td>
                                                        <td class="text-center"><?php echo isset($societyMemberData['MemberBillGenerate']['bill_number']) ? $societyMemberData['MemberBillGenerate']['bill_number'] :'' ; ?></td>
                                                        <td><?php echo isset($societyMemberData['Member']['member_name']) ? $societyMemberData['Member']['member_name'] :'' ; ?></td>
                                                        <td class="text-right"><?php echo $AmountTotal; ?></td>
                                                    </tr>
                                                    <tr id="">
                                                        <td colspan="4" class="text-center">Wing Total</td>
                                                        <td class="text-right"><?php echo $AmountTotal; ?></td>
                                                    </tr>
                                                    <tr id="">
                                                        <td colspan="4" class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></td>
                                                        <td class="text-right"><?php echo $AmountTotal; ?></td>
                                                    </tr>
                                                    <tr id="">
                                                        <td colspan="4" class="text-center"><?php echo (isset($postData['MemberTariff']['month']) && !empty($postData['MemberTariff']['month'])) ? date("F", mktime(0, 0, 0, $postData['MemberTariff']['month'],10)) : ''; ?> Total</td>
                                                        <td class="text-right"><?php echo $AmountTotal; ?></td>
                                                    </tr>
                                                    <tr id="" style="border-bottom:2px solid #000;">
                                                        <td colspan="4" class="text-center">Grand Total</td>
                                                        <td class="text-right"><?php echo round(($GrandTotal * 1),2);?></td>
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
</div>