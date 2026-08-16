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
                        <button type="button" class="btn btn-success" onclick="updateAllOpeningBalances();"><i class="fa fa-save"></i> Update All Opening Balance</button>&nbsp;
                        <?php echo $this->Html->link('<i class="fa fa-plus" style="color:#fff;font-size:10px;"></i> Add Ledger', array('controller' => 'societys','action' => 'add_society_ledger_heads'), array('class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Add Ledger', 'escape' => false)); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_ledger_heads" class="table table-striped table-bordered padding-th-none padding-td-none">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Account Category</th>
                                            <th>Account Heads</th>
                                            <th>Society Head Category</th>
                                            <th>Society Heads</th>
                                            <th>Opening Balance</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            <?php if(isset($societyLedgerHeadsLists) && count($societyLedgerHeadsLists) > 0){
                                         $idCount = 1;
                                         foreach($societyLedgerHeadsLists as $ledgerData){
                                            $ledgerId = isset($ledgerData['SocietyLedgerHeads']['id']) ? $ledgerData['SocietyLedgerHeads']['id'] : '';
                                            $opBal = isset($openingBalanceMap[$ledgerId]) ? $openingBalanceMap[$ledgerId] : '0.00';
                                      ?>
                                        <tr id="<?php echo $idCount; ?>">
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo isset($ledgerData['AccountCategory']['title']) ? $ledgerData['AccountCategory']['title'] :'' ; ?></td>
                                            <td><?php echo isset($ledgerData['AccountHead']['title']) ? $ledgerData['AccountHead']['title'] :'' ; ?></td>
                                            <td><?php echo isset($ledgerData['SocietyHeadSubCategory']['title']) ? $ledgerData['SocietyHeadSubCategory']['title'] :'' ; ?></td>
                                            <td><?php echo isset($ledgerData['SocietyLedgerHeads']['title']) ? $ledgerData['SocietyLedgerHeads']['title'] :'' ; ?></td>
                                            <td><input type="number" step="0.01" class="form-control input-sm op-balance-input" name="op_balance[<?php echo $ledgerId; ?>]" data-ledger-id="<?php echo $ledgerId; ?>" value="<?php echo $opBal; ?>" style="width:120px;text-align:right;"></td>
                                            <td class="text-nowrap">
                                            <?php echo $this->Html->link('<i class="fa fa-pencil text-inverse m-r-10"></i>', array('controller' => 'societys', 'action' => 'add_society_ledger_heads', $ledgerData['SocietyLedgerHeads']['id']), array('class' => 'mr-25', 'data-toggle' => 'tooltip', 'data-original-title' => 'Edit', 'escape' => false)); ?>
                                            <?php if (empty($usedLedgerHeadIds[$ledgerData['SocietyLedgerHeads']['id']])) { ?>
                                            <?php echo $this->Form->postLink('<i class="fa fa-close text-danger"></i>',array('action' => 'delete_society_ledger_heads', $ledgerData['SocietyLedgerHeads']['id']),array('class' => 'mr-25','data-toggle' => 'tooltip','data-original-title' => 'Delete', 'escape' => false), __('Are you sure you want to delete # %s?',$ledgerData['SocietyLedgerHeads']['id'])); ?>
                                            <?php } else { ?>
                                            <span class="mr-25 text-muted" data-toggle="tooltip" data-original-title="In use in bills or entries - cannot delete" style="cursor:not-allowed;"><i class="fa fa-close"></i></span>
                                            <?php } ?>
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
<script>
$(document).ready(function(){
    $('#datable_ledger_heads').DataTable({"pageLength": 100, "lengthMenu": [10, 25, 50, 100, 200]});
});
function updateAllOpeningBalances(){
    var balanceData = {};
    var table = $('#datable_ledger_heads').DataTable();
    table.$('.op-balance-input').each(function(){
        var ledgerId = $(this).data('ledger-id');
        var amount = $(this).val();
        if(ledgerId){
            balanceData[ledgerId] = amount;
        }
    });
    if(Object.keys(balanceData).length === 0){
        alert('No data to update.');
        return;
    }
    $.ajax({
        type: 'POST',
        url: webrootUrl + 'societys_ajax/updateAllOpeningBalances',
        data: {balances: balanceData},
        dataType: 'json',
        success: function(resp){
            if(resp.error == 0){
                alert(resp.error_message);
            } else {
                alert(resp.error_message);
            }
        },
        error: function(){
            alert('Something went wrong. Please try again.');
        }
    });
}
</script>