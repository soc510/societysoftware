<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Building Identity</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                            <form class="" method="post" id="buildingIdentityForm" name="buildingIdentityForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-1 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="society_id">Society Id</label>
                                            <div class="">
                                                <input type="text" class="form-control" id="society_id" name="data[Building][society_id]" value="<?php echo isset($this->request->data['Building']['society_id']) ? $this->request->data['Building']['society_id'] :$this->Session->read('Auth.User.id'); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="building_name">Building Name <span class="required">*</span></label>
                                            <div class="">
                                                <input type="text" class="form-control" id="building_name" name="data[Building][building_name]" placeholder="Enter Building Name" value="<?php echo isset($this->request->data['Building']['building_name']) ? $this->request->data['Building']['building_name'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-2 padding-0">    
                                        <div class="form-group">
                                            <label class="control-label" for="num_flats">No. of Flats<span class="required">*</span></label>
                                            <div class="">
                                                <input type="text" class="form-control" id="num_flats" name="data[Building][num_flats]" placeholder="no.of flats" value="<?php echo isset($this->request->data['Building']['num_flats']) ? $this->request->data['Building']['num_flats'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="col-md-6 margin-top-2">
                                        <button type="submit" data-toggle="tooltip" data-original-title="Add / Update building details" class="btn btn-success btn-sm btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['Building']['society_id']) && !empty($this->request->data['Building']['society_id'])) { ?>Update Building<?php }else{ ?> Add Building <?php } ?></span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-sm btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                    </div>  
                                    <div class="clearfix"></div>
                                   <!-- <div class="form-group mb-0">
                                        <div class="col-sm-offset-2 col-sm-10">                                             
                                        </div>
                                    </div>  -->
                                </div>
                            </form>
                        </div>

                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-hover table-bordered padding-th-none padding-td-none">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Society Name</th>
                                            <th>Building Name</th>
                                            <th>No. Of Flats</th>
                                            <th>Created Date</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0){
                                         $idCount = 1;
                                         foreach($societyBuildingLists as $societyBuildingData){
                                      ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($societyBuildingData['Society']['society_name']) ? $societyBuildingData['Society']['society_name'] :'' ; ?></td>
                                            <td><?php echo isset($societyBuildingData['Building']['building_name']) ? $societyBuildingData['Building']['building_name'] :'' ; ?></td>
                                            <td><?php echo isset($societyBuildingData['Building']['num_flats']) ? $societyBuildingData['Building']['num_flats'] :'' ; ?></td>
                                            <td><?php echo isset($societyBuildingData['Building']['udate']) ? $societyBuildingData['Building']['udate'] :'' ; ?></td>
                                            <td class="text-nowrap">
                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'building_identitys', $societyBuildingData['Building']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_building_identitys', $societyBuildingData['Building']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$societyBuildingData['Building']['id'])); ?>
                                            </td>
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

