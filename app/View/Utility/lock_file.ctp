<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"><i class="fa fa-lock"></i> Audit Lock File</h6>
                    </div>
                    <div class="clearfix"></div>

                    <form method="post" id="lockFileFilterFrm" autocomplete="off">
                        <div class="col-md-10 member-tariff-left-box">
                            <div class="row">
                                <div class="col-md-3 no-padding-right">
                                    <div class="account-report-form-field-box">
                                        <div class="account-report-form-box-label">From&nbsp;</div>
                                        <input type="date" class="account-report-form-box-field-date" name="data[BillAuditLog][from_date]" value="<?php echo isset($this->request->data['BillAuditLog']['from_date']) ? $this->request->data['BillAuditLog']['from_date'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3 no-padding-right">
                                    <div class="account-report-form-field-box">
                                        <div class="account-report-form-box-label">To&nbsp;</div>
                                        <input type="date" class="account-report-form-box-field-date" name="data[BillAuditLog][to_date]" value="<?php echo isset($this->request->data['BillAuditLog']['to_date']) ? $this->request->data['BillAuditLog']['to_date'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3 no-padding-right">
                                    <div class="account-report-form-field-box">
                                        <div class="account-report-form-box-label">Action&nbsp;</div>
                                        <select class="account-report-form-box-field-dropdown" name="data[BillAuditLog][action_type]">
                                            <option value="">All</option>
                                            <option value="edit" <?php echo (isset($this->request->data['BillAuditLog']['action_type']) && $this->request->data['BillAuditLog']['action_type'] == 'edit') ? 'selected' : ''; ?>>Edit</option>
                                            <option value="delete" <?php echo (isset($this->request->data['BillAuditLog']['action_type']) && $this->request->data['BillAuditLog']['action_type'] == 'delete') ? 'selected' : ''; ?>>Delete</option>
                                            <option value="bulk_delete" <?php echo (isset($this->request->data['BillAuditLog']['action_type']) && $this->request->data['BillAuditLog']['action_type'] == 'bulk_delete') ? 'selected' : ''; ?>>Bulk Delete</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 nopadding">
                                    <div class="form-group" style="margin-top:5px;">
                                        <button type="submit" class="btn btn-success btn-anim btn-sm"><i class="icon-rocket" style="float:left;margin-left:3%;"></i><span class="btn-text">Filter</span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>

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
                                            <th>User</th>
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
                                            if ($d['action_type'] == 'delete' || $d['action_type'] == 'bulk_delete') {
                                                $actionClass = 'label-danger';
                                            } elseif ($d['action_type'] == 'edit') {
                                                $actionClass = 'label-warning';
                                            } else {
                                                $actionClass = 'label-info';
                                            }
                                    ?>
                                        <tr>
                                            <td><?php echo $idCount; ?></td>
                                            <td><?php echo date('d-m-Y H:i:s', strtotime($d['changed_at'])); ?></td>
                                            <td><?php echo isset($userNames[$d['changed_by']]) ? htmlspecialchars($userNames[$d['changed_by']]) : $d['changed_by']; ?></td>
                                            <td><span class="label <?php echo $actionClass; ?>"><?php echo strtoupper($d['action_type']); ?></span></td>
                                            <td><?php echo isset($memberNames[$d['member_id']]) ? htmlspecialchars($memberNames[$d['member_id']]) : $d['member_id']; ?></td>
                                            <td><?php echo $d['bill_no']; ?></td>
                                            <td><?php echo !empty($d['bill_month']) ? $utilObj->getMonthWordFormat($d['bill_month']) : ''; ?></td>
                                            <td><?php echo htmlspecialchars($d['field_name']); ?></td>
                                            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?php echo htmlspecialchars($d['old_value']); ?>"><?php echo htmlspecialchars($d['old_value']); ?></td>
                                            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?php echo htmlspecialchars($d['new_value']); ?>"><?php echo htmlspecialchars($d['new_value']); ?></td>
                                            <td><?php echo htmlspecialchars($d['remarks']); ?></td>
                                            <td><?php echo $d['ip_address']; ?></td>
                                        </tr>
                                    <?php $idCount++; }} else { ?>
                                        <tr>
                                            <td colspan="12" class="text-center">No audit records found.</td>
                                        </tr>
                                    <?php } ?>
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
