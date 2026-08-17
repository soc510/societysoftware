<?php
$db = $this->Session->read('Auth.dbConfig');
if ($db != 'default'){
    $op = '01/04/2019';
} else {
    $mon = date('m');

    if ($mon > 4) {
        $y = date('Y');
    } else {
        $y = date('Y', strtotime('-1 year'));
    }
    $op = '01/04/'.$y;
}
?>
<!-- Modal for society Ledger Head -->

<div class="modal fade" id="society_ledger_heads_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title modal-title-color">Account Master / Ledger Heads</h6>
            </div>
            <!--<ul class="pager">
                 <li class="previous"><a class="btn btn-warning btn-rounded" id="ledger_head_previous_button" href="javascript:void(0);" onclick="societyLedgerHeadPrevious();" disabled>Previous</a></li>
                 <li class="next"><a class="btn btn-warning btn-rounded" id="ledger_head_next_button" href="javascript:void(0);" onclick="societyLedgerHeadNext();">Next</a></li>
             </ul>-->
			 
            <ul class="pager1">
                <a class="previous previous-btn-list list-unstyled" id="ledger_head_previous_button" href="javascript:void(0);" onclick="societyLedgerHeadPrevious();" style="display:none;"><<</a>
                <a class="next next-btn-list list-unstyled" id="ledger_head_next_button" href="javascript:void(0);" onclick="societyLedgerHeadNext();">>></a>
            </ul>

            <div class="modal-body modal-body-bg">
                <div id="modal_ledger_notify_error"></div>
                <form method="post" id="societyAddLedgerHeadsForm" name="societyAddLedgerHeadsForm"  data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                    <div class="modal-ledger-form-box">
                        <div class="row">                            
                            <div class="col-md-10">
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Particulars<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
<input type="hidden" class="modal-ledger-form-box-field-85" id="LedgerHeadsTitle" name="data[SocietyLedgerHeads][title]" value="<?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['title']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['title'] :''; ?>"required="">

<select class="modal-ledger-form-box-field-dropdown" id="LedgerHeadsId" name="data[SocietyLedgerHeads][id]" onchange="getSocietyLedgerHeadDetails(this.value);" required="">
                <option value="" selected="selected">Select</option>
                                        <?php
                                         if (isset($societyLedgerHeadsLists) && count($societyLedgerHeadsLists) > 0) :
                                                        $s = 1;
                                                        foreach ($societyLedgerHeadsLists as $inc => $societyLedgerHead) {
                                if (isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['id']) && $societyLedgerHeadsDetails['SocietyLedgerHeads']['id'] == $societyLedgerHead['SocietyLedgerHeads']['id']) {
                                                                ?>
                                                    <option value="<?php echo $societyLedgerHead['SocietyLedgerHeads']['id']; ?>" ><?php echo $societyLedgerHead['SocietyLedgerHeads']['title']; ?></option>
                                                            <?php } else { ?>
                                                    <option value="<?php echo $societyLedgerHead['SocietyLedgerHeads']['id']; ?>" ><?php echo $societyLedgerHead['SocietyLedgerHeads']['title']; ?></option>
                                                                <?php
                                                                $s++;
                                                            }
                                                        } endif;
                                                    ?>
                                                </select>                                
