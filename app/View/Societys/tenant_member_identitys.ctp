<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Tenant / Nominal Member Identity</h6>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add Tenant', array('controller' => 'societys','action' => 'add_tenant_member_identitys'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add Tenant / Nominal Member Identity', 'escape' => false)); ?>
                     </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-wrapper collapse in">
                   <div class="panel-body">
                       <div class="table-wrap">
                           <div class="table-responsive">
                               <table id="datable_1" class="table table-hover table-bordered padding-th-none padding-td-none " >
                                   <thead>
                                       <tr>
                                           <th>#</th>
                                           <th>Name</th>
                                           <th>Lease Type</th>
                                           <th>Agreement on</th>
                                           <th>Contact no</th>
                                           <th>Rent_Per_Month</th>
                                           <th class="text-nowrap">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>                                        
                                       <?php if(isset($wingTenantData) && count($wingTenantData) > 0){
                                         $idCount = 1;
                                         foreach($wingTenantData as $wingTenantInfo){
                                        ?>
                                        <tr id="<?php echo $idCount; ?>">
                                             <td><?php echo $idCount; ?></td>
                                             <td><?php echo isset($wingTenantInfo['Tenant']['tenant_name']) ? $wingTenantInfo['Tenant']['tenant_name'] :'' ; ?></td>
                                             <td><?php echo isset($wingTenantInfo['Tenant']['lease_type']) ? $wingTenantInfo['Tenant']['lease_type'] :'' ; ?></td>
                                             <td><?php echo isset($wingTenantInfo['Tenant']['agreement_on']) ? $wingTenantInfo['Tenant']['agreement_on'] :'' ; ?></td>
                                             <td><?php echo isset($wingTenantInfo['Tenant']['phone']) ? $wingTenantInfo['Tenant']['phone'] :'' ; ?></td>
                                             <td><?php echo isset($wingTenantInfo['Tenant']['rent_per_month']) ? $wingTenantInfo['Tenant']['rent_per_month'] :'' ; ?></td>
                                             <td class="text-nowrap">
                                             <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'add_tenant_member_identitys', $wingTenantInfo['Tenant']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                             <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_tenant_identitys', $wingTenantInfo['Tenant']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$wingTenantInfo['Tenant']['id'])); ?>
                                            </td>
                                        </tr>
                                         <?php $idCount++; } }?>
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