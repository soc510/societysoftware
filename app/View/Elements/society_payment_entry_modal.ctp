<!-- Modal for Payment entry  -->
<div class="modal fade" id="payment_entry_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title modal-title-color">Payment Entry</h6>
            </div>
            <div class="modal-body modal-body-bg">                
             <form class="society_payments_entry" method="post" id="society_payments_entry" name="society_payments_entry" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
               <div class="modal-society-payment-form-box">
                    <div class="row1">  
                            <div class="field-box-1">
                                <div class="modal-form-pay-entry-box-label">Date<span class="required">*</span>&nbsp;</div> 
                                <input type="date" class="modal-form-pay-entry-box-date"  id="society_entry_date" name="data[SocietyPayment][payment_date]" value="" required>
                            </div>
                            <div class="field-box-2">
                                <div class="modal-form-pay-entry-box-label">By<span class="required">*</span>&nbsp;</div> 
                                <select class="modal-society-payment-field-dropdown-70" id="society_payment_by_ledger" name="data[SocietyPayment][payment_by_ledger_id]" required>
                                    <option value="">Select Vendor</option>
                                      <?php
                                            if (isset($societyBankBalanceHeadsLists) && count($societyBankBalanceHeadsLists) > 0) :
                                            $s = 1;
                                            foreach ($societyBankBalanceHeadsLists as $societyLedgerHeadsId => $societyLedgerHeadsTitle) {
                                         if (isset($this->request->data['SocietyPayment']['payment_by_ledger_id']) && $this->request->data['SocietyPayment']['payment_by_ledger_id'] == $societyLedgerHeadsId) {
                                                    ?>
                                        <option value="<?php echo $societyLedgerHeadsId; ?>" selected=""><?php echo $societyLedgerHeadsTitle; ?></option>
                                            <?php } else { ?>
                                        <option value="<?php echo $societyLedgerHeadsId; ?>"><?php echo $societyLedgerHeadsTitle; ?></option>
                                            <?php
                                            $s++;
                                                }
                                            } endif;
                                      ?>  
                               </select>
                            </div>
                            <div class="field-box-2">
                                <div class="modal-form-pay-entry-box-label">Type<span class="required">*</span>&nbsp;</div> 
                                <select name="data[SocietyPayment][payment_type]" id="payment_type" class="modal-society-payment-field-dropdown-60" onchange="hideChequeDetailsFields(this.value);" required>
                                        <option value="">Select payment mode</option>    
                                        <option value="Bank" <?php if(isset($this->request->data['SocietyPayment']['payment_type']) && $this->request->data['SocietyPayment']['payment_type'] == 'Bank') { echo 'selected';}?>>Bank</option>   
                                        <option value="Cash" <?php if(isset($this->request->data['SocietyPayment']['payment_type']) && $this->request->data['SocietyPayment']['payment_type'] == 'Cash') { echo 'selected';}?>>Cash</option>   
                                    </select>
                            </div>
                            <!--<div class="field-box-3">
                                <div class="modal-form-pay-entry-box-label">Balance &nbsp;</div> 
                                <div class="numeric-field">5000</div>
                            </div>
                            <div class="field-box-3">
                                <div class="modal-form-pay-entry-box-label">Less(-)&nbsp;</div> 
                                <div class="numeric-field-65">25000</div>
                            </div>
                            <div class="field-box-3">
                                <div class="modal-form-pay-entry-box-label">Total(=)&nbsp;</div> 
                                <div class="numeric-field">25000</div>
                            </div>-->
                           <!-- <br><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="">Show Particulars
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="">Multiple Date
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="">Duplicate Date
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="">Multiple Cr.
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="">Clear Date
                                </label>    -->   
                                <br><br>                     
				<div class="col-md-8 nopadding">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="generate_one_voucher" value="Y">Below Payment(s) To Be Created A Single Voucher
                                </label>
                             </div>
                            <div class="col-md-4 nopadding modal-society-payment-form-btn">
                             <button type="button" onclick="saveSocietyPaymentReciepts();" class="btn btn-success btn-anim btn-sm btns">Save Society Payment</button>
                            <button type="button" class="btn btn-warning btn-anim btn-sm btns" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                <div class="clearfix"></div>
                <div class="row">
                <div style="padding: 8px;">
                        <div class="table-wrap">
                                <div class="table-responsive">
                                        <table id="datable_1" class="table table-hover table-bordered padding-th-none padding-td-none" >
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Paid to</th>
                                                        <th>Amount</th>
                                                        <th>Payment Date</th>
                                                        <th class="hide_content">ChequeNo</th>
                                                        <th class="hide_content">Cheque Date</th>
                                                        <th>Particulars</th>
                                                        <th>Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                <?php for($i=1;$i<10;$i++){?>
                                                        <tr id="<?php echo $i;?>">
                                                                <td><?php echo $i;?></td>
                                                                <td>
                                                                    <select id="society_bank_id form-control" name="data[SocietyPayments][<?php echo $i; ?>][ledger_head_id]">
                                                                            <option value="">Select Ledger</option>
                                                                                    <?php
                                                                                    if (isset($societyExpenseLedgerHeadsLists) && count($societyExpenseLedgerHeadsLists) > 0) :
                                                                                    $s = 1;
                                                                                    foreach ($societyExpenseLedgerHeadsLists as $societyLedgerHeadsId => $societyLedgerHeadsTitle) {
                                                                                            if (isset($this->request->data['SocietyPayment']['ledger_head_id']) && $this->request->data['SocietyPayment']['ledger_head_id'] == $societyLedgerHeadsId) {
                                                                                                    ?>
                                                                                            <option value="<?php echo $societyLedgerHeadsId; ?>" selected=""><?php echo $societyLedgerHeadsTitle; ?></option>
                                                                                                    <?php } else { ?>
                                                                                            <option value="<?php echo $societyLedgerHeadsId; ?>"><?php echo $societyLedgerHeadsTitle; ?></option>
                                                                                                    <?php
                                                                                             $s++;
                                                                                            }
                                                                                    } endif;
                                                                            ?> 
                                                                    </select>
                                                                    <div style='float:left;font-size:10px;margin-top: 2px'><input type='checkbox' style='position: relative;vertical-align: bottom;' name="tax_deduction" value='y' id='is_tax_applicable_<?php echo $i?>'>Is tax applicable?</div>
                                                                </td>
                                                                <td><input type="text" class="numeric td-editable  numeric-field" id="amount_paid" name="data[SocietyPayments][<?php echo $i; ?>][amount]" data-parsley-type="digits">

                                                                <div style='margin-top:4px;float:left;display: none' id='tax_<?php echo $i?>'>
                                                                <input type="text"  name="data[SocietyPayments][<?php echo $i; ?>][tax_amount]" class="numeric numeric-field"></div>
                                                                </td>
                                                                <td><input type="date" class="td-editable form-control" id="payment_date" name="data[SocietyPayments][<?php echo $i; ?>][payment_date]" ></td>
                                                                <td class="hide_content"><input type="text" class="numeric td-editable  numeric-field" id="cheque_reference_number" name="data[SocietyPayments][<?php echo $i; ?>][cheque_reference_number]" data-parsley-type="number"></td>
                                                                <td class="hide_content"><input type="date" class="td-editable form-control" id="credited_date" name="data[SocietyPayments][<?php echo $i; ?>][cheque_date]" value=""></td>
                                                                <td><input type="text" class="td-editable form-control" id="particulars" name="data[SocietyPayments][<?php echo $i; ?>][particulars]"></td>
                                                                <td><input type="text" class="td-editable form-control" id="particulars" name="data[SocietyPayments][<?php echo $i; ?>][notes]"></td>
                                                              </tr>
                                                  <?php } ?>
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                    </div>
                </div>	
             </div>
            </form> 
            </div>
        </div>
    </div>
</div>