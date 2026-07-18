<div class="modal fade" id="upload_Member_Identitys_CSV" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h5 class="modal-title" id="exampleModalLabel1">Upload Member Identitys</h5>
            </div>
            <div class="modal-body">
                <div id="show_bill_notify_error"></div>
                <form id="member_identitys_upload_csv" name="member_identitys_upload" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Building<span class="required">*</span></label>
                        <select name="data[Member][building_id]" class="form-control" onchange="getAllBuildingWings(this.value,'#member_bill_wing_id');" required="">
                            <option value="">Select building</option>
                            <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                            foreach($societyBuildingLists as $buildingID => $buildingName){?>
                            <?php if(isset($this->request->data['Member']['building_id']) && $this->request->data['Member']['building_id'] == $buildingID){?>
                            <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                            <?php }else {?>
                            <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                            <?php }?>
                            <?php } }?>
                        </select>
                     </div>
                    <div class="form-group">
                        <label for="" class="control-label">Wing Name</label>                               
                        <select name="data[Member][wing_id]" id="member_bill_wing_id" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                            <label for="form-control-1" class="control-label">Client CSV </label>
                            <input type="file" class="form-control" name="data[Member][member_csv]" id="member_csv" required=""/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" href="javascript:void(0);" onclick="uploadMemberIdentitysCSV();">Upload CSV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload_Member_Tariff_CSV" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h5 class="modal-title" id="exampleModalLabel1">Upload MemberTariff Identitys</h5>
            </div>
            <div class="modal-body">
                <div id="show_bill_notify_error"></div>
                <form id="member_tariff_upload_csv" name="member_tariff_upload_csv" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                    <div class="form-group">
                            <label for="form-control-1" class="control-label">Client CSV </label>
                            <input type="file" class="form-control" name="data[MemberTariff][member_tariff_csv]" id="member_tariff_csv" required=""/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" href="javascript:void(0);" onclick="uploadMemberTariffIdentitysCSV();">Upload CSV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="member_tariff_download_csv" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h5 class="modal-title" id="exampleModalLabel1">Upload MemberTariff Identitys</h5>
            </div>
            <div class="modal-body">
                <div id="show_bill_notify_error"></div>
                <form id="member_tariff_download_csv_file" name="member_tariff_download_csv_file" data-parsley-trigger="blur" class="form_parsley" data-validate="parsley">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Building<span class="required">*</span></label>
                        <select name="data[MemberTariff][building_id]" class="form-control" onchange="getAllBuildingWings(this.value,'#member_tariff_wing_id');" required>
                            <option value="">Select building</option>
                            <?php if(isset($societyBuildingLists) && count($societyBuildingLists) > 0) {
                            foreach($societyBuildingLists as $buildingID => $buildingName){?>
                            <?php if(isset($this->request->data['MemberTariff']['building_id']) && $this->request->data['MemberTariff']['building_id'] == $buildingID){?>
                            <option value="<?php echo $buildingID; ?>" selected><?php echo $buildingName; ?></option>
                            <?php }else {?>
                            <option value="<?php echo $buildingID; ?>"><?php echo $buildingName; ?></option>
                            <?php }?>
                            <?php } }?>
                        </select>
                     </div>
                    <div class="form-group">
                        <label for="" class="control-label">Wing Name</label>                               
                        <select name="data[MemberTariff][wing_id]" id="member_tariff_wing_id" class="form-control">
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" href="javascript:void(0);" onclick="downloadMemberTariffIdentitysCSV();">Download CSV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>