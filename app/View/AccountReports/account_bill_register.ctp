<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bill Register</h6>
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
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">From Date &nbsp;</div>  
                                                <!--input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][bill_generated_date]" value="<?php echo isset($postData['MemberBillSummary']['bill_generated_date']) ? $postData['MemberBillSummary']['bill_generated_date'] :'' ; ?>">-->
                                                <input type="date" name="data[MemberBillSummary][bill_generated_date]" class="account-report-form-box-field-date-75"  value="<?php echo isset($postData['from_date']) ? $postData['from_date'] :'' ; ?>">
                                                
												<!--select class="account-report-form-box-field-date" name="data[MemberBillSummary][start_month]"-->

                                                <?php 

                                                    if(!empty($societyGeneratedBillMonths)){
                                                        foreach($societyGeneratedBillMonths as $billData){ ?>
                                                        <option value="<?= $billData['bill_month_id'];?>"><?= $billData['bill_month'];?></option>    
                                                <?php
                                                        }
                                                    }
                                                ?>
                                                </select>
                                            </div> 
                                        </div>
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                <div class="account-report-form-box-label">To Date &nbsp;</div>  
												<input type="date" name="data[MemberBillSummary][bill_generated_date_to]"  class="account-report-form-box-field-date-75" id=""  value="<?php echo isset($postData['to_date']) ? $postData['to_date'] :'' ; ?>">
                                                
                                                <!--<input type="date" class="account-report-form-box-field-date" id="" name="data[MemberBillSummary][bill_generated_date_to]" value="<?php echo isset($postData['MemberBillSummary']['bill_generated_date_to']) ? $postData['MemberBillSummary']['bill_generated_date_to'] :'' ; ?>">-->
                                            <!--select class="account-report-form-box-field-date" name="data[MemberBillSummary][end_month]" -->
                                                <?php 

                                                    if(!empty($societyGeneratedBillMonths)){
                                                        foreach($societyGeneratedBillMonths as $billData){ ?>
                                                        <option value="<?= $billData['bill_month_id'];?>"><?= $billData['bill_month'];?></option>    
                                                <?php
                                                        }
                                                    }
                                                ?>
                                                </select>                                                
                                            </div> 
                                        </div>
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
  
                                                 <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberBillSummary][bill_type]">
                                                        <option value="reg" selected="selected" >Regular</option>
                                                        <option value="sup" >Supplementary</option>
                                                </select>
                                            </div> 
                                        </div>                                        
                                        <div class="col-md-3 no-padding-right"> 
                                            <div class="account-report-form-field-box">   
                                                    <select class="account-report-form-box-field-dropdown-80" id="" name="data[MemberBillSummary][member_record_range]">
                                                        <option value="-1">Member Record Range</option>
                                                        <option value="1">1 to 100 Records</option>
                                                        <option value="2">101 to 200 Records</option>
                                                        <option value="3">201 to 300 Records</option>
                                                        <option value="4">301 to 400 Records</option>
                                                        <option value="5">401 to 500 Records</option>
                                                        <option value="6">501 to 600 Records</option>
                                                        <option value="7">601 to 700 Records</option>
                                                    </select>      
                                                </div> 
                                        </div>                                        
                                    </div>  
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">                                
                                            <div class="form-group">
                                                <button type="submit" data-toggle="tooltip" data-original-title="" class="btn btn-success btn-anim btns"><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printAllReports('print_bill_register');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>
                                               
                                                <?php echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Export to Excel', array('controller'=>'excels_report','action' => 'download_account_bill_register','?' => array('bill_generated_date_to' => isset($postData['MemberBillSummary']['bill_generated_date_to']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date_to']) : '','bill_generated_date' => isset($postData['MemberBillSummary']['bill_generated_date']) ? base64_encode($postData['MemberBillSummary']['bill_generated_date']) : '')), array('class' => 'btn btn-success btns','data-toggle' => 'tooltip', 'data-original-title' => 'download bill register','escape' => false,'target' => '_blank')); ?>
                                            <div ><button type="button" class="btn btn-primary" onclick="export_product_excel()" value="Export" >Export  Excel</button></div>
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
                            <div class="clearfix"></div><br><br>
                            <div id="print_bill_register">
                                <div class="print-bill-register" id="export_to_excel_bill_register">
                                    <!--<div class="row">-->
                                    <!--    <h5 class="text-center"><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5>-->
                                    <!--    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></div>-->
                                    <!--    <div class="report-address-heading"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></div>-->
                                    <!--</div>-->
                                    <br>
                                    <div class="row">
                                        <!--<div class="report-bill">Bill Register</div>-->
                                        <div class="report-bill"></div> 
                                        <div class="report-bill-outer-section">
                                            <div class="table-wrap table-responsive">
                                                <table id="" class="table padding-td-none padding-th-none">
                                                    
                                                    <thead>
                                                        <!--Society Details Start-->
                                                        
                                                        <tr style="border: solid; border-color: whitesmoke;"><th colspan="10" style="background: #f5f5f5; text-align:left;"><div class="">Bill Register</div></th></tr>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th colspan="10" style="background: #f5f5f5;"> <h5 ><?php echo isset($societyDetails['Society']['society_name']) ? $societyDetails['Society']['society_name'] : ''; ?></h5></th></tr>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th  colspan="10" style="background: #f5f5f5;"><?php echo isset($societyDetails['Society']['registration_no']) ? $societyDetails['Society']['registration_no'] : ''; ?></th></tr>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th  colspan="10" style="background: #f5f5f5;"><?php echo isset($societyDetails['Society']['address']) ? $societyDetails['Society']['address'] : ''; ?></th></tr>
                                                        <tr style="border: solid; border-color: whitesmoke;"><th  colspan="10" style="background: #f5f5f5;"><?php echo isset($postData['MemberBillSummary']['bill_generated_date']) ? $postData['MemberBillSummary']['bill_generated_date'] :'' ; ?> To 
                                                       <?php echo isset($postData['MemberBillSummary']['bill_generated_date_to']) ? $postData['MemberBillSummary']['bill_generated_date_to'] :'' ; ?>
                                                            
                                                       </th></tr>
                                                        
                                                         
                                                             <!--Society Details Start-->
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Unit No</th>
                                                            <th>Member</th>
                                                            <th>For</th>
                                                            <th width="5%">Bill No</th>
                                                            <th>Bill Date</th>
                                                            <?php foreach($uniqueTariffsInBills as $billLedgerId=>$billLedgerTitle) {
                                                                echo "<th>".substr($billLedgerTitle,0,5)."</th>";
                                                            } ?>
                                                            <th>Amount</th>
                                                            <th>Int</th>
                                                            <th>Bill Amt</th>
                                                            <th>P_Arrear</th>
                                                            <th>I_Arrear</th>
                                                            <th>T_Arrear</th>
                                                            <th>Payable</th>
                                                            <th>Paid</th>
                                                            <th>Adjust</th>
                                                            <th>Discount</th>
                                                            <th>Bal.</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                <?php
                                                $tarrifVerticalTotal = array();
                                                $monthlyAmtTotal = $inteAmtTotal = $billAmtTotal = $prinArrTotal = $inteArrTotal = $taxArrTotal = $payAmtTotal = $paidAmtTotal = $adjuAmtTotal = $balanceAmtTotal = 0.00;
                                                $sr=1;
                                                foreach($billRegisterData as $billId=>$billDetails) {
                                                    $billSummary = $billDetails['MemberBillSummary'];                                                    
                                                    $monthlyAmtTotal +=$billSummary['monthly_amount'];
                                                    $inteAmtTotal +=$billSummary['interest_on_due_amount'];
                                                    $billAmtTotal +=$billSummary['monthly_bill_amount'];
                                                    $prinArrTotal +=$billSummary['op_principal_arrears'];
                                                    $inteArrTotal +=$billSummary['op_interest_arrears'];
                                                    $taxArrTotal +=$billSummary['op_tax_arrears'];
                                                    $payAmtTotal +=$billSummary['amount_payable'];
                                                    $billTariffs = $billDetails['MemberBillGenerate'];
                                                    $paidAmtTotal += $billDetails['amountPaid'];
                                                    $rebate = ($billSummary['interest_adjusted']+$billSummary['tax_adjusted']+$billSummary['principal_adjusted']);
                                                    $discount= $billSummary['discount'];
                                                    $adjuAmtTotal +=$rebate;
                                                    $discountAmtTotal +=$discount;
                                                    $balanceAmtTotal +=$billSummary['amount_payable']-$billDetails['amountPaid']-($rebate+$discount);
                                                    $memberDetails = $membersArray[$billSummary['member_id']];
                                                ?>
                                                        <tr>
                                                            <td><?php echo $sr; ?></td>
                                                            <td><?php echo $memberDetails['flat_no']; ?></td>
                                                            <td><?php echo $memberDetails['member_prefix'].''.$memberDetails['member_name']; ?></td>
                                                            
                                                            
                                                            
                                                            <td><?php echo $societyObj->monthWordFormatByBillingFrequency($billSummary['month']);?></td>
                                                            
                                                         
                                                            
                                                            <td><?php echo $billSummary['bill_no']; ?></td>
                                                            <td><?php echo $utilObj->getFormatDate($billSummary['bill_generated_date'],'d/m/Y'); ?></td>

                                                    <?php foreach($uniqueTariffsInBills as $billLedgerId=>$billLedgerTitle) {
                                                        $amount = 0.00;
                                                        if(isset($billTariffs[$billLedgerId])) {
                                                           $amount = $billTariffs[$billLedgerId];
                                                           $tarrifVerticalTotal[$billLedgerId]['amount'] = isset($tarrifVerticalTotal[$billLedgerId]['amount']) ? $tarrifVerticalTotal[$billLedgerId]['amount'] + $amount : $amount;
                                                        }   
                                                        echo "<td class=\"text-right\">$amount</td>";
                                                    } ?>
                                                            <td class="text-right"><?php echo $billSummary['monthly_amount']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['interest_on_due_amount']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['monthly_bill_amount']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['op_principal_arrears']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['op_interest_arrears']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['op_tax_arrears']; ?></td>
                                                            <td class="text-right"><?php echo $billSummary['amount_payable']; ?></td>
                                                            <td class="text-right"><?php echo $billDetails['amountPaid']; ?></td>
                                                            <td class="text-right"><?php echo ($rebate > 0) ? $rebate : '0.00'; ?></td>
                                                            <td class="text-right"><?php echo $discount; ?></td>
                                                            <td class="text-right"><?php echo ($billSummary['amount_payable']-$billDetails['amountPaid']-($rebate)); ?></td>
                                                        </tr> 
                                                <?php $sr++;}?>
                                                    </tbody>   
                                                    <tfoot id=""> 
                                                        <tr>                                                                       
                                                            <td colspan="6" class="text-right" style="font-weight:bold;">Grand Total:</td>
                                                    <?php 
                                                    foreach($tarrifVerticalTotal as $billLedgerId=>$amountTariff) {
                                                        echo "<td class=\"text-right\" style=\"font-weight:bold;\">".number_format((float)$amountTariff['amount'],2,'.','')."</td>";
                                                    } 
                                                    ?>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $monthlyAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $inteAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $billAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $prinArrTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $inteArrTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $taxArrTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $payAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $paidAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $adjuAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $discountAmtTotal; ?></td>
                                                            <td class="text-right" style="font-weight:bold;"><?php echo $balanceAmtTotal; ?></td>
                                                        </tr>
                                                    </tfoot>
                                                </table> 
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
    </div>
</div> 
<script lang="javascript" src="dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
   function export_product_excel(){
      let export_to_excel_bill_register=document.getElementById('export_to_excel_bill_register');
      var pxe=XLSX.utils.table_to_book(export_to_excel_bill_register,{sheet:'Bill_Register'});
      XLSX.write(pxe,{
         bookType:'xlsx',
         type:'base64'
      });
      XLSX.writeFile(pxe,'export_to_excel_bill_register.xlsx');
   }
</script>