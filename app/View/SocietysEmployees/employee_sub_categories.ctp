<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Employee Sub Categories</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form class="" method="post" id="employeeCategoriesForm" name="employeeCategoriesForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-3 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="emp_category_name">Employee Category<span class="required">*</span></label>
                                            <div class="">                                                
                                                <select name="data[EmployeeSubCategory][emp_category_id]" id="emp_category_name" class="form-control input-group-lg">
                                                    <option value="">select</option>
                                                    <?php
                                                    if (isset($employeeCategoryData) && count($employeeCategoryData) > 0) :
                                                        $s = 1;
                                                        foreach ($employeeCategoryData as $empCategoryId => $employeeCategoryName) {
                                                            if (isset($this->request->data['EmployeeSubCategory']['emp_category_id']) && $this->request->data['EmployeeSubCategory']['emp_category_id'] == $empCategoryId) {
                                                                ?>
                                                    <option value="<?php echo $empCategoryId; ?>" selected=""><?php echo $employeeCategoryName; ?></option>
                                                            <?php } else { ?>
                                                    <option value="<?php echo $empCategoryId; ?>"><?php echo $employeeCategoryName; ?></option>
                                                                <?php
                                                                $s++;
                                                            }
                                                        } endif;
                                                    ?>  
                                                </select>
                                            </div>   
                                        </div>
                                    </div> 
                                    <div class="col-md-3 padding-1">    
                                        <div class="form-group">
                                            <label class="control-label" for="emp_sub_category_name">Employee sub Category<span class="required">*</span></label>
                                            <div class="">
                                                <input type="hidden" class="form-control" id="emp_sub_category_name" name="data[EmployeeSubCategory][id]" value="<?php echo isset($this->request->data['EmployeeSubCategory']['id']) ? $this->request->data['EmployeeSubCategory']['id'] :''; ?>">
                                                <input type="text" class="form-control" id="emp_sub_category_name" name="data[EmployeeSubCategory][emp_sub_category_name]" placeholder="Enter Employee Sub Category" value="<?php echo isset($this->request->data['EmployeeSubCategory']['emp_sub_category_name']) ? $this->request->data['EmployeeSubCategory']['emp_sub_category_name'] :''; ?>"  required="">
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-3 padding-0 margin-top-2">    
                                        <button type="submit" data-toggle="tooltip" data-original-title="Add / Update member details" class="btn btn-success btn-sm btn-anim"><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['EmployeeSubCategory']['id']) && !empty($this->request->data['EmployeeSubCategory']['id'])) { ?>Update Sub Category<?php }else{ ?> Add Sub Category <?php } ?></span></button>                                        
                                        <a type="button" data-toggle="tooltip" data-original-title="Go to member list" class="btn btn-success btn-sm btn-anim" href="<?php echo Router::url(array('controller' =>'societysemployees','action' =>'employee_sub_categories')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
                                    </div>  
                                </div>
                                <!-- <div class="form-group mb-0">
                                     <div class="col-sm-offset-2 col-sm-10">                                         
                                     </div>
                                 </div> -->                               
                            </form>
                        </div>
                    </div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-hover table-bordered padding-th-none padding-td-none">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee Category</th>
                                            <th>Employee Sub Category</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        <?php if(isset($employeeSubCategoryList) && count($employeeSubCategoryList) > 0){
                                         $idCount = 1;
                                         foreach($employeeSubCategoryList as $employeeSubCategoryData){                                             
                                                ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($employeeSubCategoryData['EmployeeCategory']['emp_category_name']) ? $employeeSubCategoryData['EmployeeCategory']['emp_category_name'] :'' ; ?></td>
                                            <td><?php echo isset($employeeSubCategoryData['EmployeeSubCategory']['emp_sub_category_name']) ? $employeeSubCategoryData['EmployeeSubCategory']['emp_sub_category_name'] :'' ; ?></td>
                                            <td class="text-nowrap">
                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societysemployees','action' =>'employee_sub_categories',$employeeSubCategoryData['EmployeeSubCategory']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_employee_sub_categories', $employeeSubCategoryData['EmployeeSubCategory']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$employeeSubCategoryData['EmployeeSubCategory']['id'])); ?>
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

