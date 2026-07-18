<div class="panel-wrapper collapse in">
    <div class="panel-body">
        <div  class="tab-struct custom-tab-2 mt-40">
            <ul role="tablist" class="nav nav-tabs" id="myTabs_25">
                <li class="active" role="presentation" id="allmemberlink" class=""><a  data-toggle="tab" id="profile_tab_25" role="tab" href="#all_member_tariff_supplementary" aria-expanded="false" onclick="allClickSupplementaryLink('all');">All Member's Supplementary Tariff</a></li> 
                <li  role="presentation" id="indmemberlink"><a aria-expanded="true"  data-toggle="tab" role="tab" id="home_tab_25" href="#individual_tariff_supplementary" onclick="allClickSupplementaryLink('idv');">Individual Supplementary Tariff</a></li>
            </ul>
            <input type="hidden" data-parsley-required="false" id="name_suppl_appl" name="data[name_suppl_appl]" value="all"/>
            <div class="tab-content" id="myTabContent_25">
                <!-- Individual Tariff Tab  -->
              <div id="individual_tariff_supplementary" class="tab-pane fade" role="tabpanel">
                <div class="clearfix">
                                    <div class="table-wrap">
                                        <div class="table-responsive">
                                            <table class="table table-bordered padding-td-none padding-th-none">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Membar Name</th>
                                                        <th>Flat No</th>
                                                        <th>Supplementary Name</th>
                                                        <th><p>Is Supplementary Appl </p> 
                                                            <p><input type="checkbox" data-parsley-required="false" onclick="allClickRadio('actual');" 
                                                                name="data[all_suppl_appl]" id ="all_suppl_appl_actual" value="allactual"/><label>All Actual</label>&nbsp;&nbsp;
                                                         <input type="checkbox" data-parsley-required="false" onclick="allClickRadio('area');" 
                                                         name="data[all_suppl_area]" id = "all_suppl_appl_area" value="all_area"/><label>All Area</label></p>
                                                     </th>
                                                        <th>Supplementary Amt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                       if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) :
                                                       foreach ($societyLedgerHeadList as $societyLedgerHeadData) {
                                                        if (isset($societyMemberFlatLists) && count($societyMemberFlatLists) > 0) :
                                                        $s = 1;
                                                        foreach ($societyMemberFlatLists as $societyMemberId => $societyMemberDetailsArray) {
                                                        foreach($societyMemberDetailsArray as $societyMemberFlatNo=>$societyMemberName) {
                                                        ?>
                                                        <tr id="rowid_<?= $societyMemberId?>">
                                                        <td><?= $s ?></td>    
                                                        <td><?=  wordwrap($societyMemberName,15,"<br>\n") ?></td>    
                                                        <td><?= $societyMemberFlatNo ?></td>
                                                        <td><?php echo isset($societyLedgerHeadData['SocietyLedgerHeads']['title'])? $societyLedgerHeadData['SocietyLedgerHeads']['title'] :'';?> <input type="hidden" data-parsley-required="false" id="name_suppl_appl" name="data[supp_head_id]" value="<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'] ?>"/> </td>    
                                                        <td><input type="checkbox" onclick="idvClickRadioSelect('act','<?= $societyMemberId ?>')" data-parsley-required="false" name="data[SupplementaryBillGenerate][is_suppl_appl][<?= $societyMemberId ?>]" id ="is_suppl_appl_actual" value="is_suppl_appl_actual_<?= $societyMemberId?>"/><label>By Actual</label>&nbsp;&nbsp;
                                                         <input type="checkbox" onclick="idvClickRadioSelect('area','<?= $societyMemberId ?>')" data-parsley-required="false" name="data[SupplementaryBillGenerate][is_suppl_area][<?= $societyMemberId ?>]" id = "is_suppl_appl_area" value="is_suppl_appl_area_<?= $societyMemberId?>"/><label>By Area</label>   
                                                        </td>
                                                        <td>
                                                            <input type="text" pattern="[0-9|.]" title="only numbers and digits allowed" id="idv_supplementary_amount_[<?= $societyMemberId?>]" data-parsley-required="false" name="data[SupplementaryBillGenerate][suplementary_amount][<?= $societyMemberId?>]" disabled/>
                                                        </td>
                                                        </tr>    
                                                        <?php $s++;} } endif;}endif;?>
                                                </tbody>
                                            </table>
                                               <?php
                                                   if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) : ?> 
                                                <div class="row">
                                                    <div class="col-md-6 col-md-offset-4 padding-0" style="padding-top:23px;">
                                                        <button type="button" class="btn btn-success btn-anim btn-sm" id="supplementary_bill_generate" href="javascript:void(0);" onclick="checkMemberSupplementaryBillByMonth('sup');">Generate Supplementary Bill</button>
                                                        <a type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-anim btn-sm btns" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>             
                                    </div> 
                </div>
                    
                </div>

                <!-- All Member's Tariff Tab  -->
                <div id="all_member_tariff_supplementary" class="tab-pane fade active in" role="tabpanel">
                   <div class="clearfix">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered padding-td-none padding-th-none">
                                <thead>
                                    <tr>
                                        <th>Ledger Head</th>
                                        <th>Unit/Area <span class="required">*</span></th>
                                        <th>Rate</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) :
                                        $s = 1;
                                        foreach ($societyLedgerHeadList as $societyLedgerHeadData) {?>
                                            <tr>
                                                <td><?php echo isset($societyLedgerHeadData['SocietyLedgerHeads']['title'])? $societyLedgerHeadData['SocietyLedgerHeads']['title'] :'';?> </td>
                                                <td>
                                                    <select class="form-control" data-parsley-required="false" onchange="supplementarySelectChange(this.name,this.value)" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][rateUnitType]">
                                                            <option value="">Select Rate/Unit</option>                                           
                                                            <option value="Actual">Actual</option>                                           
                                                            <option value="Area">Area</option>                                           
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" pattern="[0-9|.]" title="only numbers and digits allowed" data-parsley-required="false" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][rate]" class="memberTariffCount text-right"  style="" disabled>
                                                </td>
                                                <td>
                                                    <input type="text" data-parsley-required="false" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][description]" class="memberTariffCount text-right"  style="" disabled>
                                                </td>
                                            </tr>
                                        <?php $s++;} endif;?>
                                </tbody>
                            </table>
                           <?php
                               if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) : ?> 
                            <div class="row">
                                <div class="col-md-6 col-md-offset-4 padding-0" style="padding-top:23px;">
                                    <button type="button" class="btn btn-success btn-anim btn-sm" id="supplementary_bill_generate" href="javascript:void(0);" onclick="checkMemberSupplementaryBillByMonth('sup');">Generate Supplementary Bill</button>
                                    <a type="button" data-toggle="tooltip" data-original-title="Go to dashboard" class="btn btn-success btn-anim btn-sm btns" href="<?php echo Router::url(array('controller' =>'societys','action' =>'dashboard')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>
                                </div>
                            </div>
                        <?php endif; ?>
                        </div>             
                    </div> 
                   </div>    
                </div>
            </div>
        </div>


    </div>
