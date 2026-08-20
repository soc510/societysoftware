<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Identities lists1</h6>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Member Identification lists', array('controller' => 'societys','action' =>'member_identifications'), array('class' => 'btn btn-danger','data-toggle' => 'tooltip','target' => '_blank','data-original-title' => 'Add previous flat owner/member info','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add member', array('controller' => 'societys','action' => 'add_member_identitys'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add member identities','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-upload" style="color:#fff;font-size:10px;"></i> Upload Member Identities CSV', array('controller' => 'societys','action' =>'upload_member_list'), array('class' => 'btn btn-warning','data-toggle' => 'tooltip','target' => '_blank','data-original-title' => 'Upload member list using CSV','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                             <?php // echo $this->Html->link('<i class="fa fa-upload" style="color:#fff;font-size:10px;"></i> Upload Member Identities CSV','javascript:void(0);',array('onclick'=>"loadSocietyBillsModel('#upload_Member_Identitys_CSV');",'class' => 'btn btn-warning','data-toggle' => 'tooltip', 'data-original-title' => 'Upload member list using CSV','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>&nbsp;&nbsp;
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-upload" style="color:#fff;font-size:10px;"></i> Upload Member Opening Balance', array('controller' => 'societys','action' => 'upload_member_op_balance'), array('class' => 'btn btn-danger','data-toggle' => 'tooltip', 'data-original-title' => 'Upload CSV to update member opening balance','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Download Member Opening Balance', array('controller' => 'societys','action' => 'download_member_op_template'), array('class' => 'btn btn-info','data-toggle' => 'tooltip', 'data-original-title' => 'Download member data with opening balance as CSV','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Download Sample Template file','/files/SampleMembersIdentities.csv', array('class' => 'btn  btn-success','data-toggle' => 'tooltip', 'data-original-title' => 'Download member identities sample CSV file','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                     </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-heading">
                    <div class="pull-righ">
                        <?php echo $this->Html->link('<i class="fa fa-remove" style="color:#fff;font-size:10px;"></i> Delete All Members','javascript:void(0);',array('onclick'=>"deleteAllMembers();",'class' => 'btn btn-warning','data-toggle' => 'tooltip', 'data-original-title' => 'Delete All Members','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                </div>                
                <div class="panel-wrapper collapse in">
                   <div class="panel-body">
                       <div class="table-wrap">
                           <div class="table-responsive">
                               <table id="datable_1" class="dataTable table table-striped table-bordered padding-th-none padding-td-none">
                                   <thead>
                                       <tr>
                                           <th>#</th>
                                           <th>Member Name</th>
                                           <th>Flat/Shop No</th>
                                           <th>Building Name</th>
                                           <th>Wing Name</th>
                                           <th>Floor No</th>
                                           <th>Opening Principal</th>
                                           <th>Opening Interest</th>
                                           <th>Opening Tax</th>
                                           <!--<th>Member Identification</th>-->
                                           <th class="text-nowrap">Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>                                        
                                       <?php if(isset($wingMemberData) && count($wingMemberData) > 0){
                                         $idCount = 1;
                                         foreach($wingMemberData as $memberData){
                                      ?>
                                           <tr id="<?php echo $idCount; ?>">
                                               <td><?php echo $idCount; ?></td>
                                               <td><?php echo $memberData['Member']['member_prefix']; echo ' ';
												echo isset($memberData['Member']['member_name']) ? $memberData['Member']['member_name'] :'' ; ?></td>
                                               <td><?php echo isset($memberData['Member']['flat_no']) ? $memberData['Member']['flat_no'] :'' ; ?></td>
                                               <td><?php echo isset($memberData['Building']['building_name']) ? $memberData['Building']['building_name'] :'' ; ?></td>
                                               <td><?php echo isset($memberData['Wing']['wing_name']) ? $memberData['Wing']['wing_name'] :'' ; ?></td>
                                               <!--<td><img height="100px;" src="<?php echo isset($memberData['Member']['member_photo']) ? $this->webroot.$memberData['Member']['member_photo'] :'' ; ?>"></a></td>-->
                                               <td><?php echo isset($memberData['Member']['floor_no']) ? $memberData['Member']['floor_no'] :'' ; ?></td>
                                               <td class="text-right"><?php echo isset($memberData['Member']['op_principal']) ? $memberData['Member']['op_principal'] :'0.00' ; ?></td>
                                               <td class="text-right"><?php echo isset($memberData['Member']['op_interest']) ? $memberData['Member']['op_interest'] :'0.00' ; ?></td>
                                               <td class="text-right"><?php echo isset($memberData['Member']['op_tax']) ? $memberData['Member']['op_tax'] :'0.00' ; ?></td>
                                               <!--<td class="text-right"><?php /*echo $this->Html->link('<i class="fa fa-plus-square-o"></i> Add Member Info', array('controller' => 'societys_members', 'action' => 'add_member_identifications',$memberData['Member']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'add Member Further Info', 'escape' => false));*/ ?></td>-->
                                               <td class="text-nowrap">
                                               <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'add_member_identitys', $memberData['Member']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_member_identitys', $memberData['Member']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$memberData['Member']['id'])); ?>
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
