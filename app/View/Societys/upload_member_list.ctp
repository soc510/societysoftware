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
                    <form id="" method="post" action="/societys_members/uploadSocietyMemberListCsv" enctype="multipart/form-data" name="member_identitys_upload">
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
                        <button type="submit" class="btn btn-success">Upload CSV</button>
                    </div>
                </form>
                </div>	
            </div>
        </div>
    </div>
</div>