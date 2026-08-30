<style>
.perm-table th { text-align: center; font-weight: bold; font-size: 13px; }
.perm-table td { text-align: center; vertical-align: middle; }
.perm-table td:first-child, .perm-table th:first-child { text-align: left; }
.perm-table .module-name { font-weight: 600; font-size: 14px; }
.th-add { color: #27ae60; }
.th-edit { color: #2980b9; }
.th-delete { color: #e74c3c; }
.th-generate { color: #8e44ad; }
.th-view { color: #e67e22; }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"><i class="fa fa-shield"></i> User Permissions Overview</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <?php if(!empty($subUsers)){ foreach($subUsers as $sub){
                            $su = $sub['ResellerSubUser'];
                            $u = isset($userMap[$su['user_id']]) ? $userMap[$su['user_id']] : array();
                            $uName = isset($u['full_name']) && $u['full_name'] ? $u['full_name'] : (isset($u['username']) ? $u['username'] : 'N/A');
                        ?>
                        <div class="panel panel-info" style="margin-bottom:20px;">
                            <div class="panel-heading" style="padding:10px 15px;">
                                <strong><i class="fa fa-user"></i> <?php echo htmlspecialchars($uName); ?></strong>
                                <a href="<?php echo $this->Html->url(array('action' => 'edit_user', $su['id'])); ?>" class="btn btn-xs btn-primary pull-right"><i class="fa fa-pencil"></i> Edit</a>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body" style="padding:10px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed perm-table" style="margin-bottom:0;">
                                        <thead>
                                            <tr style="background:#f9f9f9;">
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
                                                <td>
                                                    <?php if(!empty($su[$key])){ ?>
                                                        <i class="fa fa-check-circle text-success" style="font-size:18px;"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-minus-circle text-muted" style="font-size:16px;"></i>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php }} else { ?>
                        <div class="text-center" style="padding:40px;">
                            <i class="fa fa-users" style="font-size:48px;color:#ccc;"></i>
                            <p class="text-muted" style="margin-top:15px;">No users found. <a href="<?php echo $this->Html->url(array('action' => 'add_user')); ?>">Create a user</a> first.</p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
