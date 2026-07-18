<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            	<?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Flat / </h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <form method="post" id="societyMemberDetailsForm" name="societyMemberDetailsForm" autocomplete="off" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                            <div class="modal-flat-shop-form-box">
                                <div class="row">                           
                                    <div class="col-md-8">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Member Name:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-70" id="society_member_name" name="data[Member][member_name]" ><?php echo $societyMemberDetails['Member']['member_name'];?></div>
                                        </div>
                                    </div>                           
                                    <div class="col-md-2">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Unit sr.:&nbsp;&nbsp;&nbsp;&nbsp;</div>  
                                            <div class="modal-flat-shop-form-box-field-40 readonly" id="member_serial_no" name="member_serial_no" ><?php echo isset($societyMemberDetails['Member']['id']) ? $societyMemberDetails['Member']['id'] :0; ?></div>
                                        </div>
                                    </div> 
                                    <div class="col-md-2">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Unit No.:&nbsp;&nbsp;&nbsp;&nbsp;</div>  
                                            <div class="modal-flat-shop-form-box-field-40 " id="member_unit_no" name="member_unit_no" ><?php echo isset($societyMemberDetails['Member']['flat_no']) ? $societyMemberDetails['Member']['flat_no'] :0; ?></div>
                                        </div>
                                    </div> 
                                </div>    
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Building Name:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                                                        
                                            <div name="data[Member][building_id]" id="member_building_id"  class="modal-flat-shop-form-box-field-dropdown" ><?php echo $societyMemberDetails['Member']['building_id'];?></div>  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Wing:&nbsp;&nbsp;&nbsp;&nbsp;</div> 
                                            <div name="data[Member][wing_id]" id="member_wing_id" class="modal-flat-shop-form-box-field-dropdown"><?php echo $societyMemberDetails['Wing']['wing_name']; ?></div>
                                        </div>
                                    </div>                           
                                    <div class="col-md-3">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Floor No.:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-60 " id="member_floor_no" name="data[Member][floor_no]"  ><?php echo isset($societyMemberDetails['Member']['floor_no']) ? $societyMemberDetails['Member']['floor_no'] :0; ?></div>
                                        </div>
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Unit Type:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <?php 
                                                if(isset($societyMemberDetails['Member']['unit_type']) && $societyMemberDetails['Member']['unit_type'] == 'C') { $societyMemberDetails['Member']['unit_type'] = 'Commercial';} else if(isset($societyMemberDetails['Member']['unit_type']) && $societyMemberDetails['Member']['unit_type'] == 'R') { $societyMemberDetails['Member']['unit_type'] = 'Residential';} else if(isset($societyMemberDetails['Member']['unit_type']) && $societyMemberDetails['Member']['unit_type'] == 'B') { $societyMemberDetails['Member']['unit_type'] = 'Both';}
                                            ?>
                                            <div class="modal-flat-shop-form-box-field-85" id="member_unit_type" name="data[Member][unit_type]" ><?php echo $societyMemberDetails['Member']['unit_type'];?></div>
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
                                            <div class="modal-flat-shop-form-box-label">Area:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-50 " id="member_flat_area" name="data[Member][area]"><?php echo isset($societyMemberDetails['Member']['area']) ? $societyMemberDetails['Member']['area'] :0; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Carpet:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-50 " id="member_carpet" name="data[Member][carpet]" ><?php echo isset($societyMemberDetails['Member']['carpet']) ? $societyMemberDetails['Member']['carpet'] :0; ?></div>
                                        </div>
                                    </div>                            
                                    <div class="col-md-3">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Commercial:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-40 " id="member_commercial" name="data[Member][commercial]" ><?php echo isset($societyMemberDetails['Member']['commercial']) ? $societyMemberDetails['Member']['commercial'] :0; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Residential:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-40 " id="member_residential" name="data[Member][residential]"><?php echo isset($societyMemberDetails['Member']['residential']) ? $societyMemberDetails['Member']['residential'] :0; ?></div>
                                        </div>
                                    </div>                            
                                    <div class="col-md-2">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Terrace:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-40 " id="member_terrace" name="data[Member][terrace]" ><?php echo isset($societyMemberDetails['Member']['terrace']) ? $societyMemberDetails['Member']['terrace'] :0; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                            
                                    <div class="col-md-3">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Op.Principal:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-40 " id="member_principal" name="member_principal"><?php echo isset($societyMemberDetails['Member']['op_principal']) ? $societyMemberDetails['Member']['op_principal'] :0; ?></div>
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Interest:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-50 " id="member_interest" name="member_Interest" ><?php echo isset($societyMemberDetails['Member']['op_interest']) ? $societyMemberDetails['Member']['op_interest'] :0; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="modal-flat-shop-form-field-box">
                                            <div class="modal-flat-shop-form-box-label">Tax:&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                            <div class="modal-flat-shop-form-box-field-50 " id="member_tax" name="member_tax"><?php echo isset($societyMemberDetails['Member']['op_tax']) ? $societyMemberDetails['Member']['op_tax'] :0; ?></div>
                                        </div>
                                    </div>   
                                    <div class="col-md-3"></div>

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
                                                                <h5 class="text-center"><?php echo isset($societyMemberDetails['Society']['society_name']) ? $societyMemberDetails['Society']['society_name'] : ''; ?></h5>
                                                                <div class="report-address-heading"><?php echo isset($societyMemberDetails['Society']['registration_no']) ? $societyMemberDetails['Society']['registration_no'] : ''; ?></div>
                                                                <div class="report-address-heading"><?php echo isset($societyMemberDetails['Society']['address']) ? $societyMemberDetails['Society']['address'] : ''; ?></div>                                                                                                           
                                                                <div class="report-bill">Member Ledger</div>
                                                                <div class="building-name"><span>Building Name : </span><span id="print_header_section_bulding_name"><?php echo isset($societyMemberDetails['Building']['building_name']) ? $societyMemberDetails['Building']['building_name'] :0; ?></span></div>
                                                                <div class="wing-name"><span>Wing : </span><span id="print_header_section_bulding_wing"><?php echo isset($societyMemberDetails['Wing']['wing_name']) ? $societyMemberDetails['Wing']['wing_name'] :0; ?></span></div><br>
                                                                <div class="member-name"><span>Member Name : </span><span id="print_header_section_member_name"><?php echo isset($societyMemberDetails['Member']['member_name']) ? $societyMemberDetails['Member']['member_name'] :0; ?></span></div>
                                                                <div class="flat-no"><span>Unit No : </span><span id="print_header_section_unit_no"><?php echo isset($societyMemberDetails['Member']['flat_no']) ? $societyMemberDetails['Member']['flat_no'] :0; ?></span></div>
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
                                                                        $op_principal = isset($societyMemberDetails['Member']['op_principal']) ? $societyMemberDetails['Member']['op_principal'] * 1 : 0.00;
                                                                        $op_interest = isset($societyMemberDetails['Member']['op_interest']) ? $societyMemberDetails['Member']['op_interest'] * 1 : 0.00 ;
                                                                        $op_tax = isset($societyMemberDetails['Member']['op_tax']) ? $societyMemberDetails['Member']['op_tax'] * 1 : 0.00 ;
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
                                                                        $indBalance = $op_balance;
                    
                                                                        $tempOpBalance = abs($op_balance);
                                                                        ?>
                                                                        <tr>
                                                                            <td><a href="javascript:void(0);" onclick="loadSocietyBillsModel('#bill_modal');"><?php echo $societyMemberDetails['op_bill_due_date'];?></a></td>
                                                                            <td class="opening-bal">Opening Balance</td>
                                                                            <td class="text-right total_debit"><?php echo number_format((float)$openingCr, 2, '.', ''); ?></td>
                                                                            <td class="text-right total_credit"><?php echo number_format((float)$openingDr, 2, '.', ''); ?></td>
                                                                            <td class="text-right foot total_balance"><?php echo number_format((float)abs($op_balance), 2, '.', '').' '.$posNeg;?></td>
                                                                        </tr>
                                                                        <?php 
                                                                            //TODO
                                                                            
                                                                            if(isset($societyMemberDetails['memberBillPayment']) && count($societyMemberDetails['memberBillPayment']) > 0){
                                                                            $idCount = 1; $totalDr =$openingCr; $totalCr = $openingDr;
                                                                            //$paymentTotalAmount = 0;
                                                                            //$monthlyTotalAmount = 0;
                                                                            foreach($societyMemberDetails['memberBillPayment'] as $paymentData){
                                                                                $billParticularString = $paymentData['particular'];
                            
                            //indBalance = parseInt(indBalance)+ parseInt(MemberBillData.credit)-parseInt(MemberBillData.debit);
                                                                                $indBalance = $indBalance + $paymentData['debit']-$paymentData['credit'];
                                                                                $paymentBal = abs($indBalance);
                                                                                $checkNegativePositive = $indBalance < 0 ? 'Cr' : 'Dr';

                                                                                if($paymentData['flag'] == 'bill') { $totalDr = $totalDr + $paymentData['debit'];?>
                                                                        <tr id="" class="payment_entries_<?php echo $societyMemberDetails['Member']['id']?>">
                                                                                <td type="date"><?php echo isset($paymentData['formattedDate']) ? $paymentData['formattedDate']: '';  ?></td>
                                                                                <td>
                                                                                    <a href="javascript:void(0);" onclick="loadSocietyBillsModelFromFlatShop('#bill_modal',<?php echo $paymentData['bill_id']?>,<?php echo $paymentData['month'];?>);"><?php echo $billParticularString; ?></a>
                                                                                </td>
                                                                                <td class="text-right"><?php echo isset($paymentData['debit']) ? $paymentData['debit']: '';  ?></td>
                                                                                <td class="text-right">0.00</td>
                                                                                <td class="text-right"><?php echo number_format((float)$paymentBal,2,'.', '').' '.$checkNegativePositive;  ?></td>
                                                                            </tr>
                                                                                   <?php
                                                                                } else { $totalCr = $totalCr + $paymentData['credit'];
                                                                                    ?>
                                                                            <tr id="" class="payment_entries_<?php echo $societyMemberDetails['Member']['id']?>">
                                                                                <td type="date"><?php echo isset($paymentData['formattedDate']) ? $paymentData['formattedDate']: '';  ?></td>
                                                                                <td>
                                                                                    <a href="javascript:void(0);" <?php if ($paymentData['flag'] == 'receipt'){?> onclick="loadReceiptsModelFromFlatShop('#bill_modal',<?php echo $paymentData['paymentId']?>,<?php echo $paymentData['month'];?>);" <?php } ?>><?php echo $billParticularString; ?></a>
                                                                                </td>
                                                                                <td class="text-right"><?php echo isset($paymentData['debit']) ? $paymentData['debit']: '';  ?></td>
                                                                                <td class="text-right"><?php echo isset($paymentData['credit']) ? $paymentData['credit']: '';  ?></td>
                                                                                <td class="text-right"><?php echo number_format((float)$paymentBal,2,'.', '').' '.$checkNegativePositive;  ?></td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                                <?php }
                                                                                    ?>
                                                                                 
                                                                        <?php $idCount++;}?>
                                                                        <tfoot id="flat_shop_footer"> 
                                                                            <tr>
                                                                                <?php 
                                                                                    $totalBal = $totalDr - abs($totalCr);
                                                                                   // echo $totalBal;

                                                                                    if($totalBal < 0) {
                                                                                        $posNeg = 'Cr';
                                                                                    } else { $posNeg = 'Dr';}
                                                                                ?>
                                                                                <td colspan="2" class="text-right">Total</td>
                                                                                <td id="total_debit_flat_shop_sum" class="text-right"><?php  echo number_format((float)$totalDr, 2, '.', '');?></td>
                                                                                <td id="total_credit_flat_shop_sum" class="text-right"><?php  echo number_format((float)abs($totalCr),2,'.', ''); ?></td>
                                                                                <td id="total_balance_flat_shop_sum" class="text-right" style="font-weight:bold;"><?php echo number_format((float)abs($totalBal),2,'.', '').' '; echo $posNeg;?></td>

                                                                            </tr>
                                                                        </tfoot>
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
    </div>
</div>