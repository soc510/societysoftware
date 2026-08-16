<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"><i class="fa fa-user"></i> Edit User - <?php echo htmlspecialchars($userData['User']['username']); ?></h6>
                    </div>
                    <div class="pull-right">
                        <a href="<?php echo $this->Html->url(array('action' => 'manage_users')); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to Users</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <form method="post" action="<?php echo $this->Html->url(array('action' => 'edit_user', $subUser['ResellerSubUser']['id'])); ?>" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Username</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['User']['username']); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                        <input type="password" class="form-control" name="data[User][password]" placeholder="Enter new password">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <select class="form-control" name="data[User][status]">
                                            <option value="1" <?php echo $userData['User']['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                            <option value="0" <?php echo $userData['User']['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" style="font-size:15px;font-weight:bold;margin-bottom:15px;">User Permissions</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label style="font-size:14px;">
                                                    <input type="checkbox" name="data[Permission][can_add]" value="1" <?php echo $subUser['ResellerSubUser']['can_add'] ? 'checked' : ''; ?> style="width:18px;height:18px;margin-right:8px;">
                                                    <i class="fa fa-plus-circle text-success"></i> Can Add
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label style="font-size:14px;">
                                                    <input type="checkbox" name="data[Permission][can_edit]" value="1" <?php echo $subUser['ResellerSubUser']['can_edit'] ? 'checked' : ''; ?> style="width:18px;height:18px;margin-right:8px;">
                                                    <i class="fa fa-pencil text-warning"></i> Can Edit
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label style="font-size:14px;">
                                                    <input type="checkbox" name="data[Permission][can_delete]" value="1" <?php echo $subUser['ResellerSubUser']['can_delete'] ? 'checked' : ''; ?> style="width:18px;height:18px;margin-right:8px;">
                                                    <i class="fa fa-trash text-danger"></i> Can Delete
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update User</button>
                                    <a href="<?php echo $this->Html->url(array('action' => 'manage_users')); ?>" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
