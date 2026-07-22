<!-- Modal for society Ledger Head -->
<?php 
error_reporting(0);
?>
<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close"><a href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>">&times;</a></button>
                <h6 class="modal-title modal-title-color">Transactions</h6>
            </div>
            <!--<ul class="pager">
                 <li class="previous"><a class="btn btn-warning btn-rounded" id="ledger_head_previous_button" href="javascript:void(0);" onclick="societyLedgerHeadPrevious();" disabled>Previous</a></li>
                 <li class="next"><a class="btn btn-warning btn-rounded" id="ledger_head_next_button" href="javascript:void(0);" onclick="societyLedgerHeadNext();">Next</a></li>
             </ul>-->
			 
        
            <div class="modal-body modal-body-bg">
                
                <form method="post" id="societyAddLedgerHeadsForm" name="societyAddLedgerHeadsForm"  data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                    <div class="modal-ledger-form-box" style='display:none'>
                        <div class="row">                            
                            <div class="col-md-10">
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Particulars<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
<input type="hidden" class="modal-ledger-form-box-field-85" id="LedgerHeadsTitle" name="data[SocietyLedgerHeads][title]" value="<?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['title']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['title'] :''; ?>"required="">

<select class="modal-ledger-form-box-field-dropdown" id="LedgerHeadsId" name="data[SocietyLedgerHeads][id]" onchange="getSocietyLedgerHeadDetails(this.value);" required="">
                <option value="">Select</option>
                                        <?php
                                         if (isset($societyLedgerHeadsLists) && count($societyLedgerHeadsLists) > 0) :
                                                        $s = 1;
                                                        foreach ($societyLedgerHeadsLists as $inc => $societyLedgerHead) {
                                if (isset($ledgerTransactionDetails['SocietyLedgerHeads']['id']) && $ledgerTransactionDetails['SocietyLedgerHeads']['id'] == $societyLedgerHead['SocietyLedgerHeads']['id']) {
                                                                ?>
                                                    <option value="<?php echo $societyLedgerHead['SocietyLedgerHeads']['id']; ?>" selected=""><?php echo $societyLedgerHead['SocietyLedgerHeads']['title']; ?></option>
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
                                    <input type="hidden" id="ledger_head_previous" name="ledger_head_previous" value="<?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['id']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['id'] :0; ?>">
                                    <input type="hidden" id="ledger_head_next" name="ledger_head_next" value="<?php echo isset($societyLedgerHeadsNextRecord['SocietyLedgerHeads']['id']) ? $societyLedgerHeadsNextRecord['SocietyLedgerHeads']['id'] :0; ?>">
                                    <input type="text" class="modal-ledger-form-box-field-60 text-right readonly" id="serial_no" name="data[SocietyLedgerHeads][id]" value="<?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['id']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['id'] :''; ?>" readonly>
                                </div>
                            </div>
                        </div>                              
                        <div class="row" style='display:none'>                            
                            <div class="col-md-4">
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Short Code<span class="required">*</span>&nbsp;&nbsp;&nbsp;</div>                                    
                                    <input type="text" id="short_code" class="modal-ledger-form-box-field-50"name="data[SocietyLedgerHeads][short_code]" value="<?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['short_code']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['short_code'] :''; ?>"required=""> 
                                </div> 
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Subgroup<span class="required">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                                                       
                                    <select class="modal-ledger-form-box-field-dropdown" id="society_head_sub_category" name="data[SocietyLedgerHeads][society_head_sub_category_id]">
                                        <option value="<?php echo $ledgerTransactionDetails['SocietyHeadSubCategory']['id']; ?>" selected><?php echo $ledgerTransactionDetails['SocietyHeadSubCategory']['title']; ?></option>  
                                    </select>
                                </div>
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Group<span class="required">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>  
                                    <select class="modal-ledger-form-box-field-dropdown" id="AccountHeadsCategoryID" name="data[SocietyLedgerHeads][account_head_id]" required="">
                                        <option value="<?php echo $ledgerTransactionDetails['AccountHead']['id']; ?>" selected><?php echo $ledgerTransactionDetails['AccountHead']['title']; ?></option>
                                    </select>     
                                </div>
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Category<span class="required">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>                                                                     
                                    <select class="modal-ledger-form-box-field-dropdown" id="AccountCategoryID" name="data[SocietyLedgerHeads][account_category_id]" onchange="getAccountHeads(this.value, '#AccountHeadsCategoryID');" required="">
                                        <option value="">Select Account Category</option>
                                              <?php if(isset($societyAccountCategoryLists) && count($societyAccountCategoryLists) > 0) {
                                              foreach($societyAccountCategoryLists as $accountCategoryId => $accountCategoryName){?>
                                              <?php if(isset($ledgerTransactionDetails['SocietyLedgerHeads']['account_category_id']) && $ledgerTransactionDetails['SocietyLedgerHeads']['account_category_id'] == $accountCategoryId){?>
                                        <option value="<?php echo $accountCategoryId; ?>" selected><?php echo $accountCategoryName; ?></option>
                                              <?php }else {?>
                                        <option value="<?php echo $accountCategoryId; ?>"><?php echo $accountCategoryName; ?></option>
                                              <?php }?>
                                              <?php } }?>
                                    </select> 
                                </div>
                            </div>                    
                            <div class="col-md-4" style='display:none'>
                                <div class="modal-ledger-form-field-box">
                                    <div class="modal-ledger-form-box-label">Opening Balance <span class="required">*</span>&nbsp;</div>                                    
                                    <input type="text" class="modal-ledger-form-box-field-50 text-right" id="op_balance" name="data[SocietyLedgerHeads][opening_amount]" value="<?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['opening_amount']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['opening_amount'] :''; ?>" placeholder="" required="">
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
                                    <input type="text" class="modal-ledger-form-box-field-50 text-right" id="" name="" value="<?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['id']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['id'] :''; ?>" placeholder="Closing Balance">
                                </div>-->
                            </div>       
                            <div class="col-md-4" style='display:none'>
                                <div class="modal-ledger-tariff-head">Bill Tariff Details</div>  
                                <input type="checkbox" name="ledger_heads_is_in_bill_charges" id="ledger_heads_is_in_bill_charges" <?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['is_in_bill_charges']) && $ledgerTransactionDetails['SocietyLedgerHeads']['is_in_bill_charges'] == 1 ? 'checked' :''; ?> > Is this account  a part of Bill Charges<br>
                                <input type="checkbox" name="ledger_heads_is_tax_applicable" id="ledger_heads_is_tax_applicable" <?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['is_tax_applicable']) && $ledgerTransactionDetails['SocietyLedgerHeads']['is_tax_applicable'] == 1 ? 'checked' :''; ?>> Service Tax/GST Applicable ?<br>
                                <input type="checkbox" name="ledger_heads_is_rebate_applicable" id="ledger_heads_is_rebate_applicable" <?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['is_rebate_applicable']) && $ledgerTransactionDetails['SocietyLedgerHeads']['is_rebate_applicable'] == 1 ? 'checked' :''; ?>> Rebate Applicable ?<br>
                                <input type="checkbox" name="ledger_heads_is_interest_free" id="ledger_heads_is_interest_free" <?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['is_interest_free']) && $ledgerTransactionDetails['SocietyLedgerHeads']['is_interest_free'] == 1 ? 'checked' :''; ?>> interest Free?<br>
                                <div class="modal-ledger-form-btn">
                                    <button type="button" class="btn btn-success btn-anim btns" onclick="addUpdateSocietyLedgerHeads();"><i class="icon-rocket"></i><span class="btn-text"> Update</span></button>                            
                                    <?php /* This view is opened by the balance sheet with window.open(), so the
                                             only way out was a Cancel that navigated the popup to the dashboard -
                                             leaving a stray window sitting behind the report. Closes itself when
                                             it is a popup, and falls back to the dashboard link when it was
                                             reached as a normal page. */ ?>
                                    <a type="button" data-toggle="tooltip" data-original-title="Close" class="btn btn-success btn-anim btns" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>" onclick="if (window.opener &amp;&amp; !window.opener.closed) { window.close(); return false; }"><i class="icon-rocket"></i><span class="btn-text">Close</span></a>
                                    <a type="button" data-toggle="tooltip" data-original-title="Print Friendly" class="btn btn-success btn-anim btns" href="javascript:void(0);" onclick="printFlatShopReports('print_ledger_heads','print_ledger_heads_section');"><i class="fa fa-print"></i><span class="btn-text">Print Friendly</span></a>  
                                </div>
                            </div> 
                        </div> 
                        <div class="row">

                        </div>
                    </div>   
                    
                    <div class="row" style='display:none'>
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
                            <input type="text" class="modal-ledger-form-box-field-40 text-right" id="" name="" value="<?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['id']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['id'] :''; ?>" >
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
                                     <div class="building-name"><span>Ledger Heads Name : </span><span id="print_header_section_bulding_name"><?php echo isset($ledgerTransactionDetails['SocietyLedgerHeads']['title']) ? $ledgerTransactionDetails['SocietyLedgerHeads']['title'] :''; ?></span></div>                                     
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
                                            if(isset($ledgerTransactionDetails['txn_type']) && $ledgerTransactionDetails['txn_type'] == 'debit') {
                                                $openingDebit = $ledgerTransactionDetails['SocietyLedgerHeads']['opening_amount'];
                                                $openingCredit = 0;
                                                $openingBalance = $openingDebit;
                                                $posReg = ' Dr.';
                                            } else if(isset($ledgerTransactionDetails['txn_type']) && $ledgerTransactionDetails['txn_type'] == 'credit') {
                                                $openingCredit = $ledgerTransactionDetails['SocietyLedgerHeads']['opening_amount'];
                                                $openingDebit = 0;
                                                $openingBalance = $openingCredit;
                                                $posReg = ' Cr.';
                                            }
                                            ?>
                                            <tr id="">
                                                <td class="opening-bal">01/04/2020</td>
                                                <td class="opening-bal">Opening Balance</td>
                                                <td class="opening-bal"><?php echo $openingDebit;?></td>
                                                <td class="opening-bal"><?php echo $openingCredit;?></td>
                                                <td class="opening-bal"><?php echo $openingBalance.''.$posReg;?></td>
                                            </tr>

                                            
                                            <?php if(isset($ledgerTransactionDetails['ledgerPaymentData'])) {
                                            $particularNote = 'To Payment';
                                            
                                            if($ledgerTransactionDetails['SocietyLedgerHeads']['is_in_bill_charges'] == 1) {
                                                $particularNote = 'By Bill';
                                            }
                                            
                                            $indBalance = $openingBalance;

                                            foreach($ledgerTransactionDetails['ledgerPaymentData'] as $paymentData) {
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
                                                <td><?php echo isset($paymentData['particularNote']) ? $paymentData['particularNote'] : $particularNote; ?></td>
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

<script type='text/javascript'>
jQuery(document).ready(function(){

alert(1);
var totalDebit = $('#ledger_total_debit_sum').html();
                    var totalCredit = $('#ledger_total_credit_sum').html();

                    var totalBalance = ((totalDebit * 1) - (totalCredit * 1));
                    var checkNegativePositive = checkNegativePositiveValue(totalBalance);
                    totalBalance = Math.abs(totalBalance);

                    $('#ledger_total_balance_sum').html(totalBalance.toFixed(2) + ' '+checkNegativePositive);

});
</script>
