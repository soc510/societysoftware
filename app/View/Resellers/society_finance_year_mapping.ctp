<?php  
    $yearList = $this->request->data['year_list'];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            	<?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Society Name</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form  method="post" id="AddSociety" name="AddSociety" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">    
                                    <div class="form-group">
                                       <label for="exampleInputPassword1" class="form-label">Society Name</label>
                                        <select  class="form-control" name="society_id">
                                        <?php
                                        if(!empty($societydata)){
                                            foreach($societydata as $society){
                                       ?>                
                                            <option value="<?= $society['soc']['id']; ?>"><?= $society['soc']['society_name']; ?></option>
                                            
                                        <?php }}?>  
                                        </select>
                                    </div>
                                </div>
                                    <div class="row">    
                                        <div class="form-group">
                                            <label for="exampleInputPassword1" class="form-label">Financial Year</label>
                                            <div class="">
                                                <select  class="form-control" name="financial_year_id">
                                                    <option>Select Year</option>
                                                <?php
                                                if(!empty($yearList)){
                                                    foreach($yearList as $year){
                                               ?>                
                                                    <option value="<?= $year['FinancialYearMaster']['id']; ?>"><?= $year['FinancialYearMaster']['year']; ?></option>
                                                    
                                                <?php }}?>
                                                </select>                                                
                                            </div>                                        
                                        </div>
                                    </div>                                   
                                <button class="btn btn-primary " type="submit" style="margin-left:48%;"> save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

