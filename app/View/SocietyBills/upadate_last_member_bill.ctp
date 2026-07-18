<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Update Generated Bill</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <form id="society_member_bill_summary" method="post" name="society_member_bill_summary" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                            <div class="row">
                                <div class="col-md-2 padding-1">
                                    <div class="form-group">
                                        <label for="" class="control-label">Bill Month.<span class="required">*</span></label>
                                        <select name="data[UpdateMemberBillSummary][month]" id="member_bill_summary_month" class="form-control" required="">
                                            <option value="">Select Month</option>
                                            <?php if(isset($billingMonthArray) && count($billingMonthArray) > 0) {
                                            foreach($billingMonthArray as $MonthID => $MonthName){?>
                                                <?php if (isset($lastBillGeneratedData['UpdateMemberBillSummary']['month']) && $lastBillGeneratedData['UpdateMemberBillSummary']['month']  == $MonthID) { ?> 
                                            <option value="<?php echo $MonthID; ?>" selected=""><?php echo $MonthName; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $MonthID; ?>"><?php echo $MonthName; ?></option>
                                                <?php } ?>
                                            <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 padding-0">
                                    <div class="form-group">
                                        <input type="hidden" name="data[UpdateMemberBillSummary][id]" value="<?php echo isset($lastBillGeneratedData['UpdateMemberBillSummary']['id']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['id'] : ''; ?>">
                                        <label for="" class="control-label">Bill No.<span class="required">*</span></label>
                                        <input type="text" id="edit_member_bill_summary_bill_no" name="data[UpdateMemberBillSummary][bill_no]"  class="form-control input-height-30" value="<?php echo isset($lastBillGeneratedData['UpdateMemberBillSummary']['bill_no']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['bill_no'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3 padding-0">
                                    <div class="form-group">
                                        <label for="" class="control-label">Rebate Date</label> 
                                        <input type="date" id="edit_member_bill_summary_manual_bill_no" name="" class="form-control input-height-30" value="<?php echo isset($lastBillGeneratedData['UpdateMemberBillSummary']['bill_no']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['bill_no'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3 padding-1">
                                    <div class="form-group">
                                        <label for="" class="control-label">Bill Date</label> 
                                        <input type="date" id="edit_member_bill_summary_bill_generated_date" name="data[UpdateMemberBillSummary][bill_generated_date]"  class="form-control input-height-30" value="<?php echo isset($lastBillGeneratedData['UpdateMemberBillSummary']['bill_generated_date']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['bill_generated_date'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3 padding-0">
                                    <div class="form-group">
                                        <label for="" class="control-label">Due Date</label> 
                                        <input type="date" id="edit_member_bill_summary_bill_due_date" name="data[UpdateMemberBillSummary][bill_due_date]" class="form-control input-height-30" value="<?php echo isset($lastBillGeneratedData['UpdateMemberBillSummary']['bill_due_date']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['bill_due_date'] : ''; ?>">
                                    </div>
                                </div>
                            </div> 
                            <div class="row"> 
                                <div class="col-md-2 padding-2">
                                    <div class="form-group">
                                        <label for="" class="control-label">Member</label> 
                                        <select class="form-control select2" id="edit_member_bill_summary_member_id" name="data[UpdateMemberBillSummary][member_id]">
                                            <option value="">Choose  Members </option>
                                            <optgroup label="Society Members List">
                                                <?php
                                                if (isset($societyMemberListData) && count($societyMemberListData) > 0) :
                                                    $s = 1;
                                                    foreach ($societyMemberListData as $societyMemberId => $societyMemberName) { ?>
                                                     <?php if (isset($lastBillGeneratedData['UpdateMemberBillSummary']['member_id']) && $lastBillGeneratedData['UpdateMemberBillSummary']['member_id']  == $societyMemberId){ ?> 
                                                        <option value="<?php echo $societyMemberId; ?>" selected=""><?php echo $societyMemberName; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $societyMemberId; ?>"><?php echo "$societyMemberName"; ?></option>
                                                    <?php $s++;
                                                    }
                                                    }
                                                endif;
                                                ?>  
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>                       
                                <div class="col-md-1 padding-0">
                                    <div class="form-group">
                                        <label for="" class="control-label">Unit No.</label> 
                                        <input type="text" id="edit_member_bill_summary_bill_flat_no" name="data[UpdateMemberBillSummary][flat_no]" class="form-control input-height-30" value="<?php echo isset($lastBillGeneratedData['UpdateMemberBillSummary']['flat_no']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['flat_no'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Total</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_monthly_principal_amount" name="" placeholder="" value="<?php echo isset($lastBillGeneratedData['UpdateMemberBillSummary']['monthly_amount']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['monthly_amount'] : ''; ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Interest on due amount</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_interest_on_due_amount" name="data[UpdateMemberBillSummary][interest_on_due_amount]" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['interest_on_due_amount']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['interest_on_due_amount'] : ''; ?>" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Interest Paid</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_discount"  placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['interest_paid']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['interest_paid'] : ''; ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Discount</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_discount" name="data[UpdateMemberBillSummary][discount]" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['discount']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['discount'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Other Adjustments(Principal)</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_principal_adjusted" name="data[UpdateMemberBillSummary][principal_adjusted]" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['principal_adjusted']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['principal_adjusted'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Other Adjustments(Interest)</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_interest_adjusted" name="data[UpdateMemberBillSummary][interest_adjusted]" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['interest_adjusted']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['interest_adjusted'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="" style="color:blue;">Bill Amount(Rs)</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_monthly_bill_amount" name="" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['monthly_bill_amount']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['monthly_bill_amount'] : ''; ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Principal Arrears</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_principal_arrears" name="" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['op_principal_arrears']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['op_principal_arrears'] : ''; ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Interest Arrears</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_interest_balance" name="" placeholder="" readonly="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['op_interest_arrears']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['op_interest_arrears'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">S.Tax Arrears</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_op_tax_arrears" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['op_tax_arrears']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['op_tax_arrears'] : ''; ?>" readonly="" >
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="">Total Arrears</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_op_total_errears" name="" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['op_tax_arrears']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['op_tax_arrears'] + $lastBillGeneratedData['UpdateMemberBillSummary']['op_principal_arrears'] + $lastBillGeneratedData['UpdateMemberBillSummary']['op_interest_arrears'] : ''; ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="" style="color:blue;"><span id="edit_amtPayableExcess">Amount Payable</span></label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_bill_amount_payable" name="" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['amount_payable']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['amount_payable'] : ''; ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="control-label mb-10 col-sm-4" for="" style="color:blue;"><span id="edit_amtPayableExcess">Balance Amount</span></label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control text-right" id="edit_member_bill_summary_balance_amount" name="" placeholder="" value="<?php echo  isset($lastBillGeneratedData['UpdateMemberBillSummary']['balance_amount']) ? $lastBillGeneratedData['UpdateMemberBillSummary']['balance_amount'] : ''; ?>" readonly="">
                                        </div>
                                    </div>
                                </div> 
                            </div>
                    </div>
                    <center>
                        <button type="submit" class="btn btn-success btn-anim btn-sm">Update Bill</button>
                        <a type="button" data-toggle="tooltip" data-original-title="Go to latest generated bills list" class="btn btn-warning btn-anim btns btn-sm" href="<?php echo Router::url(array('controller' =>'society_bills','action' =>'society_generated_bills')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                    </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>