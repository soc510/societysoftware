<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Tariff</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <div class="row">
                                <form class="form-horizontal" method="post" id="societyAddLedgerHeadsForm" name="societyAddLedgerHeadsForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                    <div class="col-md-6">    
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="title">Society Members</label>
                                            <div class="col-md-9">
                                                <input type="hidden" name="data[MemberTariff][id]" value="<?php echo isset($this->request->data['MemberTariff']['id']) ? $this->request->data['MemberTariff']['id'] :''; ?>">
                                                <select class="form-control" id="society_head_sub_category_id" name="data[MemberTariff][member_id]" onchange="getSocietyMemberDetails(this.value);">
                                                    <option value="">Select  Members </option>
                                                    <?php
                                                    if (isset($societyMemberLists) && count($societyMemberLists) > 0) :
                                                        $s = 1;
                                                        foreach ($societyMemberLists as $societyMemberId => $societyMemberName) {
                                                            if (isset($this->request->data['MemberTariff']['member_id']) && $this->request->data['MemberTariff']['member_id'] == $societyMemberId) {
                                                                ?>
                                                    <option value="<?php echo $societyMemberId; ?>" selected=""><?php echo $societyMemberName; ?></option>
                                                            <?php } else { ?>
                                                    <option value="<?php echo $societyMemberId; ?>"><?php echo $societyMemberName; ?></option>
                                                                <?php
                                                                $s++;
                                                            }
                                                        } endif;
                                                    ?>  
                                                </select>  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>    
                                    <div class="col-md-6" id="show_member_details">

                                    </div>
                                    <div class="clearfix"></div>
                                    <?php
                                    
                                    if(isset($societyMemberTariffDetails)){
                                        print_r($societyMemberTariffDetails);die;
                                    }
                                      if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) :
                                            $s = 1;
                                            foreach ($societyLedgerHeadList as $key => $societyLedgerHeadData) {
                                                   ?>
                                                    <div class="col-md-6">    
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label " for="example-input-small"><?php echo $societyLedgerHeadData['SocietyLedgerHeads']['title'];?><span class="required">*</span></label>
                                                            <div class="col-sm-6">
                                                                <input type="text" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[MemberTariff][ledger_head_id][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>]" class="form-control input-sm" placeholder="<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['title'];?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                             <?php
                                              $s++;
                                            } endif;
                                      ?>  
                                    <div class="form-group mb-0">
                                        <div class="col-sm-offset-2 col-sm-10"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="Add / Update Society Head" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['SocietyHeadSubCategory']['id']) && !empty($this->request->data['SocietyHeadSubCategory']['id'])) { ?>Update Head<?php }else{ ?> Add / Update Head <?php } ?></span></button>                                        
                                            <a type="button" data-toggle="tooltip" data-original-title="Go to member list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'society_ledger_heads')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
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
</div>