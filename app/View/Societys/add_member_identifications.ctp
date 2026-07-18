<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            	<?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Member Identification</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <div id="show_notify_error"></div>
                            <form  method="post" class="form-horizontal" id="memberIdentificationForm" name="memberIdentificationForm" autocomplete="off" enctype="multipart/form-data">                                								
                                <div class="row">
                                    <div class="col-md-3">    
                                        <div class="form-group">

                                            <label class="control-label" for="member_name">Society Flat/Shop<span class="required">*</span></label>
                                            <div class="">
                                                <select class="form-control" id="member_identification_flat_no" name="data[MemberIdentification][flat_no]" onchange="setCurentMemberInMemberTranfer()" required>
                                                    <option value="">Select Flat No</option>
                                                    <?php
                                                    if (isset($societyMemberFlatLists) && count($societyMemberFlatLists) > 0) :
                                                    foreach ($societyMemberFlatLists as $societyMemberId => $societyMemberDetailsArray) {

                                                        foreach($societyMemberDetailsArray as $societyMemberFlatNo => $societyMemberName) {?>

                                                            <option value="<?php 
                                                            echo $societyMemberFlatNo; ?>__<?php echo $societyMemberId;?>__<?php echo $societyMemberDetailsArray[$societyMemberFlatNo];?>"  <?=  (isset($postData['MemberIdentification']['flat_no']) && $postData['MemberIdentification']['flat_no'] == $societyMemberFlatNo) ?  "selected='selected'"  : '' ;?>><?php echo $societyMemberFlatNo; ?></option>
                                                            <?php
                                                        } } 
                                                    endif;
                                                        ?>  
                                                    </select>  
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                    <div  class="tab-struct custom-tab-2 mt-40">
                                        <ul role="tablist" class="nav nav-tabs" id="myTabs_15">
                                            <li class="active" role="presentation"><a aria-expanded="true"  data-toggle="tab" role="tab" id="home_tab_15" href="#member_identification">Member</a></li>
                                            <li role="presentation" class=""><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#personal_identificaton" aria-expanded="false">Personal</a></li>
                                            <li role="presentation" class=""><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#shares_details" aria-expanded="false">Shares</a></li>                                        
                                            <li role="presentation" class=""><a  data-toggle="tab" id="profile_tab_15" role="tab" href="#iform_details" aria-expanded="false">I Form</a></li>                                        
                                        </ul>
                                        <div class="tab-content" id="myTabContent_15">
                                            <div id="member_identification" class="tab-pane fade active in" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <input type="hidden" id="member_id" name="data[MemberIdentification][member_id]" value="<?php echo isset($postData['MemberIdentification']['member_id']) ? $postData['MemberIdentification']['member_id'] :'' ; ?>">            
                                                            <input type="hidden" id="id" name="data[MemberIdentification][id]" value="<?php echo isset($postData['MemberIdentification']['id']) ? $postData['MemberIdentification']['id'] :'' ; ?>">			
                                                            <label class="control-label mb-10 col-sm-4" for="second_member">Flat Transfer To</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="second_member" name="data[MemberIdentification][second_member]" value="<?php echo isset($postData['MemberIdentification']['second_member']) ? $postData['MemberIdentification']['second_member'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="third_member">Flat Transfer From</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="third_member" name="data[MemberIdentification][third_member]" value="<?php echo isset($postData['MemberIdentification']['third_member']) ? $postData['MemberIdentification']['third_member'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="associate_member">Associate Member</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="associate_member" name="data[MemberIdentification][associate_member]" value="<?php echo isset($postData['MemberIdentification']['associate_member']) ? $postData['MemberIdentification']['associate_member'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="joint_member">Joint Member</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="joint_member" name="data[MemberIdentification][joint_member]"  value="<?php echo isset($postData['MemberIdentification']['joint_member']) ? $postData['MemberIdentification']['joint_member'] :'' ; ?>"  placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="date_of_birth">Date of Birth</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="date_of_birth" name="data[MemberIdentification][date_of_birth]" value="<?php echo isset($postData['MemberIdentification']['date_of_birth']) ? $postData['MemberIdentification']['date_of_birth'] :$financialStartEndDate['firstDate']; ; ?>"  placeholder="">
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="class">Class</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="class" name="data[MemberIdentification][class]" value="<?php echo isset($postData['MemberIdentification']['class']) ? $postData['MemberIdentification']['class'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="GSTIN">GSTIN</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="associatGSTINe_member" name="data[MemberIdentification][GSTIN]" value="<?php echo isset($postData['MemberIdentification']['GSTIN']) ? $postData['MemberIdentification']['GSTIN'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>



                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="Opening Principal">Opening Principal</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="op_principal" name="data[MemberIdentification][op_principal]" value="<?php echo isset($postData['MemberIdentification']['op_principal']) ? $postData['MemberIdentification']['op_principal'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>



                                                    </div>

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="date_of_admission_1">date of Admission 1</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="date_of_admission_1" name="data[MemberIdentification][date_of_admission_1]" value="<?php echo isset($postData['MemberIdentification']['date_of_admission_1']) ? $postData['MemberIdentification']['date_of_admission_1'] :$financialStartEndDate['firstDate']; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="date_of_admission_2">date of Admission 2</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="date_of_admission_2" name="data[MemberIdentification][date_of_admission_2]" value="<?php echo isset($postData['MemberIdentification']['date_of_admission_2']) ? $postData['MemberIdentification']['date_of_admission_2'] :$financialStartEndDate['firstDate']; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="purchased_on">Purchased On</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="purchased_on" name="data[MemberIdentification][purchased_on]" value="<?php echo isset($postData['MemberIdentification']['purchased_on']) ? $postData['MemberIdentification']['purchased_on'] :$financialStartEndDate['firstDate']; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="disposed_on">Disposed On</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="disposed_on" name="data[MemberIdentification][disposed_on]" value="<?php echo isset($postData['MemberIdentification']['disposed_on']) ? $postData['MemberIdentification']['disposed_on'] :$financialStartEndDate['firstDate']; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="agreement_date">Agreement Date</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="agreement_date" name="data[MemberIdentification][agreement_date]" value="<?php echo isset($postData['MemberIdentification']['agreement_date']) ? $postData['MemberIdentification']['agreement_date'] :$financialStartEndDate['firstDate']; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="agreement_value">Agreement Value</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="agreement_value" name="data[MemberIdentification][agreement_value]" value="<?php echo isset($postData['MemberIdentification']['agreement_value']) ? $postData['MemberIdentification']['agreement_value'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="stamp_duty_no">Stamp Duty No</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="stamp_duty_no" name="data[MemberIdentification][stamp_duty_no]" value="<?php echo isset($postData['MemberIdentification']['stamp_duty_no']) ? $postData['MemberIdentification']['stamp_duty_no'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="stamp_duty_no">Opening Interest</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="op_interest" name="data[MemberIdentification][op_interest]" value="<?php echo isset($postData['MemberIdentification']['op_interest']) ? $postData['MemberIdentification']['op_interest'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>




                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="stamp_duty_no">Opening Tax</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="op_tax" name="data[MemberIdentification][op_tax]" value="<?php echo isset($postData['MemberIdentification']['op_tax']) ? $postData['MemberIdentification']['op_tax'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>



                                                    </div>
                                                </div>
                                            </div>

                                            <div id="personal_identificaton" class="tab-pane fade" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="gender">Gender</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="gender" name="data[MemberIdentification][gender]" value="<?php echo isset($postData['MemberIdentification']['gender']) ? $postData['MemberIdentification']['gender'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="age">Age</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="age" name="data[MemberIdentification][age]" value="<?php echo isset($postData['MemberIdentification']['age']) ? $postData['MemberIdentification']['age'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="religion">Religion</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="religion" name="data[MemberIdentification][religion]" value="<?php echo isset($postData['MemberIdentification']['religion']) ? $postData['MemberIdentification']['religion'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="blood_group">Blood Group</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="blood_group" name="data[MemberIdentification][blood_group]" value="<?php echo isset($postData['MemberIdentification']['blood_group']) ? $postData['MemberIdentification']['blood_group'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="profession">Profession</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="profession" name="data[MemberIdentification][profession]" value="<?php echo isset($postData['MemberIdentification']['profession']) ? $postData['MemberIdentification']['profession'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="nationality">Nationality</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="nationality" name="data[MemberIdentification][nationality]" value="<?php echo isset($postData['MemberIdentification']['nationality']) ? $postData['MemberIdentification']['nationality'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="members_bank">Member's Bank</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="members_bank" name="data[MemberIdentification][members_bank]" value="<?php echo isset($postData['MemberIdentification']['members_bank']) ? $postData['MemberIdentification']['members_bank'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="pan_no">Pan No</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="pan_no" name="data[MemberIdentification][pan_no]" value="<?php echo isset($postData['MemberIdentification']['pan_no']) ? $postData['MemberIdentification']['pan_no'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="shares_details" class="tab-pane fade " role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="date_of_allotment">Date of Allotment</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="date_of_allotment" name="data[MemberIdentification][date_of_allotment]" value="<?php echo isset($postData['MemberIdentification']['date_of_allotment']) ? $postData['MemberIdentification']['date_of_allotment'] :$financialStartEndDate['firstDate']; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="certification_no">Certification No</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="certification_no" name="data[MemberIdentification][certification_no]" value="<?php echo isset($postData['MemberIdentification']['certification_no']) ? $postData['MemberIdentification']['certification_no'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="folio_no">Folio No</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="folio_no" name="data[MemberIdentification][folio_no]" value="<?php echo isset($postData['MemberIdentification']['folio_no']) ? $postData['MemberIdentification']['folio_no'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="no_of_shares">No. of Shares</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="no_of_shares" name="data[MemberIdentification][no_of_shares]" value="<?php echo isset($postData['MemberIdentification']['no_of_shares']) ? $postData['MemberIdentification']['no_of_shares'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                    </div> 

                                                    <div class="col-md-5">                                                    
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="shares_value">Shares Value Rs.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="shares_value" name="data[MemberIdentification][shares_value]" value="<?php echo isset($postData['MemberIdentification']['shares_value']) ? $postData['MemberIdentification']['shares_value'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="from_share_no">From Share No.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="from_share_no" name="data[MemberIdentification][from_share_no]" value="<?php echo isset($postData['MemberIdentification']['from_share_no']) ? $postData['MemberIdentification']['from_share_no'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="to_share_no">To Share No.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="from_share_no" name="data[MemberIdentification][to_share_no]" value="<?php echo isset($postData['MemberIdentification']['to_share_no']) ? $postData['MemberIdentification']['to_share_no'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="status">Status</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="status" name="data[MemberIdentification][status]" value="<?php echo isset($postData['MemberIdentification']['status']) ? $postData['MemberIdentification']['status'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="iform_details" class="tab-pane fade " role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="date_of_admission">Date of Admission</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="date_of_admission" name="data[MemberIdentification][date_of_admission]" value="<?php echo isset($postData['MemberIdentification']['date_of_admission']) ? $postData['MemberIdentification']['date_of_admission'] :$financialStartEndDate['firstDate']; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="admission_fees">Admission Fees</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="admission_fees" name="data[MemberIdentification][admission_fees]" value="<?php echo isset($postData['MemberIdentification']['admission_fees']) ? $postData['MemberIdentification']['admission_fees'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="full_name">Full Name</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="full_name" name="data[MemberIdentification][full_name]" value="<?php echo isset($postData['MemberIdentification']['full_name']) ? $postData['MemberIdentification']['full_name'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="unit_no">Unit No</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="unit_no" name="data[MemberIdentification][unit_no]" value="<?php echo isset($postData['MemberIdentification']['unit_no']) ? $postData['MemberIdentification']['unit_no'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div>                                                    
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="address">Address</label>
                                                            <div class="col-sm-8">
                                                                <textarea rows="3" cols="50" class="form-control" id="address" name="data[MemberIdentification][address]" value="<?php echo isset($postData['MemberIdentification']['address']) ? $postData['MemberIdentification']['address'] :'' ; ?>"></textarea>                                                            
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="occupation">Occupation</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="occupation" name="data[MemberIdentification][occupation]" value="<?php echo isset($postData['MemberIdentification']['occupation']) ? $postData['MemberIdentification']['occupation'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="age_on_date_of_admission">Age on Date of Admission</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="age_on_date_of_admission" name="data[MemberIdentification][age_on_date_of_admission]" value="<?php echo isset($postData['MemberIdentification']['age_on_date_of_admission']) ? $postData['MemberIdentification']['age_on_date_of_admission'] :$financialStartEndDate['firstDate'] ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                    </div> 

                                                    <div class="col-md-6"> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="nominee_name">Nominee Name</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="nominee_name" name="data[MemberIdentification][nominee_name]" value="<?php echo isset($postData['MemberIdentification']['nominee_name']) ? $postData['MemberIdentification']['nominee_name'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="nomination_date">Date of Nomination</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="nomination_date" name="data[MemberIdentification][nomination_date]" value="<?php echo isset($postData['MemberIdentification']['nomination_date']) ? $postData['MemberIdentification']['nomination_date'] :$financialStartEndDate['firstDate'] ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="nominee_address">Address of Nominee</label>
                                                            <div class="col-sm-8">
                                                                <textarea rows="3" cols="50" class="form-control" id="nominee_address" name="data[MemberIdentification][nominee_address]" value="<?php echo isset($postData['MemberIdentification']['nominee_address']) ? $postData['MemberIdentification']['nominee_address'] :'' ; ?>"></textarea>                                                            
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="date_of_cessation_membership">Date of Cessation of Membership</label>
                                                            <div class="col-sm-8">
                                                                <input type="date" class="form-control" id="date_of_cessation_membership" name="data[MemberIdentification][date_of_cessation_membership]" value="<?php echo isset($postData['MemberIdentification']['date_of_cessation_membership']) ? $postData['MemberIdentification']['date_of_cessation_membership'] :$financialStartEndDate['firstDate'] ; ?>" placeholder="">
                                                            </div>
                                                        </div>  
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="reason_for_cessation">Reason For Cessation</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="reason_for_cessation" name="data[MemberIdentification][reason_for_cessation]" value="<?php echo isset($postData['MemberIdentification']['reason_for_cessation']) ? $postData['MemberIdentification']['reason_for_cessation'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 col-sm-4" for="remarks">Remarks</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="remarks" name="data[MemberIdentification][remarks]" value="<?php echo isset($postData['MemberIdentification']['remarks']) ? $postData['MemberIdentification']['remarks'] :'' ; ?>" placeholder="">
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group mb-0">
                                        <div class="col-sm-offset-2 col-sm-10"> 
                                            <button type="submit" data-toggle="tooltip" data-original-title="Submit details" class="btn btn-success btn-anim" onclick=""><i class="icon-rocket"></i><span class="btn-text"><?php if(isset($this->request->data['Member']['id']) && !empty($this->request->data['Member']['id'])) { ?>Update details<?php }else{ ?> Submit details <?php } ?></span></button>                                        
                                            <a type="button" data-toggle="tooltip" data-original-title="Go to member list" class="btn btn-success btn-anim" href="<?php echo Router::url(array('controller' =>'societys','action' =>'member_identifications')); ?>"><i class="icon-rocket"></i><span class="btn-text">Cancel</span></a>                                                                            
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

