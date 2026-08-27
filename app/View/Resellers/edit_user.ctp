<style>
.perm-table th { text-align: center; font-weight: bold; font-size: 13px; }
.perm-table td { text-align: center; vertical-align: middle; }
.perm-table td:first-child, .perm-table th:first-child { text-align: left; }
.perm-table .module-name { font-weight: 600; font-size: 14px; }
.perm-table input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
.th-add { color: #27ae60; }
.th-edit { color: #2980b9; }
.th-delete { color: #e74c3c; }
.th-generate { color: #8e44ad; }
.th-view { color: #e67e22; }
.status-toggle { position: relative; display: inline-block; width: 50px; height: 26px; }
.status-toggle input { opacity: 0; width: 0; height: 0; }
.status-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .3s; border-radius: 26px; }
.status-slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
.status-toggle input:checked + .status-slider { background-color: #27ae60; }
.status-toggle input:checked + .status-slider:before { transform: translateX(24px); }
</style>
<?php
$modules = array('dashboard', 'software', 'members', 'bills', 'payments', 'settlement', 'reports', 'settings');
$permActions = array('add', 'edit', 'delete', 'generate', 'view');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"><i class="fa fa-user"></i> Edit User - <?php echo htmlspecialchars($userData['User']['full_name'] ?: $userData['User']['username']); ?></h6>
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
                                    <h5 style="font-weight:bold;margin-bottom:20px;"><i class="fa fa-user"></i> User Information</h5>
                                    <div class="form-group">
                                        <label class="control-label">Full Name</label>
                                        <input type="text" class="form-control" name="data[User][full_name]" value="<?php echo htmlspecialchars($userData['User']['full_name']); ?>" placeholder="Enter full name">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Email Address</label>
                                        <input type="email" class="form-control" name="data[User][email]" value="<?php echo htmlspecialchars($userData['User']['email']); ?>" placeholder="Enter email address">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Mobile Number</label>
                                        <input type="text" class="form-control" name="data[User][mobile]" value="<?php echo htmlspecialchars($userData['User']['mobile']); ?>" placeholder="Enter mobile number" maxlength="10">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Username</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['User']['username']); ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                        <input type="password" class="form-control" name="data[User][password]" placeholder="Enter new password" autocomplete="new-password">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <div>
                                            <label class="status-toggle">
                                                <input type="checkbox" name="data[User][status]" value="1" <?php echo $userData['User']['status'] == 1 ? 'checked' : ''; ?>>
                                                <span class="status-slider"></span>
                                            </label>
                                            <span style="margin-left:10px;font-weight:bold;" class="<?php echo $userData['User']['status'] == 1 ? 'text-success' : 'text-danger'; ?>"><?php echo $userData['User']['status'] == 1 ? 'Active' : 'Inactive'; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 style="font-weight:bold;margin-bottom:20px;"><i class="fa fa-shield"></i> Set Permissions</h5>
                                    <p class="text-muted" style="margin-bottom:15px;">Check the permissions you want to assign to this user.</p>
                                    <div class="table-responsive">
                                        <table class="table table-bordered perm-table">
                                            <thead>
                                                <tr style="background:#f5f5f5;">
                                                    <th>MODULE</th>
                                                    <th class="th-add">ADD</th>
                                                    <th class="th-edit">EDIT</th>
                                                    <th class="th-delete">DELETE</th>
                                                    <th class="th-generate">GENERATE</th>
                                                    <th class="th-view">VIEW</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($modules as $mod) { ?>
                                                <tr>
                                                    <td class="module-name"><?php echo ucfirst($mod); ?></td>
                                                    <?php foreach ($permActions as $act) { $key = $mod . '_' . $act; ?>
                                                    <td><input type="checkbox" name="data[Permission][<?php echo $key; ?>]" value="1" <?php echo !empty($subUser['ResellerSubUser'][$key]) ? 'checked' : ''; ?>></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="<?php echo $this->Html->url(array('action' => 'manage_users')); ?>" class="btn btn-default"><i class="fa fa-times"></i> Cancel</a>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
