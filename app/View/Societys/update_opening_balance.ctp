<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <style>
                    .db-panel {
                        display: flex;
                        flex-direction: row;
                        flex-wrap: wrap;
                        justify-content: space-between;
                        align-items: center;
                    }
                    </style>
                    <div class="db-panel">
                        <div>
                            <h6 class="panel-title txt-dark">Bulk Opening Balance Update</h6>
                        </div>
                        <div>
                            <?php 
                                echo $this->Html->link(
                                    '<i class="fa fa-download"></i> Download Member Details',
                                    array(
                                        'controller' => 'societys',
                                        'action' => 'download_member_details'
                                    ),
                                    array(
                                        'class' => 'btn btn-success',
                                        'data-toggle' => 'tooltip',
                                        'data-original-title' => 'Download Member Details Sample CSV',
                                        'style' => 'margin-right:10px;',
                                        'escape' => false
                                    )
                                );
                                ?>
                        </div>
                    </div>

                    <div class="panel-wrapper collapse in pt-40 " style="width:50%">
                        <form method="post" 
                            action="<?php echo $this->Html->url(array('controller' => 'societys', 'action' => 'upload_member_details')); ?>" 
                            enctype="multipart/form-data" 
                            name="member_identitys_upload">


                            <div class="form-group">
                                <label for="form-control-1" class="control-label">Member Details Update</label>
                                <input type="file" class="form-control" name="data[Member][member_csv]" id="member_csv"
                                    required="" />
                            </div>
                            <span class="block pb-25 text-danger"><strong>Note:</strong> Please update only the Principal Balance, Interest Balance, and Tax Balance fields.</span>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Upload CSV</button>
                            </div>
                        </form>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>