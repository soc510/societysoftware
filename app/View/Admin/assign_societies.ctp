<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Assign Society Login To Reseller</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_assign_reseller_error">  
                            </div>
                            <?php
                            echo $this->Form->create('ResellerSociety',array('horizontal' => true,'class'=>'form-horizontal','name'=>'ResellerSocietyAssignSocietiesForm','onsubmit'=>"return validateResellerSocietyAssign();"));
                            ?>
                               <div class="form-group">
                                    <label class="col-sm-2 mb-10 control-label">Select Reseller<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="reseller_id" name="data[ResellerSociety][reseller_id]" onchange="getAssignedSocieties(this.value);" required="">
                                            <option value="">Select</option>
                                            <?php
                                            if (isset($resellerList) && !empty($resellerList)) {
                                                foreach ($resellerList as $key => $value) {
                                                    if (isset($dataArr['inward_credit']['party_type']) && $dataArr['inward_credit']['party_type'] == $key) {
                                                        echo '<option  value="' . $key . '" selected="selected" >' . $value . '</option>';
                                                    } else {
                                                        echo '<option value="' . $key . '" >' . $value . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 mb-10 control-label">Select Society<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="societie_id"  name="data[ResellerSociety][societie_id][]" multiple>
                                            <option value="">Select</option>
                                            <?php
                                            if (isset($societyList) && !empty($societyList)) {
                                                foreach ($societyList as $key => $value) {  
                                                        echo '<option value="'.$value['Society']['id'].'">'.$value['Society']['society_name'].'</option>';
                                                    
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-0"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                         <?php if (isset($singleSocietyRecord['User']['id']) && $singleSocietyRecord['User']['id'] != '') { ?>    
                                        <button type="submit" class="btn btn-success btn-anim" onclick="addUpdateSocietyLoginCredentials();"><i class="icon-rocket"></i><span class="btn-text">Update</span></button>
                                        <?php }else {?>
                                        <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Assign Society</span></button>
                                        <?php }?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>