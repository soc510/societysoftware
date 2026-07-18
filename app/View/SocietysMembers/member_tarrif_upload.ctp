<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Taffif Upload / Download</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in " style="width:50%">
                <form action="/societys_members/downloadSocietyMemberTariffData" id="" name="member_tariff_download_csv_file" method="post" enctype="multipart/form-data" >
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
                        <button type="submit" class="btn btn-info" >Download CSV</button>
                    </div>
                </form>
                </div>	
                <div class="panel-wrapper collapse in " style="width:50%">
                    <form action="/societys_members/uploadSocietyMemberTariffData" method="post"  enctype="multipart/form-data" >
                        <div class="form-group">
                                <label for="form-control-1" class="control-label">Client CSV </label>
                                <input type="file" class="form-control" name="data[MemberTariff][member_tariff_csv]" id="member_tariff_csv" required=""/>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Upload CSV</button>
                        </div>
                    </form>
                </div>	
            </div>
        </div>
    </div>
</div>