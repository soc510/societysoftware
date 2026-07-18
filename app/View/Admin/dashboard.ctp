

<div class="container-fluid pt-10">
    <div class="row">
        <?php echo $this->Session->flash(); ?>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-red">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter"><span class="counter-anim"><?php echo isset($countReseller) ? $countReseller : 0; ?></span></span>
                                        <span class="weight-500 uppercase-font txt-light block font-13">No. Of Resellers</span>

                                    </div>
                                    <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                    <i class="fa fa-users txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                                <div class="row"> 
                                    <div class="col-xs-12 ">
                                        <span class="block txt-light font-15">Societies assign to Reseller -> &nbsp;&nbsp; <span class="txt-dark weight-500 font-20"><?php echo isset($resellerSocietiesCount) ? $resellerSocietiesCount : 0; ?></span></span>                                        
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-yellow">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter"><span class="counter-anim"><?php echo isset($countSocieties) ? $countSocieties :0; ?></span></span>
                                        <span class="weight-500 uppercase-font txt-light block">No. of Societies</span>
                                    </div>
                                    <div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
                                        <i class="fa fa-building-o txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>	
                                <div class="row"> 
                                    <div class="col-xs-12">
                                        <span class="block txt-light font-15">Members-> &nbsp;&nbsp; <span class="txt-dark weight-500 font-20"><?php echo isset($allSocietysMemberCount) ? $allSocietysMemberCount :0; ?></span></span>                                        
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-green" style="height:130px;">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter">
                                            <span class="counter-anim"><?php echo isset($currentYearSocietiesLists) ? $currentYearSocietiesLists :0; ?></span></span>
                                        <span class="weight-500 uppercase-font txt-light block">New Societies This Year</span>
                                    </div>
                                    <div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
                                        <i class="zmdi zmdi-city-alt txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-blue" style="height:130px;">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-7 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter">
                                            <span class="counter-anim"><?php echo isset($droppedSocietiesLists) ? $droppedSocietiesLists :0; ?></span></span>
                                        <span class="weight-500 uppercase-font txt-light block">Societies Dropped</span>
                                    </div>
                                    <div class="col-xs-5 text-center  pl-0 pr-0 data-wrap-right">
                                        <i class="fa fa-times-circle txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

    <br>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Resellers Societies</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block refresh mr-15">
                            <i class="zmdi zmdi-replay"></i>
                        </a>
                        <a href="#" class="pull-left inline-block full-screen mr-15">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>No. of Societies</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($resellerAssignSocietys) && count($resellerAssignSocietys) > 0){
                                     $idCount = 1;
                                     foreach($resellerAssignSocietys as $resellerAssignData){
                                  ?>
                                    <tr id="<?php echo $idCount; ?>">
                                        <td><?php echo $idCount; ?></td>
                                        <td><?php echo isset($resellerAssignData['resellerData']['username']) ? $resellerAssignData['resellerData']['username'] :'' ; ?></td>
                                        <td>
                                            <?php if(isset($resellerAssignData['assignSocietiesData']) && count($resellerAssignData['assignSocietiesData']) > 0){ ?>
                                            <select class="form-control" id="ressellerSociety" name="ressellerSociety">
                                                <?php foreach($resellerAssignData['assignSocietiesData'] as $societyFinalInfo){?>
                                                <option value="<?php echo isset($societyFinalInfo['Society']['id']) ? $societyFinalInfo['Society']['id'] :0; ?>"><?php echo isset($societyFinalInfo['Society']['society_name']) ? $societyFinalInfo['Society']['society_name'] :0; ?></option>
                                                <?php }?>
                                            </select>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php $idCount++;}}?>   
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Societies</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block refresh mr-15">
                            <i class="zmdi zmdi-replay"></i>
                        </a>
                        <a href="#" class="pull-left inline-block full-screen mr-15">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-striped table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Society Name</th>
                                    <th>Society Registration No</th>
                                    <th>Telephone No</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php if(isset($allSocietiesLists) && count($allSocietiesLists) > 0){
                                     $idCount = 1;
                                     foreach($allSocietiesLists as $societyData){
                                  ?>
                                <tr id="<?php echo $idCount; ?>">
                                    <td><?php echo $idCount; ?></td>
                                    <td><?php echo isset($societyData['Society'][0]['society_name']) ? $societyData['Society'][0]['society_name'] :'' ; ?></td>
                                    <td><?php echo isset($societyData['Society'][0]['registration_no']) ? $societyData['Society'][0]['registration_no'] :'' ; ?></td>
                                    <td><?php echo isset($societyData['Society'][0]['telephone_no']) ? $societyData['Society'][0]['telephone_no'] :'' ; ?></td>
                                </tr>
                                <?php $idCount++;}
                                    echo "<tr id=\"$idCount\"><td class=\"text-center\" colspan=\"4\">".$this->Html->link('Click here',array('controller'=>'admin','action'=>'view_societys'),array('style'=>'color:#0f4fa8;font-size:15px;','escape'=>false))." to view all societies</td></tr>";
                                     }?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
                
    </div>    
    
</div>