<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            	<?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Bank Deposit/Contra/Withdraw</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form  method="post" id="cashWithdrawsForm" name="cashWithdrawsForm" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3 padding-1">
                                        <div class="form-group">
                                            <label for="txn_type" class="control-label">Type<span class="required">*</span></label>
                                            <select name="data[CashWithdraw][txn_type]" id="txn_type" class="form-control input-group-lg" onchange="chkTxnType();">
                                                <option value="">Select Type</option>
                                                <option value="contra" <?php if(isset($this->request->data['CashWithdraw']['txn_type']) && $this->request->data['CashWithdraw']['txn_type'] == 'contra') { echo 'selected';} ?>>Contra</option>
                                                <option value="deposit" <?php if(isset($this->request->data['CashWithdraw']['txn_type']) && $this->request->data['CashWithdraw']['txn_type'] == 'deposit') { echo 'selected';} ?>>Deposit</option>
                                                <option value="withdraw" <?php if(isset($this->request->data['CashWithdraw']['txn_type']) && $this->request->data['CashWithdraw']['txn_type'] == 'withdraw') { echo 'selected';} ?>>Withdraw</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 padding-1">
                                        <div class="form-group">
                                            <label for="payment_date" class="control-label">Date<span class="required">*</span></label>
                                            <input type="date" class="form-control" id="payment_date" name="data[CashWithdraw][payment_date]" min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>" value="<?php echo isset($this->request->data['CashWithdraw']['payment_date']) ? $this->request->data['CashWithdraw']['payment_date'] :''; ?>" required="">
                                        </div>
                                    </div>                                    
                                    <div class="col-md-3 padding-1">
                                        <div class="form-group">
                                           <label for="" class="control-label">Bank<span class="required">*</span></label>
                                           <select name="data[CashWithdraw][bank_ledger_head_id]" id="bank_ledger_head_id" class="form-control input-group-lg">
                                              <?php
                                              echo "<option value=\"\">Select Bank</option>";
                                                    if (isset($societyBankBalanceHeadsLists) && count($societyBankBalanceHeadsLists) > 0) :
                                                    $s = 1;
                                                    foreach ($societyBankBalanceHeadsLists as $societyLedgerHeadsId => $societyLedgerHeadsTitle) {
                                                        if (isset($this->request->data['CashWithdraw']['bank_ledger_head_id']) && $this->request->data['CashWithdraw']['bank_ledger_head_id'] == $societyLedgerHeadsId) {
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
                                    </div>
                                    <div class="col-md-3 padding-1" id="divContra" <?php if(isset($this->request->data['CashWithdraw']['txn_type']) && $this->request->data['CashWithdraw']['txn_type'] == 'contra') { echo 'style="display:block;"';} else { echo 'style="display:none;"';} ?>>
                                        <div class="form-group">
                                           <label for="" class="control-label">Transfer to Bank<span class="required">*</span></label>
                                           <select name="data[CashWithdraw][bank_to_ledger_head_id]" id="bank_to_ledger_head_id" class="form-control input-group-lg">
                                              <?php
                                              echo "<option value=\"\">Select Bank</option>";
                                                    if (isset($societyBankBalanceHeadsLists) && count($societyBankBalanceHeadsLists) > 0) :
                                                    $s = 1;
                                                    foreach ($societyBankBalanceHeadsLists as $societyLedgerHeadsId => $societyLedgerHeadsTitle) {
                                                        if (isset($this->request->data['CashWithdraw']['bank_to_ledger_head_id']) && $this->request->data['CashWithdraw']['bank_to_ledger_head_id'] == $societyLedgerHeadsId) {
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
                                    </div>
                                    <div class="col-md-3 padding-1">
                                        <div class="form-group">
                                            <label for="" class="control-label">Amount<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="amount" name="data[CashWithdraw][amount]" value="<?php echo isset($this->request->data['CashWithdraw']['amount']) ? $this->request->data['CashWithdraw']['amount'] :''; ?>" placeholder="Enter Amount">
                                        </div>
                                    </div>
                                    <div class="so-check col-md-3 padding-1">
                                        <div class="form-group">
                                            <label for="" class="control-label">Cheque No </label>
                                            <input type="text" class="form-control" id="cheque_no" name="data[CashWithdraw][cheque_no]" value="<?php echo isset($this->request->data['CashWithdraw']['cheque_no']) ? $this->request->data['CashWithdraw']['cheque_no'] :''; ?>" placeholder="Enter Cheque No.">
                                        </div>
                                    </div>
                                    <div class="col-md-3 padding-1">
                                        <div class="form-group">
                                            <label for="" class="control-label">Particulars</label>
                                            <input type="text" class="form-control" id="particulars" name="data[CashWithdraw][particulars]" value="<?php echo isset($this->request->data['CashWithdraw']['particulars']) ? $this->request->data['CashWithdraw']['particulars'] :''; ?>" placeholder="Enter particulars">
                                        </div>
                                    </div>
                                    <div class="col-md-3 padding-1">
                                        <div class="form-group">
                                            <label for="" class="control-label">Narration</label>
                                            <input type="text" class="form-control" id="narration" name="data[CashWithdraw][narration]" value="<?php echo isset($this->request->data['CashWithdraw']['narration']) ? $this->request->data['CashWithdraw']['narration'] :''; ?>" placeholder="Enter narration">
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group mb-0 padding-0">
                                     <div class="col-sm-offset-2 col-sm-10"> 
                                        <button type="submit" data-toggle="tooltip" data-original-title="Submit" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text">Submit</span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to cash withdraws" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'cash_withdraws')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
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

<script>
    function chkTxnType() {
        if($('#txn_type').val() == 'contra') {
           $('#divContra').css('display','block'); 
        } else {
           $('#divContra').css('display','none');  
        }
    }
</script>
