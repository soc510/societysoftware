<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Employee Details lists</h6>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add Society Employee', array('controller' => 'societys_employees','action' => 'add_employee_details'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add member identities', 'escape' => false)); ?>
                     </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                   <div class="panel-body">
                       <div class="table-wrap">
                           <div class="table-responsive">
                               <table id="datable_1" class="dataTable table table-hover table-bordered padding-th-none padding-td-none">
                                   <thead>
                                       <tr>
                                           <th>#</th>
                                           <th>Category Name</th>
                                           <th>Name</th>
                                           <th>Joining Date</th>
                                           <th>Date Of Leaving</th>
                                           <th class="text-nowrap">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>                                        
                                       <?php if(isset($buildingEmployeeData) && count($buildingEmployeeData) > 0){
                                         $idCount = 1;
                                         foreach($buildingEmployeeData as $employeeData){
                                      ?>
                                           <tr id="<?php echo $idCount; ?>">
                                               <td><?php echo $idCount; ?></td>
                                               <td><?php echo isset($employeeData['EmployeeCategory']['emp_category_name']) ? $employeeData['EmployeeCategory']['emp_category_name'] :'' ; ?></td>
                                               <td><?php echo isset($employeeData['Employee']['emp_name']) ? $employeeData['Employee']['emp_name'] :'' ; ?></td>
                                               <td><?php echo isset($employeeData['Employee']['joining_date']) ? $employeeData['Employee']['joining_date'] :'' ; ?></td>
                                               <td><?php echo isset($employeeData['Employee']['date_of_leaving']) ? $employeeData['Employee']['date_of_leaving'] :'' ; ?></td>
                                               <td class="text-nowrap">
                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys_employees', 'action' => 'add_employee_details', $employeeData['Employee']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_member_identitys', $employeeData['Employee']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$employeeData['Employee']['id'])); ?>
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
