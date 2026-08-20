<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Upload Member Opening Balance CSV</h6>
                    </div>
                    <div class="pull-right">
                        <?php echo $this->Html->link('<i class="fa fa-arrow-left" style="color:#fff;font-size:10px;"></i> Back to Members', array('controller' => 'societys','action' => 'member_identitys'), array('class' => 'btn btn-primary','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in" style="width:50%">
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <strong>Instructions:</strong><br>
                            1. First download the "Member Opening Balance" template from Members page.<br>
                            2. Edit the CSV file in Excel - update Mobile No, Email, Area, Opening Principal, Interest, Tax.<br>
                            3. Save the file as CSV and upload here.<br>
                            4. Data will be updated/overwritten for matching Member IDs.
                        </div>
                        <form method="post" action="<?php echo $this->Html->url(array('controller' => 'societys', 'action' => 'upload_member_op_balance')); ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="member_csv" class="control-label">Select CSV File <span class="required">*</span></label>
                                <input type="file" class="form-control" name="data[Member][member_csv]" id="member_csv" accept=".csv" required="">
                            </div>
                            <div class="modal-footer">
                                <?php echo $this->Html->link('Close', array('controller' => 'societys','action' => 'member_identitys'), array('class' => 'btn btn-default')); ?>
                                <button type="submit" class="btn btn-success">Upload & Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