</div>
<!-- <div class="col-md-12">
    <div class="table-wrap">
        <div class="table-responsive">
            <table class="table table-bordered padding-td-none padding-th-none">
                <thead>
                    <tr>
                        <th>Ledger Head</th>
                        <th>Unit/Area <span class="required">*</span></th>
                        <th>Rate</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($societyLedgerHeadList) && count($societyLedgerHeadList) > 0) :
                        $s = 1;
                        foreach ($societyLedgerHeadList as $societyLedgerHeadData) {?>
                            <tr>
                                <td><?php echo isset($societyLedgerHeadData['SocietyLedgerHeads']['title'])? $societyLedgerHeadData['SocietyLedgerHeads']['title'] :'';?> </td>
                                <td>
                                    <select class="form-control" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][rateUnitType]">
                                            <option value="">Select Rate/Unit</option>                                           
                                            <option value="Actual">Actual</option>                                           
                                            <option value="Area">Area</option>                                           
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][rate]" class="memberTariffCount text-right"  style="">
                                </td>
                                <td>
                                    <input type="text" id="ledger_head_id_<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>" name="data[SupplementaryBillGenerate][<?php echo $societyLedgerHeadData['SocietyLedgerHeads']['id'];?>][description]" class="memberTariffCount text-right"  style="">
                                </td>
                            </tr>
                        <?php $s++;} endif;?>
                </tbody>
            </table>
        </div>             
    </div>	
</div>  -->

<!-- <div class="clearfix">
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
                    </div> -->                 