<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Payments</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div class="col-md-8">  
                                <div class="row">
                                    <form class="" method="post" id="memberPaymentForm" name="memberPaymentForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                        <div class="row">
                                            <div class="col-md-3">    
                                                <div class="form-group">
                                                    <label class="control-label" for="member_name">Society Members<span class="required">*</span></label>
                                                    <div class="">
                                                        <input type="hidden" name="data[MemberPayment][id]" value="<?php echo isset($postData['MemberPayment']['id']) ? $postData['MemberPayment']['id'] :''; ?>">
                                                        <input type="hidden" name="data[bill_generated_id]" value="<?php echo isset($postData['MemberPayment']['bill_generated_id']) ? $postData['MemberPayment']['bill_generated_id'] :''; ?>">
                                                        <input type="hidden" name="data[receipt_id]" value="<?php echo isset($postData['MemberPayment']['receipt_id']) ? $postData['MemberPayment']['receipt_id'] :''; ?>">
                                                        <select class="form-control select2" id="id" name="data[MemberPayment][member_id]" onchange="getSocietyMemberPaymentDetails(this.value);" required>
                                                            <option value="">Choose  Members </option>
                                                            <optgroup label="Society Members List">
                                                            <?php
                                                            if (isset($societyMemberFlatLists) && count($societyMemberFlatLists) > 0) :
                                                                $s = 1;
                                                                foreach ($societyMemberFlatLists as $societyMemberId => $societyMemberDetailsArray) {
                                                                    foreach($societyMemberDetailsArray as $societyMemberFlatNo=>$societyMemberName) {
                                                                    if (isset($postData['MemberPayment']['member_id']) && $postData['MemberPayment']['member_id'] == $societyMemberId) {
                                                                        ?>
                                                                    <option value="<?php echo $societyMemberId; ?>" selected=""><?php echo $societyMemberName; ?></option>
                                                                                <?php } else { ?>
                                                                    <option value="<?php echo $societyMemberId; ?>"><?php echo "$societyMemberName ($societyMemberFlatNo)"; ?></option>
                                                                          <?php
                                                                        $s++;
                                                                    }
                                                                } } endif;
                                                            ?>
                                                           </optgroup>   
                                                            <optgroup label="Society Old Members List">
                                                            <?php
                                                            if (isset($societyOldMemberFlatLists) && count($societyOldMemberFlatLists) > 0) :
                                                                foreach ($societyOldMemberFlatLists as  $societyMemberDetailsArray) {
                                                                    foreach($societyMemberDetailsArray as $societyMembers) {
                                                                        $societyMemberId = $societyMembers['member_id'];
                                                                        $societyMemberName = $societyMembers['third_member'];
                                                                        $societyMemberFlatNo = $societyMembers['flat_no'];
                                                                        if($this->session->read('Auth.User.role') != 'Member'){
                                                                            if (isset($postData['MemberPayment']['member_id']) && $postData['MemberPayment']['member_id'] == $societyMemberId) {
                                                                                ?>
                                                                                    <option value="<?php echo "$societyMemberId-old"; ?>" selected=""><?php echo $societyMemberName; ?></option>
                                                                                        <?php } 
                                                                            else { ?>
                                                                                    <option value="<?php echo "$societyMemberId-old"; ?>"><?php echo "$societyMemberName ($societyMemberFlatNo)"; ?></option>
                                                                                  <?php
                                                                            }
                                                                        }
                                                                        else{
                                                                            if ($this->session->read('Auth.member.id') == $societyMemberId) {
                                                                                ?>
                                                                                <option value="<?php echo "$societyMemberId-old"; ?>" ><?php echo $societyMemberName; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    } 
                                                                } endif;
                                                            ?>
                                                           </optgroup>                                                               
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3">    
                                                <div class="form-group">
                                                    <label class="control-label" for="payment_mode">Payment Mode<span class="required">*</span></label>
                                                    <div class="">
                                                        <select class="form-control" id="payment_mode" name="data[MemberPayment][payment_mode]" required onchange="hideChequeInputFields(this.value,'society_bank_id');">
                                                            <option value="">Select payment mode</option>
                                                            <?php
                                                            if (isset($paymentModeLists) && count($paymentModeLists) > 0) :
                                                            $s = 1;
                                                            foreach ($paymentModeLists as $societyPaymentModeId => $societyPaymentModeName) {
                                                                if (isset($postData['MemberPayment']['payment_mode']) && $postData['MemberPayment']['payment_mode'] == $societyPaymentModeId) {
                                                                    ?>
                                                                    <option value="<?php echo $societyPaymentModeId; ?>" selected=""><?php echo $societyPaymentModeName; ?></option>
                                                                    <?php } else { ?>
                                                                    <option value="<?php echo $societyPaymentModeId; ?>"><?php echo $societyPaymentModeName; ?></option>
                                                                        <?php
                                                                $s++;
                                                                    }
                                                                } endif;
                                                            ?>  
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3">    
                                                <div class="form-group">
                                                    <label class="control-label" for="amount_paid">Amount Paid <span class="required">*</span></label>
                                                    <div class="">
                                                        <input type="text" class="form-control" id="amount_paid" name="data[MemberPayment][amount_paid]" value="<?php echo isset($postData['MemberPayment']['amount_paid']) ? $postData['MemberPayment']['amount_paid'] :''; ?>" placeholder="Enter amount paid" required="">
                                                    </div>                                        
                                                </div>
                                            </div>
                                            <div class="col-md-3">    
                                                <div class="form-group">
                                                    <label class="control-label" for="payment_date">Payment Date<span class="required">*</span></label>
                                                    <div class="">
                                                        <input type="date" class="form-control" id="payment_date" name="data[MemberPayment][payment_date]"  min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>" value="<?php echo isset($postData['MemberPayment']['payment_date']) ? $postData['MemberPayment']['payment_date'] :$financialStartEndDate['firstDate']; ?>" required="">
                                                    </div>     
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="row">  
                                            <div class="col-md-3">    
                                                <div class="form-group">
                                                    <label class="control-label" for="society_bank_id">Society Bank<span class="required">*</span></label>
                                                    <div class="">
                                                        <select class="form-control" id="society_bank_id" name="data[MemberPayment][society_bank_id]" required>
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                            if (isset($postData['MemberPayment']['payment_mode']) && $postData['MemberPayment']['payment_mode'] == Configure::read('PaymentMode.Cash')) {
                                                                if (isset($societyCashBalanceHeadsLists) && count($societyCashBalanceHeadsLists) > 0):
                                                                    $s = 1;
                                                                    foreach ($societyCashBalanceHeadsLists as $societyBankId => $societyBankName) {
                                                                        if (isset($postData['MemberPayment']['society_bank_id']) && $postData['MemberPayment']['society_bank_id'] == $societyBankId) {
                                                                            ?>
                                                                            <option value="<?php echo $societyBankId; ?>" selected=""><?php echo $societyBankName; ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $societyBankId; ?>"><?php echo $societyBankName; ?></option>
                                                                            <?php
                                                                            $s++;
                                                                        }
                                                                    }
                                                                endif;
                                                            }
                                                            ?>
                                                            <?php
                                                            if (isset($postData['MemberPayment']['payment_mode']) && $postData['MemberPayment']['payment_mode'] != Configure::read('PaymentMode.Cash')) {
                                                                if (isset($societyBankBalanceHeadsLists) && count($societyBankBalanceHeadsLists) > 0):
                                                                    $s = 1;
                                                                    foreach ($societyBankBalanceHeadsLists as $societyBankId => $societyBankName) {
                                                                        if (isset($postData['MemberPayment']['society_bank_id']) && $postData['MemberPayment']['society_bank_id'] == $societyBankId) {
                                                                            ?>
                                                                            <option value="<?php echo $societyBankId; ?>" selected=""><?php echo $societyBankName; ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $societyBankId; ?>"><?php echo $societyBankName; ?></option>
                                                                            <?php
                                                                            $s++;
                                                                        }
                                                                    }
                                                                endif;
                                                            }
                                                            ?>
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3 cheque_hide">    
                                                <div class="form-group">
                                                    <label class="control-label" for="cheque_reference_number">Reference Number<span class="required">*</span></label>
                                                    <div class="">
                                                        <input type="text" class="form-control" id="cheque_reference_number" name="data[MemberPayment][cheque_reference_number]" value="<?php echo isset($postData['MemberPayment']['cheque_reference_number']) ? $postData['MemberPayment']['cheque_reference_number'] :''; ?>" placeholder="Enter cheque reference number">
                                                    </div>     
                                                </div>
                                            </div> 
                                            <div class="col-md-3 cheque_hide">    
                                                <div class="form-group">
                                                    <label class="control-label" for="cheque_date">Cheque Date</label>
                                                    <div class="">
                                                        <input type="date" class="form-control" id="credited_date" name="data[MemberPayment][cheque_date]" min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>" value="<?php echo isset($postData['MemberPayment']['cheque_date']) ? $postData['MemberPayment']['cheque_date'] :''; ?>">
                                                    </div>     
                                                </div>
                                            </div>  
                                            <div class="col-md-3 cheque_hide">    
                                                <div class="form-group">
                                                    <label class="control-label" for="credited_date">Cleared On</label>
                                                    <div class="">
                                                        <input type="date" class="form-control" id="credited_date" name="data[MemberPayment][credited_date]" min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>" value="<?php echo isset($postData['MemberPayment']['credited_date']) ? $postData['MemberPayment']['credited_date'] :''; ?>">
                                                    </div>     
                                                </div>
                                            </div>    
                                            <div class="col-md-3 cheque_hide">    
                                                <div class="form-group">
                                                    <label class="control-label" for="bank_slip_no">Bank Slip No<span class="required">*</span></label>
                                                    <div class="">
                                                        <input type="text" class="form-control" id="bank_slip_no" name="data[MemberPayment][bank_slip_no]" value="<?php echo isset($postData['MemberPayment']['bank_slip_no']) ? $postData['MemberPayment']['bank_slip_no'] :''; ?>">
                                                    </div>     
                                                </div>
                                            </div>
                                            <?php if (isset($postData['MemberPayment']['member_id']) && $postData['MemberPayment']['payment_mode'] == "3")  {?>
                                                <div class="col-md-3">    
                                                    <div class="form-group">
                                                        <label class="control-label" for="cheque_return_date">Cheque Return Date
                                                        </label>
                                                        <div class="">
                                                            <input type="date" class="form-control" id="cheque_return_date" name="data[MemberPayment][cheque_return_date]" min="<?= $financialYearStartDate; ?>" max="<?= $financialYearEndDate; ?>" value="<?php echo isset($postData['MemberPayment']['cheque_return_date']) ? $postData['MemberPayment']['cheque_return_date'] :''; ?>">
                                                        </div>     
                                                    </div>
                                                </div>

                                                <div class="col-md-3">    
                                                    <div class="form-group">
                                                        <label class="control-label" for="cheque_return_reason">Cheque Return Reason</label>
                                                        <div class="">
                                                            <input type="text" class="form-control" id="cheque_return_reason" name="data[MemberPayment][cheque_return_reason]" value="<?php echo isset($postData['MemberPayment']['cheque_return_reason']) ? $postData['MemberPayment']['cheque_return_reason'] :''; ?>">
                                                        </div>     
                                                    </div>
                                                </div>
                                            <?php }?>    
                                        </div> 
                                        <div class="cheque_hide" id="member-bank-details">Member Bank Details</div>                                      
                                        <div class="row">
                                            <div class="col-md-3 cheque_hide">    
                                                <div class="form-group">
                                                    <label class="control-label" for="member_bank_id">Bank Name<span class="required">*</span></label>
                                                    <div class="">
                                                         <select class="form-control" id="member_bank_id" name="data[MemberPayment][member_bank_id]">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                            if (isset($bankLists) && count($bankLists) > 0) :
                                                            $s = 1;
                                                            foreach ($bankLists as $societyBankId => $societyBankName) {
                                                                if (isset($postData['MemberPayment']['member_bank_id']) && $postData['MemberPayment']['member_bank_id'] == $societyBankId) {
                                                                    ?>
                                                            <option value="<?php echo $societyBankId; ?>" selected=""><?php echo $societyBankName; ?></option>
                                                            <?php } else { ?>
                                                            <option value="<?php echo $societyBankId; ?>"><?php echo $societyBankName; ?></option>
                                                                <?php
                                                                $s++;
                                                                    }
                                                                } endif;
                                                            ?>  
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3 cheque_hide">    
                                                <div class="form-group">
                                                    <label class="control-label" for="member_bank_ifsc">IFSC Code<span class="required">*</span></label>
                                                    <div class="">
                                                        <input type="text" class="form-control" id="member_bank_ifsc" name="data[MemberPayment][member_bank_ifsc]" value="<?php echo isset($postData['MemberPayment']['member_bank_ifsc']) ? $postData['MemberPayment']['member_bank_ifsc'] :''; ?>">
                                                    </div>     
                                                </div>
                                            </div>   
                                            <div class="col-md-3 cheque_hide">    
                                                <div class="form-group">
                                                    <label class="control-label" for="member_bank_branch">Branch<span class="required">*</span></label>
                                                    <div class="branch-textarea">
                                                        <textarea rows="2" type="text" class="form-control" id="member_bank_branch" name="data[MemberPayment][member_bank_branch]" value="<?php echo isset($postData['MemberPayment']['member_bank_branch']) ? $postData['MemberPayment']['member_bank_branch'] :''; ?>"><?php echo isset($postData['MemberPayment']['member_bank_branch']) ? $postData['MemberPayment']['member_bank_branch'] :''; ?></textarea>
                                                    </div>     
                                                </div>
                                            </div>
                                            <div class="col-md-3">    
                                                <div class="form-group">
                                                    <label class="control-label" for="member_bank_branch">Narration<span class="required">*</span></label>
                                                    <div class="branch-textarea">
                                                        <textarea rows="2" type="text" class="form-control" id="narration" name="data[MemberPayment][narration]" value="<?php echo isset($postData['MemberPayment']['member_bank_branch']) ? $postData['MemberPayment']['member_bank_branch'] :''; ?>"><?php echo isset($postData['MemberPayment']['narration']) ? $postData['MemberPayment']['narration'] :''; ?></textarea>
                                                    </div>     
                                                </div>
                                            </div>                                                
                                            <div class="col-md-3">    
                                                <div class="form-group">
                                                    <label class="control-label" for="member_bank_id">Bill Type<span class="required">*</span></label>
                                                    <div class="">
                                                        <select class="form-control" name="data[MemberPayment][bill_type]" required="">
                                                            <option value="">Select Rate/Unit</option>                                           
                                                            <option <?php echo isset($postData['MemberPayment']['bill_type']) && $postData['MemberPayment']['bill_type'] == "sup" ? "selected":''; ?> value="sup">Supplementary</option>                                           
                                                            <option <?php echo isset($postData['MemberPayment']['bill_type']) && $postData['MemberPayment']['bill_type'] == "reg" ? "selected":'selected'; ?> value="reg">Regular</option>                                           
                                                          </select>  
                                                    </div>
                                                </div>
                                            </div> 
                                        </div> 
                                        <div class="form-group mb-0">
                                            <div class="col-sm-offset-2 col-sm-10"> 
                                                <button type="submit" data-toggle="tooltip" data-original-title="Add / Update member payment details" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($postData['MemberPayment']['id']) && !empty($postData['MemberPayment']['id'])) { ?>Update Member payment Details<?php }else{ ?> Add Member payment Details <?php } ?></span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys_members','action' =>'member_payments')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>
                                    </form>
                                </div>    
                            </div> 

                            <div class="col-md-4">
                                <?php if (isset($postData['MemberPayment']['member_id']) && $postData['MemberPayment']['member_id'] != ''){?>
                                <div class="row">
                                       <a type="button" data-toggle="tooltip" data-original-title="Go to member payment list" class="btn btn-primary btn-anim pull-right" href="<?php echo Router::url(array('controller' =>'societys_members','action' =>'member_payments')); ?>"><i class="icon-rocket"></i><span class="btn-text">Make New Payment</span></a>
                                 </div>
                                <?php } ?>
                                <div class="row">   
                                    <form class="form-horizontal" method="post" id="" name="memberPaymentOutstandingForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                        <div class="outstanding-pay-box">
                                             <!--<div class="outstanding-title">Outstanding Amount</div>-->
                                            <div class="row">
                                                <div class="col-md-12">    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5" for="total_outstanding">Total Outstanding</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control text-right" id="member_outstanding_payment" name="" value="" placeholder="" required="">
                                                        </div>     
                                                    </div>
                                                </div>    
                                            </div> 
                                            <div class="row">
                                                <div class="col-md-12">    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5" for="member_interest">Interest</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control text-right" id="member_interest" name="" value="" placeholder="" required="">
                                                        </div>     
                                                    </div>
                                                </div>    
                                            </div> 
                                            <div class="row">
                                                <div class="col-md-12">    
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5" for="credited_date">Principle</label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control text-right" id="member_principle" name="" value="" placeholder="" required="">
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
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="t_member_payment" class="dataTable table table-hover table-bordered padding-th-none padding-td-none no-footer">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bill No</th>
                                        <th width="10%">Date</th>
                                        <th>Amount Paid</th>
                                        <th>Society Bank</th>                                            
                                        <th>Payment Mode</th>                                           
                                        <th>Ref. No</th>
                                        <th>Bill Type</th>
                                        <th>Edit</th>
                                        <th>Delete</th> 
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/jquery.min.js"></script>
<script>
    var societyMemberPaymentID = 0;
    <?php if (isset($postData['MemberPayment']['member_id']) && $postData['MemberPayment']['member_id'] != ''){?>
          societyMemberPaymentID = <?php echo $postData['MemberPayment']['member_id']; ?>;
    <?php }?> 
    
    $(document).ready(function() {
		$("#id").select2();
	});
    
</script>    
