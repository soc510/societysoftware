<?php $paymentModeArray = array(1=>'Cash',2=>'Cheque',3=>'NEFT',4=>'Other');

?>

<div class="container-fluid">

    <div class="row">

        <div class="col-sm-12">

            <div class="panel panel-default card-view">

                <?php echo $this->Session->flash(); ?>

                <div class="panel-heading">

                    <div class="pull-left">

                        <h6 class="panel-title txt-dark">Import Society Payments - test</h6>

                    </div>                    

                    <div class="pull-right">
                        
                             <?php echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Download Sample Template file','/files/SampleImportSocietyPayment.xlsx', array('class' => 'btn  btn-success','data-toggle' => 'tooltip', 'data-original-title' => 'Download society payment sample excel file','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                             <button class='btn  btn-success' onclick="generateSampleFileForSocietyPayment()" ><i class="fa fa-download" style="color:#fff;font-size:10px;"></i>&nbsp;Download Sample Template file with Flat No</button>
                             
                     

                     </div>

                    <div class="clearfix"></div>

                </div>

                <div class="panel-wrapper collapse in">

                    <div class="panel-body">

<!--h5>Upload File</h5-->
<div class="content">
    <div class="upload-frm">
	<!--form action='' method='post' enctype='multipart/data'>
		<input type='file' name='file'>
		<input type='submit' value='submit'>
	</form-->

        <?php echo $this->Form->create('data',['type' => 'file'],array('class' => 'form-control','style' =>array('font-size:20px;width:50%'))); ?>
            <?php echo $this->Form->input('society_bank',array('options' => $societyBankLists)); ?>
            <?php echo $this->Form->input('file', ['type' => 'file'],array('class' => 'form-control','style'=> array('width:50%;'))); echo "<br>";?>
            <?php echo $this->Form->button(__('Upload File'), ['type'=>'submit', 'class' => 'form-control btn btn-info','style'=> array('width:50%;color:#FFF')]); ?>
        <?php echo $this->Form->end(); ?>        
    </div>
</div>

                    </div>

                </div>

              </div>

	</div>

    </div>

</div>