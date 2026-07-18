<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Society Listing</h6>
                    </div>
                    <div class="pull-right">
                        <?php echo $this->Html->link('<span class="btn-text">Add Society</span>',array('controller' => 'admin', 'action' => 'add_societys'), array('class' => 'btn btn-primary', 'data-toggle' => 'tooltip', 'data-original-title' => 'Add Society', 'escape' => false)); ?> 
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-hover table-bordered padding-th-none padding-td-none display pb-30" >
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Society Name</th>
                                            <th>Society Code</th>
                                            <th>Role</th>
                                            <th>Created Date</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($societyData) && count($societyData) > 0) :
                                            $s = 1;
                                            foreach($societyData as $sData){      
                                        ?>
                                        <tr id="society_<?php echo $s; ?>">
                                            <td><?php echo $s; ?></td>
                                            <td><?php echo isset($sData['User']['username']) ? $sData['User']['username'] : ''; ?></td>
                                            <td><?php echo isset($sData['Society'][0]['society_name']) ? $sData['Society'][0]['society_name'] : ''; ?></td>
                                            <td><?php echo isset($sData['Society'][0]['society_code']) ? $sData['Society'][0]['society_code'] : ''; ?></td>
                                            <td><?php echo isset($sData['User']['role']) ? $sData['User']['role'] : ''; ?></td>
                                            <td><?php echo isset($sData['User']['cdate']) ? date('d/m/Y H:i:s',strtotime($sData['User']['cdate'])) : ''; ?></td>
                                            <td class="text-nowrap">
                                                                                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'admin', 'action' => 'add_societys', $sData['User']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                            <!--<a href="javascript:void(0);" class="mr-25" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil text-inverse m-r-10"></i></a> 
                                                <a href="#" data-toggle="tooltip" data-original-title="Delete"> <i class="fa fa-close text-danger"></i> </a>-->
                                                                                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_societys', $sData['User']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?', $sData['User']['id'])); ?>
                                            </td>
                                        </tr>
                                       
                                        <?php $s++; } endif;?>   
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
