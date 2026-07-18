<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Identification lists</h6>
                    </div>
                    <div class="pull-right">
                             <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add member info', array('controller' => 'societys','action' => 'add_member_identifications'), array('class' => 'btn btn-warning','data-toggle' => 'tooltip', 'data-original-title' => 'Add previous flat owner/member info','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="dataTable table table-striped table-bordered padding-th-none padding-td-none">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Flat/Shop No</th>
                                            <th>New Member</th>
                                            <th>Old Member</th>
                                            <th>Associate Member</th>
                                            <th>Joint Member Name</th>

                                            <th>Op.Principal</th>
                                            <th>Op.Interest</th>
                                            <th>Op.Tax</th>

                                            <th>Purchased On</th>
                                            <th>Disposed On</th>
                                            <th>Agreement Date</th>
                                            <th>Agreement Value</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                       <?php if(isset($MemberIdentificationDetails) && count($MemberIdentificationDetails) > 0){
                                         $idCount = 1;
                                         foreach($MemberIdentificationDetails as $memberIdentificationData){
                                      ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['flat_no']) ? $memberIdentificationData['MemberIdentification']['flat_no'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['second_member']) ? $memberIdentificationData['MemberIdentification']['second_member'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['third_member']) ? $memberIdentificationData['MemberIdentification']['third_member'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['associate_member']) ? $memberIdentificationData['MemberIdentification']['associate_member'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['joint_member']) ? $memberIdentificationData['MemberIdentification']['joint_member'] :'' ; ?></td>




                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['op_principal']) ? $memberIdentificationData['MemberIdentification']['op_principal'] :'' ; ?></td> 
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['op_interest']) ? $memberIdentificationData['MemberIdentification']['op_interest'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['op_tax']) ?    $memberIdentificationData['MemberIdentification']['op_tax'] :'' ; ?></td> 

                                           

                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['purchased_on']) ? $memberIdentificationData['MemberIdentification']['purchased_on'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['disposed_on']) ? $memberIdentificationData['MemberIdentification']['disposed_on'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['agreement_date']) ? $memberIdentificationData['MemberIdentification']['agreement_date'] :'' ; ?></td>
                                            <td><?php echo isset($memberIdentificationData['MemberIdentification']['agreement_value']) ? $memberIdentificationData['MemberIdentification']['agreement_value'] :'' ; ?></td>
                                            <td class="text-nowrap">
                                                <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'add_member_identifications', $memberIdentificationData['MemberIdentification']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                                <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_member_identification', $memberIdentificationData['MemberIdentification']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$memberIdentificationData['MemberIdentification']['id'])); ?>
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