</div>
                            </div>
                            <div class="col-md-2">
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Sr &nbsp;</div>    
                                    <input type="hidden" id="ledger_head_previous" name="ledger_head_previous" value="<?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['id']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['id'] :0; ?>">
                                    <input type="hidden" id="ledger_head_next" name="ledger_head_next" value="<?php echo isset($societyLedgerHeadsNextRecord['SocietyLedgerHeads']['id']) ? $societyLedgerHeadsNextRecord['SocietyLedgerHeads']['id'] :0; ?>">
                                    <input type="text" class="modal-ledger-form-box-field-60 text-right readonly" id="serial_no" name="data[SocietyLedgerHeads][id]" value="<?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['id']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['id'] :''; ?>" readonly>
                                </div>
                            </div>
                        </div>                              
                        <div class="row">                            
                            <div class="col-md-4">
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Short Code<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" id="short_code" class="modal-ledger-form-box-field-50"name="data[SocietyLedgerHeads][short_code]" value="<?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['short_code']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['short_code'] :''; ?>"required=""> 
                                </div> 
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Subgroup<span class="required">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                                                       
                                    <select class="modal-ledger-form-box-field-dropdown" id="society_head_sub_category" name="data[SocietyLedgerHeads][society_head_sub_category_id]">
                                        <option value="<?php echo $societyLedgerHeadsDetails['SocietyHeadSubCategory']['id']; ?>" selected><?php echo $societyLedgerHeadsDetails['SocietyHeadSubCategory']['title']; ?></option>  
                                    </select>
                                </div>
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Group<span class="required">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>  
                                    <select class="modal-ledger-form-box-field-dropdown" id="AccountHeadsCategoryID" name="data[SocietyLedgerHeads][account_head_id]" required="">
                                        <option value="<?php echo $societyLedgerHeadsDetails['AccountHead']['id']; ?>" selected><?php echo $societyLedgerHeadsDetails['AccountHead']['title']; ?></option>
                                    </select>     
                                </div>
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Category<span class="required">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                                                     
                                    <select class="modal-ledger-form-box-field-dropdown" id="AccountCategoryID" name="data[SocietyLedgerHeads][account_category_id]" onchange="getAccountHeads(this.value, '#AccountHeadsCategoryID');" required="">
                                        <option value="">Select Account Category</option>
                                              <?php if(isset($societyAccountCategoryLists) && count($societyAccountCategoryLists) > 0) {
                                              foreach($societyAccountCategoryLists as $accountCategoryId => $accountCategoryName){?>
                                              <?php if(isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['account_category_id']) && $societyLedgerHeadsDetails['SocietyLedgerHeads']['account_category_id'] == $accountCategoryId){?>
                                        <option value="<?php echo $accountCategoryId; ?>" selected><?php echo $accountCategoryName; ?></option>
                                              <?php }else {?>
                                        <option value="<?php echo $accountCategoryId; ?>"><?php echo $accountCategoryName; ?></option>
                                              <?php }?>
                                              <?php } }?>
                                    </select> 
                                </div>
                            </div>                    
                            <div class="col-md-4">
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Opening Balance <span class="required">*</span>&nbsp;</div>                                    
                                    <input type="text" class="modal-ledger-form-box-field-50 text-right" id="op_balance" name="data[SocietyLedgerHeads][opening_amount]" value="<?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['opening_amount']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['opening_amount'] :''; ?>" placeholder="" required="">
                                </div> 
                                <!--<div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Previous Year <span class="required">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-ledger-form-box-field-50 text-right" id="" name="" placeholder="" required="">
                                </div>
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Estimate Amount<span class="required">*</span>&nbsp;</div>                                    
                                    <input type="text" class="modal-ledger-form-box-field-50 text-right" id="" name="" placeholder="" required="">
                                </div>
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Closing Balance<span class="required">*</span> &nbsp;&nbsp;</div>                                    
                                    <input type="text" class="modal-ledger-form-box-field-50 text-right" id="" name="" value="<?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['id']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['id'] :''; ?>" placeholder="Closing Balance">
                                </div>-->
                            </div>       
                            <div class="col-md-4">
                                <div class="modal-ledger-tariff-head">Bill Tariff Details</div>  
                                <input type="checkbox" name="ledger_heads_is_in_bill_charges" id="ledger_heads_is_in_bill_charges" <?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['is_in_bill_charges']) && $societyLedgerHeadsDetails['SocietyLedgerHeads']['is_in_bill_charges'] == 1 ? 'checked' :''; ?> > Is this account  a part of Bill Charges<br>
                                <input type="checkbox" name="ledger_heads_is_tax_applicable" id="ledger_heads_is_tax_applicable" <?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['is_tax_applicable']) && $societyLedgerHeadsDetails['SocietyLedgerHeads']['is_tax_applicable'] == 1 ? 'checked' :''; ?>> Service Tax/GST Applicable ?<br>
                                <input type="checkbox" name="ledger_heads_is_rebate_applicable" id="ledger_heads_is_rebate_applicable" <?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['is_rebate_applicable']) && $societyLedgerHeadsDetails['SocietyLedgerHeads']['is_rebate_applicable'] == 1 ? 'checked' :''; ?>> Rebate Applicable ?<br>
                                <input type="checkbox" name="ledger_heads_is_interest_free" id="ledger_heads_is_interest_free" <?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['is_interest_free']) && $societyLedgerHeadsDetails['SocietyLedgerHeads']['is_interest_free'] == 1 ? 'checked' :''; ?>> interest Free?<br>
                                <div class="modal-ledger-form-btn">
                                    <button type="button" class="btn btn-success btn-anim btns" onclick="addUpdateSocietyLedgerHeads();"><i class="icon-rocket"></i><span class="btn-text"> Update</span></button>                            
                                    <a type="button" data-toggle="tooltip" data-original-title="Close" class="btn btn-success btn-anim btns" href="javascript:void(0);" data-dismiss="modal"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                                    <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printFlatShopReports('print_ledger_heads','print_ledger_heads_section');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>  
                                </div>
                            </div> 
                        </div> 
                        <div class="row">

                        </div>
                    </div>   
                    <div class="line-break"></div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="modal-ledger-form-box-label">From<span class="required">*</span>&nbsp;</div>                                    
                            <input type="date" class="modal-ledger-form-box-field-date"  id="society_ledger_from_date" name="society_ledger_from_date" value="" />
                        </div>
                        <div class="col-md-3">
                            <div class="modal-ledger-form-box-label">To<span class="required">*</span>&nbsp;</div>                                    
                            <input type="date" class="modal-ledger-form-box-field-date" id="society_ledger_to_date" name="society_ledger_from_date" value="" />
                        </div>
                        <div class="col-md-4">
                            <div class="modal-ledger-form-box-label">Particulars<span class="required">*</span>&nbsp;</div>                                    
                            <input type="text" class="modal-ledger-form-box-field-70" id="" name="" required="">
                        </div>
                        <div class="col-md-2">
                            <div class="modal-ledger-form-box-label">Amount<span class="required">*</span>&nbsp;</div>                                    
                            <input type="text" class="modal-ledger-form-box-field-40 text-right" id="" name="" value="<?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['id']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['id'] :''; ?>" >
                        </div>
                    </div>                    
                </form>
                <div class="row">
                    <div style="padding: 8px;">
                        <div class="table-wrap">
                            <div id="print_ledger_heads">
                            <div class="print-ledger-heads">
                                <div class="societyinfo-section" id="print_ledger_heads_section">
                                     <h5 class="text-center"><?php echo isset($societyMemberLists['Society']['society_name']) ? $societyMemberLists['Society']['society_name'] : ''; ?></h5>
                                     <div class="report-address-heading"><?php echo isset($societyMemberLists['Society']['registration_no']) ? $societyMemberLists['Society']['registration_no'] : ''; ?></div>
                                     <div class="report-address-heading"><?php echo isset($societyMemberLists['Society']['address']) ? $societyMemberLists['Society']['address'] : ''; ?></div>                                                                                                           
                                     <div class="report-bill">Account Master / Ledger Heads</div>
                                     <div class="building-name"><span>Ledger Heads Name : </span><span id="print_header_section_bulding_name"><?php echo isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['title']) ? $societyLedgerHeadsDetails['SocietyLedgerHeads']['title'] :''; ?></span></div>                                     
                                </div>
                                <div class="table-responsive">
                                    <table id="datable_11" class="table table-hover table-bordered padding-th-none padding-td-none">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Particular</th>
                                                <th>Dr.Amount</th>
                                                <th>Cr.Amount</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ledger_details">
                                            <?php
                                            $openingDebit = 0;
                                            $openingCredit = 0;
                                            $openingBalance = 0;
                                            $posReg = '';
                                            ?>
                                            <tr id="">
                                                <td class="opening-bal"><?php echo $op;?></td>
                                                <td class="opening-bal">Opening Balance</td>
                                                <td class="opening-bal"></td>
                                                <td class="opening-bal"></td>
                                                <td class="opening-bal"></td>
                                            </tr>


                                            <?php if(isset($societyLedgerHeadsDetails['ledgerPaymentData']) && !empty($societyLedgerHeadsDetails['ledgerPaymentData'])) {
                                            $particularNote = 'To Payment';

                                            if(isset($societyLedgerHeadsDetails['SocietyLedgerHeads']['is_in_bill_charges']) && $societyLedgerHeadsDetails['SocietyLedgerHeads']['is_in_bill_charges'] == 1) {
                                                $particularNote = 'By Bill';
                                            }

                                            $indBalance = $openingBalance;

                                            foreach($societyLedgerHeadsDetails['ledgerPaymentData'] as $paymentData) {
                                                $credit = 0;
                                                $debit = 0;
                                                
                                                if($paymentData['txn_type'] == 'credit') {
                                                   $credit = $paymentData['amount'];
                                                   $indBalance = $indBalance+$credit;
                                                   $posNeg = 'Cr';
                                                } else if($paymentData['txn_type'] == 'debit') {
                                                   $debit = $paymentData['amount'];
                                                   $indBalance = $indBalance+$debit;
                                                   $posNeg = 'Dr';
                                                }     
                                            ?>
                                            <tr id="">
                                                <td><?php echo $paymentData['payment_date'];?></td>
                                                <td><?php echo isset($paymentData['particularNote']) ? $paymentData['particularNote']."<br>".$paymentData['notes'] : $particularNote.$paymentData['flat_no']; ?></td>
                                                <td><?php echo $debit;?></td>
                                                <td><?php echo $credit;?></td>
                                                <td><?php echo $indBalance.' '.$posNeg;?></td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                        <tfoot id="society_ledger_footer"> 
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td id="ledger_total_debit_sum" class="text-right"></td>
                                                    <td id="ledger_total_credit_sum" class="text-right"></td>
                                                    <td id="ledger_total_balance_sum" class="text-right"></td>
                                                </tr>
                                        </tfoot>
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
