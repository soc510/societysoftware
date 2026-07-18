<div class="container-fluid">

    <div class="row">

        <div class="col-sm-12">

            <div class="panel panel-default card-view">

                <?php echo $this->Session->flash(); ?>

                <div class="panel-heading">

                    <div class="pull-left">

                        <h6 class="panel-title txt-dark">Create Member Logins</h6>

                    </div>                    

                    <div class="pull-right">
                     

                     </div>

                    <div class="clearfix"></div>

                </div>

                <div class="panel-wrapper collapse in">

                    <div class="panel-body">

<!--h5>Upload File</h5-->
<div class="content">
    <div class="upload-frm">
        <?php echo $this->Form->create('data', ['type' => 'file']); ?>
            <input type="hidden" name="createlogin" value="1" />
            <?php echo $this->Form->button(__('Create Member Logins'), ['type'=>'submit', 'class' => 'form-controlbtn btn-default']); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

                    </div>

                </div>

              </div>

	</div>

    </div>

</div>

