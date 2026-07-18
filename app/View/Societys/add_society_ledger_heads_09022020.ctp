<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Ledger Heads</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div class="row">
                                <form class="" method="post" id="societyAddLedgerHeadsForm" name="societyAddLedgerHeadsForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                    <div class="col-md-3 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="title">Society Head Sub Group</label>
                                            <div class="">
                                                <select class="form-control" id="society_head_sub_category_id" name="data[SocietyLedgerHeads][society_head_sub_category_id]" onchange="getAccountCategory(this.value);" required="">
                                                    <option value="">select Society Head Sub Group</option>
                                                    <?php
                                                    if (isset($societyHeadSubCategoryLists) && count($societyHeadSubCategoryLists) > 0) :
                                                        $s = 1;
                                                        foreach ($societyHeadSubCategoryLists as $headSubCategoryId => $headSubCategoryName) {
                                                            if (isset($this->request->data['SocietyLedgerHeads']['society_head_sub_category_id']) && $this->request->data['SocietyLedgerHeads']['society_head_sub_category_id'] == $headSubCategoryId) {
                                                                ?>
                                                    <option value="<?php echo $headSubCategoryId; ?>" selected=""><?php echo $headSubCategoryName; ?></option>
                                                            <?php } else { ?>
                                                    <option value="<?php echo $headSubCategoryId; ?>"><?php echo $headSubCategoryName; ?></option>
                                                                <?php
                                                                $s++;
                                                            }
                                                        } endif;
                                                    ?>  
                                                </select>  
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label for="title" class="control-label">Account Category</label>    
                                            <div class="">
                                                <select class="form-control" id="account_category_id" name="data[SocietyLedgerHeads][account_category_id]">
                                                     <?php if(isset($this->request->data['SocietyHeadSubCategory']['account_category_id']) && !empty($this->request->data['SocietyHeadSubCategory']['account_category_id'])){?>
                                                    <option value="<?php echo $this->request->data['SocietyHeadSubCategory']['account_category_id']; ?>"><?php echo $this->request->data['AccountCategory']['title']; ?></option>
                                                    <?php } ?>
                                                </select>  
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-3 padding-0">    
                                        <div class="form-group">
                                            <label for="title" class="control-label">Account Heads</label>    
                                            <div class="">
                                                <select class="form-control" id="account_head_id" name="data[SocietyLedgerHeads][account_head_id]">
                                                     <?php if(isset($this->request->data['AccountHead']['id']) && !empty($this->request->data['AccountHead']['id'])){?>
                                                    <option value="<?php echo $this->request->data['AccountHead']['id']; ?>"><?php echo $this->request->data['AccountHead']['title']; ?></option>
                                                    <?php } ?>
                                                </select>  
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(isset($this->request->data['SocietyLedgerHeads']['id']) && !empty($this->request->data['SocietyLedgerHeads']['id'])) { ?>
                                    <div class="col-md-2 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="short_code">Short Code</label>
                                            <div class="">
                                                <input type="text" class="form-control" id="title" name="data[SocietyLedgerHeads][short_code]" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['short_code']) ? $this->request->data['SocietyLedgerHeads']['short_code'] :''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                   <div class="col-md-2 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="title">Ledger Title<span class="required">*</span></label>
                                            <div class="">
                                                <input type="hidden" name="data[SocietyLedgerHeads][id]" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['id']) ? $this->request->data['SocietyLedgerHeads']['id'] :''; ?>">
                                                <input type="text" class="form-control" id="title" name="data[SocietyLedgerHeads][title]" placeholder="Enter Ledger Title" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['title']) ? $this->request->data['SocietyLedgerHeads']['title'] :''; ?>" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="op_amount">Opening Balance<span class="required">*</span></label>
                                            <div class="">
                                                <input type="hidden" name="data[SocietyLedgerHeads][id]" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['id']) ? $this->request->data['SocietyLedgerHeads']['id'] :''; ?>">
                                                <input type="text" class="form-control" id="opening_amount" name="data[SocietyLedgerHeads][opening_amount]" placeholder="Enter Opening Amount" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['opening_amount']) ? $this->request->data['SocietyLedgerHeads']['opening_amount'] :''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <span class="bill-tariff-deatils-head">Bill Tariff Details</span> 
                                    <div class="bill-tariff-deatils-label-box">
                                        <label class="control-label checkbox-inline" for="is_in_bill_charges"><input type="checkbox" class="" id="is_in_bill_charges" name="data[SocietyLedgerHeads][is_in_bill_charges]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_in_bill_charges']) && $this->request->data['SocietyLedgerHeads']['is_in_bill_charges'] == 1 ? 'checked' : ''); ?>>Is In Bill Charges</label>
                                        <label class="control-label checkbox-inline" for="is_tax_applicable"><input type="checkbox" class="" id="is_tax_applicable" name="data[SocietyLedgerHeads][is_tax_applicable]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_tax_applicable']) && $this->request->data['SocietyLedgerHeads']['is_tax_applicable'] == 1 ? 'checked' : ''); ?>>Service Tax / GST Applicable?</label>
                                        <label class="control-label checkbox-inline" for="is_rebate_applicable"><input type="checkbox" class="" id="is_rebate_applicable" name="data[SocietyLedgerHeads][is_rebate_applicable]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_rebate_applicable']) && $this->request->data['SocietyLedgerHeads']['is_rebate_applicable'] == 1 ? 'checked' : ''); ?>>Rebate Application?</label>
                                        <label class="control-label checkbox-inline" for="is_interest_free"><input type="checkbox" class="" id="is_interest_free" name="data[SocietyLedgerHeads][is_interest_free]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_interest_free']) && $this->request->data['SocietyLedgerHeads']['is_interest_free'] == 1 ? 'checked' : ''); ?>>Interest Free?</label>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group mb-0">
                                        <div class="col-sm-offset-2 col-sm-10"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="Add / Update Society Head" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['SocietyLedgerHeads']['id']) && !empty($this->request->data['SocietyLedgerHeads']['id'])) { ?>Update Head<?php }else{ ?> Add Head <?php } ?></span></button>                                        
                                            <a type="button" data-toggle="tooltip" data-original-title="Go to member list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'society_ledger_heads')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                        </div>
                                    </div>
                                    <?php if(!isset($this->request->data['SocietyLedgerHeads']['id']) && empty($this->request->data['SocietyLedgerHeads']['id'])) { ?>
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="table-wrap">
                                        <div class="table-responsive1">
                                            <table class="table table-striped table-bordered mb-0 padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th>Ledger Head Title</th>
                                                        <th>Short Code</th>
                                                        <th>Opening Balance</th>
                                                        <th>Is In Bill Charges</th>
                                                        <th>Service Tax/GST Applicable</th>
                                                        <th>Rebate</th>
                                                        <th>Interest Free</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php for($i=0;$i<=9;$i++){ ?>
                                                    <tr>
                                                        <td><input type="text" class="form-control" id="title" name="data[SocietyLedgerHead][<?php echo $i; ?>][title]" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['short_code']) ? $this->request->data['SocietyLedgerHeads']['title'] :''; ?>" <?php echo ($i == 0) ? 'required':'';?>></td>
                                                        <td><input type="text" class="form-control" id="title" name="data[SocietyLedgerHead][<?php echo $i; ?>][short_code]" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['short_code']) ? $this->request->data['SocietyLedgerHeads']['short_code'] :''; ?>"></td>
                                                        <td><input type="text" class="form-control" id="title" name="data[SocietyLedgerHead][<?php echo $i; ?>][op_amount]" value="<?php echo isset($this->request->data['SocietyLedgerHeads']['op_amount']) ? $this->request->data['SocietyLedgerHeads']['op_amount'] :''; ?>"></td>
                                                        <td><input type="checkbox" class="" id="is_in_bill_charges" name="data[SocietyLedgerHead][<?php echo $i; ?>][is_in_bill_charges]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_in_bill_charges']) && $this->request->data['SocietyLedgerHeads']['is_in_bill_charges'] == 1 ? 'checked' : ''); ?>>
                                                        <td><input type="checkbox" class="" id="is_tax_applicable" name="data[SocietyLedgerHead][<?php echo $i; ?>][is_tax_applicable]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_tax_applicable']) && $this->request->data['SocietyLedgerHeads']['is_tax_applicable'] == 1 ? 'checked' : ''); ?>>
                                                        <td><input type="checkbox" class="" id="is_rebate_applicable" name="data[SocietyLedgerHead][<?php echo $i; ?>][is_rebate_applicable]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_rebate_applicable']) && $this->request->data['SocietyLedgerHeads']['is_rebate_applicable'] == 1 ? 'checked' : ''); ?>>
                                                        <td><input type="checkbox" class="" id="is_interest_free" name="data[SocietyLedgerHead][<?php echo $i; ?>][is_interest_free]" placeholder="Enter Ledger Title" value="1" <?php echo (isset($this->request->data['SocietyLedgerHeads']['is_interest_free']) && $this->request->data['SocietyLedgerHeads']['is_interest_free'] == 1 ? 'checked' : ''); ?>>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>