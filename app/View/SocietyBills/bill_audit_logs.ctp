<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"><i class="fa fa-history"></i> Bill Edit/Delete Audit Log</h6>
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
                                            <th>Date/Time</th>
                                            <th>Action</th>
                                            <th>Member</th>
                                            <th>Bill No</th>
                                            <th>Bill Month</th>
                                            <th>Field</th>
                                            <th>Old Value</th>
                                            <th>New Value</th>
                                            <th>Remarks</th>
                                            <th>IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($auditLogs) && count($auditLogs) > 0){
                                        $idCount = 1;
                                        foreach($auditLogs as $log){
                                            $d = $log['BillAuditLog'];
                                            $actionClass = ($d['action_type'] == 'delete' || $d['action_type'] == 'bulk_delete') ? 'label-danger' : 'label-info';
                                    ?>
                                        <tr>
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo $d['changed_at']; ?></td>
                                            <td><span class="label <?php echo $actionClass; ?>"><?php echo strtoupper($d['action_type']); ?></span></td>
                                            <td><?php echo isset($memberNames[$d['member_id']]) ? $memberNames[$d['member_id']] : $d['member_id']; ?></td>
                                            <td><?php echo $d['bill_no']; ?></td>
                                            <td><?php echo !empty($d['bill_month']) ? $utilObj->getMonthWordFormat($d['bill_month']) : ''; ?></td>
                                            <td><?php echo $d['field_name']; ?></td>
                                            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($d['old_value']); ?></td>
                                            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($d['new_value']); ?></td>
                                            <td><?php echo $d['remarks']; ?></td>
                                            <td><?php echo $d['ip_address']; ?></td>
                                        </tr>
                                    <?php $idCount++; }} ?>
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
