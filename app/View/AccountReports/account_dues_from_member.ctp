<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Dues From Member</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="duesFromMemberFrm" method="post" id="duesFromMemberFrm" name="duesFromMemberFrm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                <div class="col-md-8 member-tariff-left-box"> 
                                    <div class="row">
                                        <div class="col-md-4 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">As on Date &nbsp;</div>  
                                                <input type="date" class="account-report-form-box-field-date" id="" name="data[MemberPayment][payment_date]" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :'' ; ?>">
                                            </div> 
                                        </div>                                            
                                        <div class="col-md-3 nopadding">  
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Type &nbsp;</div>  
                                                <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberPayment][report_type]">
                                                    <option value="">Select</option>  
                                                    <option value="Summary" <?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Summary'){echo 'selected';}?>>Summary</option>  
                                                    <option value="Detail"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Detail'){echo 'selected';}?>>Detail</option>  
                                                    <option value="Dues-Advance"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Dues-Advance'){echo 'selected';}?>>Dues-Advance</option> 
                                                    <option value="With Transaction"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='With Transaction'){echo 'selected';}?>>With Transaction</option> 
                                                    <option value="Regular"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Regular'){echo 'selected';}?>>Regular</option>  
                                                    <option value="Supplementary"<?php if(isset($postData['MemberPayment']['report_type']) && $postData['MemberPayment']['report_type']=='Supplementary'){echo 'selected';}?>>Supplementary</option> 
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-3 nopadding">   
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Operator &nbsp;</div>                                                                     
                                                <select class="account-report-form-box-field-dropdown-65" id="" name="data[MemberPayment][operator]">
                                                    <option value="">Select Operator</option>   
                                                    <?php //if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] ==''){?>
                                                    <option value=">" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '>') { echo 'selected';}?>>></option>
                                                    <option value="<" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<') { echo 'selected';}?>><</option>
                                                    <option value=">=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '>=') { echo 'selected';}?>>>=</option>
                                                    <option value="<=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<=') { echo 'selected';}?>><=</option>
                                                    <option value="=" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '=') { echo 'selected';}?>>=</option>
                                                    <option value="<>" <?php if(isset($postData['MemberPayment']['operator']) && $postData['MemberPayment']['operator'] == '<>') { echo 'selected';}?>><></option>
                                                    <?php //}?>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-2 nopadding">
                                            <div class="account-report-form-field-box">     
                                                <div class="account-report-form-box-label">Amount &nbsp;</div>  
                                                <input type="text" class="account-report-form-box-field-50 text-right" id="" name="data[MemberPayment][amount]" value="<?php echo isset($postData['MemberPayment']['amount']) ? $postData['MemberPayment']['amount'] :'' ; ?>">
                                            </div> 
                                        </div>  


                                        <div class="col-md-6 nopadding">   
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">Old Record &nbsp; &nbsp;</div>                                                                     
                                            <select class="account-report-form-box-field-dropdown-40" id="" name="data[MemberPayment][member_record]">
                                                    <!--<option value="">Select</option>  -->
                                            <option value="Current" <?php if (isset($postData['MemberPayment']['member_record']) && $postData['MemberPayment']['member_record'] == 'Current') {
                                            echo 'selected';
                                            } ?>>Current Member</option>  

                                            <option value="Old" <?php if (isset($postData['MemberPayment']['member_record']) && $postData['MemberPayment']['member_record'] == 'Old') {
                                            echo 'selected';
                                            } ?>>Old Member</option>  
                
                                            </select> 


                                            </div>
                                        </div>


                                                                 
                                    </div>  
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_dues_from_member');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>                                                                            
                                                <a data-toggle="tooltip" data-original-title="Export to PDF" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick=""><i class="fa fa-file-excel-o"></i><span class="btn-text">Export to PDF</span></a>  
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim btns" href=""><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a> 
                                                <button class="btn btn-primary" type="button" onclick="export_to_excel()">Export to excel</button>
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
                                                <select class="account-report-form-box-field-dropdown" id="" name="data[MemberPayment][building_id]" onchange="getAllBuildingWings(this.value, '#dues_from_member');">
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
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Wing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                                <select class="account-report-form-box-field-dropdown" id="dues_from_member" name="data[MemberPayment][wing_id]">
                                                    <option value="">Select Wing</option>  
                                                </select> 
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">Unit No &nbsp;&nbsp;</div>                                   
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberPayment][flat_no]" value="<?php echo isset($postData['MemberPayment']['flat_no']) ? $postData['MemberPayment']['flat_no'] :'' ; ?>">
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="account-report-form-field-box">
                                                <div class="account-report-form-box-label">To &nbsp;&nbsp;</div>                                    
                                                <input type="text" class="account-report-form-box-field-50" id="" name="data[MemberPayment][flat_no_to]" value="<?php echo isset($postData['MemberPayment']['flat_no_to']) ? $postData['MemberPayment']['flat_no_to'] :'' ; ?>">
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                    </div>    
                                </div>
                            </form> 
                            <div class="clearfix"></div>
                            <br><br><br>
                            <div id="print_dues_from_member">
                                <div class="print-dues-from-member">
                                    <div class="row">
                                        <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>
                                        <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>
                                    </div>
                                    <br>

                                    <div class="report-bill"><h5>Dues From Member As  <?php echo $formatted = date('d/M/Y', strtotime($_POST['data']['MemberPayment']['payment_date']));?></h5></div>
                                    <div class="report-bill"></div>  
                                    <div class="report-bill-outer-section no-border-thead">
                                        <div class="table-wrap1">
                                            <table id="" class="table table-bordered1 padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th style="width:5%;" class="text-center">Sr.</th>
                                                        <th style="width:5%;" class="text-center">Date</th>
                                                        <th style="width:5%;" class="text-center">Unit No.</th>
                                                        <th class="text-center">Member Name</th>
                                                        <?php if ($reportType == 'Detail'){?>
                                                        <th style="width:10%;" class="text-center">Principal</th>
                                                        <th style="width:10%;" class="text-center">Interest</th>
                                                        <th style="width:10%;" class="text-center">Tax</th>
                                                        <?php } ?>
                                                        <th style="width:10%;" class="text-center">Dues</th>
                                                        <?php if($reportType == 'Dues-Advance'){ ?>
                                                        <th style="width:10%;" class="text-center">Advance</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   <tr id="">
                                                            <td <?php if ($reportType == 'Detail'){?>colspan="8"<?php }else{ ?>colspan="5"<?php }?> class="border-none">Building : <?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></td>
                                                        </tr>
                                                        <tr id="">
                                                            <td <?php if ($reportType == 'Detail'){?>colspan="8"<?php }else{ ?>colspan="5"<?php }?> class="border-none"><span style="display: block;">Wing : <?php echo isset($societyDetails['Wing']['wing_name']) ? $societyDetails['Wing']['wing_name'] : ''; ?></span></td>
                                                        </tr> 
                                                <?php //TODO
                                                if(isset($accountDuesMemberSummary) && count($accountDuesMemberSummary) > 0){
                                                $idCount = 1;
                                                $duesTotalAmount = 0;
                                                $AdvanceTotalAmount=0;
                                                $principalTotal = $interestTotal = $taxTotal = 0;
                                                foreach($accountDuesMemberSummary as $accountDuesMemberInfo){
                                                    
                                                    if ($reportType == 'Detail'){
                                                        $dueAmount = $accountDuesMemberInfo['total_dues_amount']['due'];
                                                        $principal = $accountDuesMemberInfo['total_dues_amount']['principal'];
                                                        $interest = $accountDuesMemberInfo['total_dues_amount']['interest'];
                                                        $tax = $accountDuesMemberInfo['total_dues_amount']['tax'];
                                                        $tTotal = $principal + $interest + $tax;
                                                    }else{
                                                        $dueAmount = $accountDuesMemberInfo['total_dues_amount'];
                                                    }
                                                    
                                                    if($reportType != 'Dues-Advance' && $dueAmount < 0 ) continue;
                                                    
                                                   if($dueAmount == 0) {
                                                       continue;
                                                   }
                                                   $AdvanceTotalAmount += isset($dueAmount) && !empty($dueAmount) && $dueAmount < 0 ? abs($dueAmount) * 1 : 0.00;
                                                   $duesTotalAmount += isset($dueAmount) && !empty($dueAmount) && $dueAmount > 0 ? $dueAmount * 1 : 0.00;
                                                   if ($reportType == 'Detail'){
                                                       $principalTotal += $principal;
                                                       $interestTotal += $interest;
                                                       $taxTotal += $tax;
                                                   }
                                                 ?>
                                                    <tr id="<?php echo $idCount;  ?>">
                                                        <td><?php echo $idCount;  ?></td>
                                                        <td><?php echo isset($accountDuesMemberInfo['date']) ? $accountDuesMemberInfo['date'] : '';  ?></td>
                                                        <td><?php echo isset($accountDuesMemberInfo['flat_no']) ? $accountDuesMemberInfo['flat_no'] : '';  ?></td>
                                                        <td><?php echo isset($accountDuesMemberInfo['member_prefix']) ? $accountDuesMemberInfo['member_prefix'].$accountDuesMemberInfo['member_name'] : '';  ?> <?php echo isset($accountDuesMemberInfo['Member']['member_name']) ? $accountDuesMemberInfo['Member']['member_name'] : '';  ?></td>                                                <?php if ($reportType == 'Detail'){ $flag = ($dueAmount - $tTotal != 0) ? $dueAmount - $tTotal : 'yes';?>
                                                        <td class="text-right"><?php echo isset($principal) ? (number_format(abs($principal),2,'.',',')).(($principal >= 0) ? ' Dr' : ' Cr') : '';  ?></td>
                                                        <td class="text-right"><?php echo isset($interest) ? (number_format(abs($interest),2,'.',',')).(($interest >= 0) ? ' Dr' : ' Cr') : '';  ?></td>
                                                        <td class="text-right"><?php echo isset($tax) ? (number_format(abs($tax),2,'.',',')).(($tax >= 0) ? ' Dr' : ' Cr') : '';  ?></td>
                                                        <?php } ?>
                                                        <td class="text-right"><?php echo isset($dueAmount) && $dueAmount > 0 ? number_format($dueAmount,2,'.',',').' Dr' : '';  ?></td>
                                                        <?php if($reportType == 'Dues-Advance'){ ?>
                                                        <td class="text-right"><?php echo isset($dueAmount) && $dueAmount < 0 ? number_format(abs($dueAmount),2,'.',',').' Cr' : '';  ?></td>
                                                        <?php } ?>

                                                    </tr>      
                                                <?php $idCount++; }?> 
                                                    
                                                    <?php $colspan = ($reportType == 'Detail') ? 'colspan="6"' : 'colspan="4"';?>
                                                    <?php if ($reportType == 'Detail'){ ?>
                                                    <tr id="">
                                                        <td class="text-right" colspan="3" class="border-none">Total</td>
                                                        <td class="text-right"><?php $principalTotal1 = abs($principalTotal); echo number_format($principalTotal1,2,'.',','); echo ($principalTotal >= 0) ? ' Dr' : ' Cr';  ?>
                                                        <td class="text-right"><?php $interestTotal1 = abs($interestTotal); echo number_format($interestTotal1,2,'.',','); echo ($interestTotal >= 0) ? ' Dr' : ' Cr';  ?>
                                                        <td class="text-right"><?php $taxTotal1 = abs($taxTotal); echo number_format($taxTotal1,2,'.',','); echo ($taxTotal >= 0) ? ' Dr' : ' Cr';  ?>        
                                                        <td class="text-right"><?php echo $duesTotalAmount.' Dr';  ?></td>
                                                    </tr>  
                                                    <?php }else{ ?>
                                                    <tr id="">
                                                        <td class="text-right" <?php echo $colspan;?> class="border-none">Total</td>
                                                        <td class="text-right"><?php echo number_format($duesTotalAmount,2,'.',',').' Dr';  ?></td>
                                                        <?php if($reportType == 'Dues-Advance'){ ?>
                                                        <td class="text-right"><?php echo number_format($AdvanceTotalAmount,2,'.',',').' Cr';  ?></td>
                                                        <?php } ?>
                                                        
                                                    </tr>      
                                                    <?php } ?>
                                                    <tr id="">
                                                        <td class="text-right" <?php echo $colspan;?>><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?> Total</td>
                                                        <td class="text-right"><?php echo number_format($duesTotalAmount,2,'.',',').' Dr'; ?></td>
                                                        <?php if($reportType == 'Dues-Advance'){ ?>
                                                        <td class="text-right"><?php echo number_format($AdvanceTotalAmount,2,'.',',').' Cr';  ?></td>
                                                        <?php } ?>
                                                    </tr>                                                        
                                                    <tr id="">
                                                        <td class="text-right" <?php echo $colspan;?>>Grand Total</td>
                                                        <td class="text-right"><?php echo number_format($duesTotalAmount,2,'.',',').' Dr'; ?></td>
                                                        <?php if($reportType == 'Dues-Advance'){ ?>
                                                        <td class="text-right"><?php echo number_format($AdvanceTotalAmount,2,'.',',').' Cr';  ?></td>
                                                        <?php } ?>
                                                    </tr> 
                                                 <?php }else{?>
                                                    <tr><td colspan="11"><center>Record not found.</center></td></tr>
                                                <?php }?>
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
script lang="javascript" src="dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
   function export_to_excel(){
      let print_dues_from_member=document.getElementById('print_dues_from_member');
      var pxe=XLSX.utils.table_to_book(print_dues_from_member,{sheet:'Dues From member'});
      XLSX.write(pxe,{
         bookType:'xlsx',
         type:'base64'
      });
      XLSX.writeFile(pxe,'dues_from_member.xlsx');
   }
</script>