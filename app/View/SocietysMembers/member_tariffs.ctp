<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Regular Bill Tariff Master</h6>
                    </div>
                    <div class="pull-right">
                            <?php echo $this->Html->link('<i class="fa fa-upload" style="color:#fff;font-size:10px;"></i> Upload Member Tarrif CSV', array('controller' => 'societys_members','action' =>'member_tarrif_upload'), array('class' => 'btn btn-warning','data-toggle' => 'tooltip','target' => '_blank','data-original-title' => 'Upload member Tarrif using CSV','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                             <?php // echo $this->Html->link('<i class="fa fa-upload" style="color:#fff;font-size:10px;"></i> Upload Member Tariff CSV','javascript:void(0);',array('onclick'=>"loadSocietyBillsModel('#upload_Member_Tariff_CSV');",'class' => 'btn btn-warning','data-toggle' => 'tooltip', 'data-original-title' => 'Upload member list using CSV','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                             <?php //  echo $this->Html->link('<i class="fa fa-download" style="color:#fff;font-size:10px;"></i> Download Member Tariff CSV','javascript:void(0);',array('onclick'=>"loadSocietyBillsModel('#member_tariff_download_csv');",'class' => 'btn btn-primary','data-toggle' => 'tooltip', 'data-original-title' => 'Upload member list using CSV','style'=>array('margin-right:10px;'),'escape' => false)); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div  class="tab-struct custom-tab-2 mt-40">
                            <ul role="tablist" class="nav nav-tabs" id="myTabs_15">
                                <li class="active" role="presentation"><a aria-expanded="true"  data-toggle="tab" role="tab" id="home_tab_15" href="#individual_tariff">Individual Tariff</a></li>
                                <li role="presentation" class=""><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#all_member_tariff" aria-expanded="false">All Member's Tariff</a></li>                               
                            </ul>
                            <div class="tab-content" id="myTabContent_15">
                                <!-- Individual Tariff Tab  -->
                                <div id="individual_tariff" class="tab-pane fade active in" role="tabpanel">
                                    <div class="clearfix">
                                        <div class="form-wrap">
                                            <div id="show_notify_error"></div>
                                            <div class="col-md-8 member-tariff-left-box"> 
                                                <form class="" method="post" id="memberTariffForm" name="memberTariffForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                                    <div class="row">
                                                        <div class="col-md-4">    
                                                            <div class="form-group">
                                                                <label class="control-label" for="title">Society Members</label>
                                                                <select class="form-control select2" id="society_head_sub_category_id" name="data[MemberTariff][member_id]" onchange="loadMemberTarrifDetails(this.value);">
                                                                    <option value="">Choose  Members </option>
                                                                    <optgroup label="Society Members List">
                                                                    <?php
                                                                    if (isset($societyMemberFlatLists) && count($societyMemberFlatLists) > 0) :
                                                                        $s = 1;
                                                                        foreach ($societyMemberFlatLists as $societyMemberId => $societyMemberDetailsArray) {
                                                                            foreach($societyMemberDetailsArray as $societyMemberFlatNo=>$societyMemberName) {
                                                                            if (isset($this->request->data['MemberTariff']['member_id']) && $this->request->data['MemberTariff']['member_id'] == $societyMemberId) {
                                                                                ?>
                                                                            <option value="<?php echo $societyMemberId; ?>" selected=""><?php echo $societyMemberName; ?></option>
                                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $societyMemberId; ?>"><?php echo "$societyMemberName ($societyMemberFlatNo)"; ?></option>
                                                                                  <?php
                                                                                $s++;
                                                                            }
                                                                        } } endif;
                                                                    ?>  
                                                                    </optgroup>
                                                                </select> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">                                
                                                            <div class="form-group">
                                                                <label class="control-label" for="effective_date">Effective Date :</label>
                                                                <input type="date" class="form-control" id="effective_date" name="data[MemberTariffDetails][tariff_effective_since]" required="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">                                
                                                            <div class="form-group">
                                                                <label class="control-label" for="member_tariff_flat_no">Unit No :</label>
                                                                <input type="text" class="form-control" id="member_tariff_flat_no" readonly="">
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
                                                                        <input type="text" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[MemberTariff][ledger_head_id][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>]" class="memberTariffCount text-right"  style="">
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                 $s++;
                                                               } endif;
                                                               ?>
                                                                <tr>
                                                                    <td></td>
                                                                    <td><b>Total</b></td>
                                                                    <td><input readonly="" type="text" style="" class="text-right" id="total_member_tariff"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">                                
                                                            <div class="form-group">
                                                                <label class="control-label" for="Remark">Remark :</label>
                                                                <textarea class=""rows="3" style="width:100%;" id="tariff_remark" name="data[MemberTariffDetails][remark]"></textarea>
                                                            </div>
                                                        </div>
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
                                                                        <th class="text-center">RATE</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="t_member_tariff_details">                                                      
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>                                       
                                                </div>    
                                            </div>                  
                                        </div>
                                    </div>
                                </div>

                                <!-- All Member's Tariff Tab  -->
                                <div id="all_member_tariff" class="tab-pane fade" role="tabpanel">
                                    <div class="table-wrap">
                                        <form class="form-horizontal" method="post" id="allMembersTariffForm" name="allMembersTariffForm" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley" autocomplete="off"> 
                                            <div class="row">
                                                <div class="col-md-4">    
                                                    <div class="form-group">   
                                                        <label for="building_id" class="control-label mb-10 col-sm-4">Building Name <span class="required">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control input-group-lg" id="building_id" name="data[MemberPayment][building_id]" onchange="getAllBuildingWings(this.value, '#dues_from_member');">
                                                                <option value="">Select building</option>
                                                            <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                                                            foreach($societyBuildingLists as $buildingID => $buildingName){?>
                                                            <?php if(isset($postData['MemberPayment']['building_id']) && $postData['MemberPayment']['building_id'] == $buildingID){?>
                                                                <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                                                            <?php }else {?>
                                                                <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                                                            <?php }?>
                                                            <?php } }?> 
                                                            </select> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">                                
                                                    <div class="form-group">
                                                        <label class="control-label mb-10 col-sm-2" for="wing_id">Wing</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" id="wing_id" name="data[MemberPayment][wing_id]">
                                                                <option value="">Select Wing</option>  
                                                            </select> 
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">      
                                                    <button type="button" class="btn  btn-info btn-outline btn-rounded" onclick="getAllMemberTariffData();"><i class="fa fa-eye"></i><span> Show</span></button>
                                                </div> 
                                            </div>
                                            <div class="table-responsive" id="all_member_tariff_data">
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 margin-top-3"><button type="button" data-toggle="tooltip" data-original-title="Submit" onclick="updateAllMemberTariffData();" class="btn btn-success btn-anim">Update Member Tariff</button></div>  
                                            </div>
                                        </form>
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