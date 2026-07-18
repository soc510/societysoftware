<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Ledger Heads</h6>
                    </div>
                    <div class="pull-right">
                        <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add Ledger', array('controller' => 'societys','action' => 'add_society_ledger_heads'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add Ledger', 'escape' => false)); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-striped table-bordered padding-th-none padding-td-none">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Account Category</th>
                                            <th>Account Heads</th>
                                            <th>Society Head Category</th>
                                            <th>Society Heads</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                            <?php if(isset($societyLedgerHeadsLists) && count($societyLedgerHeadsLists) > 0){
                                         $idCount = 1;
                                         foreach($societyLedgerHeadsLists as $ledgerData){
                                      ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($ledgerData['AccountCategory']['title']) ? $ledgerData['AccountCategory']['title'] :'' ; ?></td>
                                            <td><?php echo isset($ledgerData['AccountHead']['title']) ? $ledgerData['AccountHead']['title'] :'' ; ?></td>
                                            <td><?php echo isset($ledgerData['SocietyHeadSubCategory']['title']) ? $ledgerData['SocietyHeadSubCategory']['title'] :'' ; ?></td>
                                            <td><?php echo isset($ledgerData['SocietyLedgerHeads']['title']) ? $ledgerData['SocietyLedgerHeads']['title'] :'' ; ?></td>
                                            <td class="text-nowrap">
                                            <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'add_society_ledger_heads', $ledgerData['SocietyLedgerHeads']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>    
                                            <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_society_ledger_heads', $ledgerData['SocietyLedgerHeads']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$ledgerData['SocietyLedgerHeads']['id'])); ?>
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