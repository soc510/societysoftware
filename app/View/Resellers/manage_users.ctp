<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view bg-white">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark"><i class="fa fa-users"></i> User List</h6>
                    </div>
                    <div class="pull-right">
                        <a href="<?php echo $this->Html->url(array('controller' => 'resellers', 'action' => 'add_user')); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New User</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="datable_1" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($subUsers)){
                                        $count = 1;
                                        foreach($subUsers as $sub){
                                            $su = $sub['ResellerSubUser'];
                                            $u = isset($userMap[$su['user_id']]) ? $userMap[$su['user_id']] : array();
                                            $uName = isset($u['full_name']) && $u['full_name'] ? $u['full_name'] : (isset($u['username']) ? $u['username'] : 'N/A');
                                            $uMobile = isset($u['mobile']) ? $u['mobile'] : '-';
                                            $uEmail = isset($u['email']) && $u['email'] ? $u['email'] : '-';
                                            $uStatus = isset($u['status']) ? $u['status'] : 0;
                                    ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo htmlspecialchars($uName); ?></td>
                                            <td><?php echo htmlspecialchars($uMobile); ?></td>
                                            <td><?php echo htmlspecialchars($uEmail); ?></td>
                                            <td><?php echo $uStatus ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>'; ?></td>
                                            <td><?php echo date('d M Y', strtotime($su['created'])); ?></td>
                                            <td>
                                                <a href="<?php echo $this->Html->url(array('action' => 'edit_user', $su['id'])); ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <?php if($uStatus){ ?>
                                                <a href="<?php echo $this->Html->url(array('action' => 'delete_user', $su['id'])); ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Deactivate" onclick="return confirm('Are you sure you want to deactivate this user?');"><i class="fa fa-ban"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php $count++; }} else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No users found. Click "Add New User" to create one.</td>
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
