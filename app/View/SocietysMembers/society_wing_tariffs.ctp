<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Regular Bill Tariff Master</h6>
                    </div>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <div class="col-md-8 member-tariff-left-box"> 
                                <form class="" method="post" id="societyTariffForm" name="societyTariffForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                    <div class="row">
                                        <div class="col-md-4">    
                                            <div class="form-group">
                                                <label class="control-label" for="title">Society Buildings</label>
                                                    <select class="form-control" id="building_id" name="data[SocietyTariffWings][building_id]" onchange="getAllBuildingWings(this.value, '#wing_id');">
                                                        <option value="">Select Building</option>
                                                            <?php
                                                            if (isset($societyBuildingLists)) {
                                                                foreach ($societyBuildingLists as $buildingId => $buildingName) { ?>
								<option value="<?php echo $buildingId; ?>"><?php echo $buildingName; ?></option>
                                                            <?php } }?>  
                                                    </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-3">    
                                            <div class="form-group">
                                                <label class="control-label" for="title">Wings</label>
                                                    <select class="form-control" id="wing_id" name="data[SocietyTariffWings][wing_id]" onchange="loadWingTarrifDetails(this.value);">
                                                        <option value="">Select Wing</option>                                                           
                                                    </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-4">                                
                                            <div class="form-group">
                                                <label class="control-label" for="effective_date">Effective Date :</label>
                                                <input type="date" class="form-control" id="effective_date" name="data[SocietyTariffWings][effective_date]" required="">
                                            </div>
                                        </div>                                        
                                    </div>  
                                    <div class="table-responsive">
                                        <table id="" class="table table-bordered padding-th-none padding-td-none " style="width:82%;">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Particulars</th>
                                                    <th>Rate</th>                                                                                                    
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                    <?php          
                                                    if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) :
                                                    $s = 1;                                           
                                                    foreach ($societyLedgerHeadList as $key => $societyLedgerHeadData) {
                                                           ?>
                                                <tr>
                                                    <td>
                                                        <label class="control-label " for="sr"><?php echo $s;?></label>
                                                    </td>
                                                    <td>
                                                        <label class="" for="head"><?php echo $societyLedgerHeadData['SocietyLedgerHeads']['title'];?></label>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[SocietyTariffWings][ledger_head_id][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>]" class="memberTariffCount text-right"  style="">
                                                    </td>
                                                </tr>
                                                    <?php
                                                     $s++;
                                                   } endif;
                                                   ?>
                                                <tr>
                                                    <td></td>
                                                    <td><b>Total</b></td>
                                                    <td><input readonly="" type="text" style="" class="text-right" id="total_wing_tariff"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 margin-top-3"><button type="submit" data-toggle="tooltip" data-original-title="Submit" class="btn btn-success btn-anim">Submit</button></div>  
                                    </div>
                                </form>  
                            </div> 
                            <div class="col-md-4">
                                <div class="row">   
                                   <div class="table-wrap">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover padding-th-none padding-td-none">
                                                <thead>
                                                    <tr>
                                                        <th>PARTICULARS</th>
                                                        <th>RATE</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="t_wing_tariff_details">                                                      
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
        </div>
    </div>
</div>