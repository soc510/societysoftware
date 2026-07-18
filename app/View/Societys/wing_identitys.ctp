<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Wing Identity</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="wingIndentityForm" name="wingIndentityForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-3 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="wingBuildingName">Building Name<span class="required">*</span></label>
                                            <div class="">
                                                <select name="data[Wing][building_id]" id="wingBuildingName" class="form-control input-group-lg" required>
                                                    <option value="">Select building</option>
                                                    <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                    foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                    <?php if(isset($this->request->data['Building']['id']) && $this->request->data['Building']['id'] == $buildingID){?>
                                                    <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                    <?php }else {?>
                                                    <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                    <?php }?>
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-3 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="wing_name">Wing Name <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="wing_namewing_name" name="data[Wing][wing_name]" value="<?php echo isset($this->request->data['Wing']['wing_name']) ? $this->request->data['Wing']['wing_name'] :''; ?>"placeholder="Enter wing short name" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 margin-top-2">
                                        <button type="submit" data-toggle="tooltip" data-original-title="Add wing" class="btn btn-success btn-sm btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['Wing']['id']) && !empty($this->request->data['Wing']['id'])) { ?>Update wing<?php }else{ ?> Add wing <?php } ?></span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-sm btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                 
                                    </div>
                                   <!--<div class="form-group mb-0">
                                        <div class="col-sm-offset-2 col-sm-10"></div>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-hover table-bordered padding-th-none padding-td-none">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Building Name</th>
                                            <th>Wing short name</th>                                            
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                       <?php if(isset($buildingWingData) && count($buildingWingData) > 0){
                                         $idCount = 1;
                                         foreach($buildingWingData as $wingData){
                                      ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($wingData['Building']['building_name']) ? $wingData['Building']['building_name'] :'' ; ?></td>
                                            <td><?php echo isset($wingData['Wing']['wing_name']) ? $wingData['Wing']['wing_name'] :'' ; ?></td>                                           
                                            <td class="text-nowrap">
                                            <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'wing_identitys', $wingData['Wing']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                            <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_wing_identitys', $wingData['Wing']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$wingData['Wing']['id'])); ?>
                                            </td>
                                        </tr>
                                         <?php $idCount++;}} ?>
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

