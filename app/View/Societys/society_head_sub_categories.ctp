<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Society Head Sub category</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="row society-head-sub-cat-frm">
                                <form class="" method="post" id="societyHeadsForm" name="societyHeadsForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                    <div class="col-md-4 padding-1">
                                        <div class="form-group">
                                            <label class="control-label" for="title">Account Sub Group<span class="required">*</span></label>
                                            <input type="hidden" class="form-control"  name="data[SocietyHeadSubCategory][id]" value="<?php echo isset($this->request->data['SocietyHeadSubCategory']['id']) ? $this->request->data['SocietyHeadSubCategory']['id'] :''; ?>">
                                            <?php if(isset($this->request->data['SocietyHeadSubCategory']['id'])) { ?>
                                            <input class="form-control" id="title" name="data[SocietyHeadSubCategory][title]" placeholder="Enter Head Title" value="<?php echo isset($this->request->data['SocietyHeadSubCategory']['title']) ? $this->request->data['SocietyHeadSubCategory']['title'] :''; ?>">
                                            <?php } else { ?>
                                            <textarea class="form-control" style="height:150px;width:350px;" id="title" name="data[SocietyHeadSubCategory][title]" placeholder="Enter Head Title"><?php echo isset($this->request->data['SocietyHeadSubCategory']['title']) ? $this->request->data['SocietyHeadSubCategory']['title'] :''; ?></textarea>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <label for="title" class="control-label">Account Category <span class="required">*</span></label>                                              
                                        <select class="form-control" id="title" name="data[SocietyHeadSubCategory][account_category_id]" onchange="getAccountHeads(this.value, '#AccountHeads');" required="">
                                            <option value="">Select</option>
                                            <?php if(isset($societyAccountCategoryLists) && count($societyAccountCategoryLists) > 0) {
                                            foreach($societyAccountCategoryLists as $accountCategoryId => $accountCategoryName){?>
                                            <?php if(isset($this->request->data['SocietyHeadSubCategory']['account_category_id']) && $this->request->data['SocietyHeadSubCategory']['account_category_id'] == $accountCategoryId){?>
                                            <option value="<?php echo $accountCategoryId; ?>" selected><?php echo $accountCategoryName; ?></option>
                                            <?php }else {?>
                                            <option value="<?php echo $accountCategoryId; ?>"><?php echo $accountCategoryName; ?></option>
                                            <?php }?>
                                            <?php } }?>
                                        </select> 
                                    </div>
                                    <div class="col-md-2 padding-1">
                                        <label for="title" class="control-label">Account Group</label>   
                                        <select class="form-control" id="AccountHeads" name="data[SocietyHeadSubCategory][account_head_id]">
                                       <?php if(isset($this->request->data['SocietyHeadSubCategory']['account_head_id']) && $this->request->data['SocietyHeadSubCategory']['account_head_id']){?>
                                            <option value="<?php echo $this->request->data['SocietyHeadSubCategory']['account_head_id']; ?>"><?php echo $this->request->data['AccountHead']['title']; ?></option>
                                        <?php } ?>
                                        </select>  
                                    </div>
                                    <div class="col-md-2 padding-0" style="margin-top:30px;">    
                                        <div class="form-group">
                                            <label for="title" class="control-label">Status</label>                 
                                            <label class="radio-inline">
                                                <input type="radio" name="data[SocietyHeadSubCategory][account_status]" value="0" <?php  if( isset($this->request->data['SocietyHeadSubCategory']['account_status']) && $this->request->data['SocietyHeadSubCategory']['account_status'] == 0){ ?> checked="checked" <?php }?> >Active
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="data[SocietyHeadSubCategory][account_status]" value="1" <?php  if(isset($this->request->data['SocietyHeadSubCategory']['account_status']) && $this->request->data['SocietyHeadSubCategory']['account_status'] == 1){ ?> checked="checked" <?php }?> >Inactive
                                            </label>
                                        </div>
                                    </div> 
                                    <div class="col-md-4 padding-3" style="margin-top:15px;">  
                                        <div class="form-group mb-0">
                                            <div class="col-sm-offset-2 col-sm-12"> 
                                                <button type="submit" data-toggle="tooltip" data-original-title="Add / Update Society Head Category" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['SocietyHeadSubCategory']['id']) && !empty($this->request->data['SocietyHeadSubCategory']['id'])) { ?>Update Head Category<?php }else{ ?> Add Head Category<?php } ?></span></button>                                        
                                                <a type="button" data-toggle="tooltip" data-original-title="Go to member list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'society_head_sub_categories')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                            </div>
                                        </div>   
                                    </div> 
                                </form>
                            </div>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-bordered table-striped display padding-td-none padding-th-none  pb-30" >
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Account Category</th>
                                            <th>Account Head</th>
                                            <th>Society Head Sub Group</th>
                                            <th>status</th>
                                            <!--<th class="text-nowrap">Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        <?php if(isset($societyHeadSubCategoryData) && count($societyHeadSubCategoryData) > 0){
                                                    $idCount = 1;
                                             foreach($societyHeadSubCategoryData as $headSubCategoryData){
                                          ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($headSubCategoryData['AccountCategory']['title']) ? $headSubCategoryData['AccountCategory']['title'] :'' ; ?></td>
                                            <td><?php echo isset($headSubCategoryData['AccountHead']['title']) ? $headSubCategoryData['AccountHead']['title'] :'' ; ?></td>
                                            <td><?php echo isset($headSubCategoryData['SocietyHeadSubCategory']['title']) ? $headSubCategoryData['SocietyHeadSubCategory']['title'] :'' ; ?></td>
                                                    <?php  if(isset($headSubCategoryData['SocietyHeadSubCategory']['account_status']) && $headSubCategoryData['SocietyHeadSubCategory']['account_status'] == 0){ ?>
                                            <td class="text-center"><span class="label label-primary">Active</span></td>
                                                    <?php }else{ ?>
                                            <td class="text-center"><span class="label label label-danger">In Active</span></td>
                                                    <?php } ?>
                                            <!--<td class="text-nowrap">
                                                    <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'society_head_sub_categories', $headSubCategoryData['SocietyHeadSubCategory']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                    <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_head_sub_categories', $headSubCategoryData['SocietyHeadSubCategory']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$headSubCategoryData['SocietyHeadSubCategory']['id'])); ?>
                                            </td>-->
                                        </tr>
                                             <?php $idCount++;}}?>
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