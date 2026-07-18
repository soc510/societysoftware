<script type="text/javascript">
        <?php if(!empty($this->params['action']) && $this->params['action'] == 'assign_societies' && $this->params['controller'] == 'admin'){?>
         $(function() {
                    $('#societie_id').multiselect({
                    nonSelectedText: 'Select Societies',
                    enableFiltering: true,
                    selectAllValue: 'multiselect-all',
                    enableCaseInsensitiveFiltering: true,
                    buttonWidth:'680px',
                    selectAllText: ' Select all',
                    selectAllName: true
                    });
					});
        <?php } ?>
            function addUpdateSocietyLoginCredentials(){
                //alert();
                $("#SocietyLoginCredentialsForm").submit();
            }
                $("#SocietyLoginCredentialsForm").submit(function (e)
            {
                var validated = $("#SocietyLoginCredentialsForm").parsley().validate();
                    if (validated) {
                        showLoader();
                        var formData = new FormData($(this)[0]);
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo Router::url(array('controller' => 'admin', 'action' => 'add_update_login_credentials')); ?>",
                            data: formData,
                            dataType: 'json',
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(resp) {
                                hideLoader();
                                if (resp) {
                                    showNotifyAlert(resp,'show_notify_error');
                                    if (resp.error === 0) {
                                        setTimeout(function(){ redirectToActionController("<?php echo $this->Html->url(array('controller'=>'admin','action' =>'view_societys')); ?>"); }, 1000);
                                    }
                                }
                            }
                        });
                    }
                    return false;
            }); 

            function societyShortCode(shortCode){
                    if(shortCode != ''){
                    var shortCodeArr = shortCode.split(" ");
                    var code1 = shortCodeArr[0] ? shortCodeArr[0] : '';
                    var code2 = shortCodeArr[1] ? shortCodeArr[1] : '';
                    //alert(shortCodeArr.length);
                        if(shortCodeArr.length > 1){
                        var code = code1.substr(0,2)+''+code2.substr(0,2);
                    }else{
                        var code = code1.substr(0,3);
                    }
                    $('#society_code').val(code.toUpperCase()+'_CHS');
                }
            }


            function updateSocietyDetailsBySocietyId(){
                $("#SocietyUpdateForm").submit();
            }
            $("#SocietyUpdateForm").submit(function (e)
            {
                var validated = $("#SocietyUpdateForm").parsley().validate();
                    if (validated) {
                        showLoader();
                        var formData = new FormData($(this)[0]);
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo Router::url(array('controller'=>'societys','action'=>'update_societys')); ?>",
                            data: formData,
                            dataType: 'json',
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(resp) {
                                hideLoader();
                                if (resp) {
                                    showNotifyAlert(resp,'show_notify_error');
                                    var societyUserId = $('#society_user_id').val();
                                    if (resp.error === 0) {
                                        setTimeout(function(){ redirectToActionController("<?php echo $this->Html->url(array('controller'=>'societys','action' =>'update_societys')); ?>"+'/'+societyUserId); }, 3000);
                                    }
                                }
                            }
                        });
                    }
                    return false;
            }); 

            function selectAccesslevel(accesslevel){
                if(accesslevel != '' && parseInt(accesslevel) == 3){
                    $('.access_level').hide();
                }else{
                    $('.access_level').show();
                }
            }
            
            function validateResellerSocietyAssign(){
            var societieId = $('#societie_id').val();
            if(societieId == null){
                    $('.btn-group').css('border-color','red');
                    showNotifyAlert({"error":1,"error_message":"Please select society"},'show_assign_reseller_error');
                    return false;
                }
            }

            function getAssignedSocieties(resellerId){
                if(resellerId){
                        showLoader();
                        var postStr = 'resellerId='+resellerId;
                        $.ajax({
                            type: 'POST',
                            url:"<?php echo Router::url(array('controller'=>'admin','action'=>'getAssignedSocieties')); ?>",
                            data: postStr,
                            dataType: 'json',
                            success: function(resp) {
                                hideLoader();
                                if (resp.error_flag == 0) {
                                    if (resp.assignedSocietyList.length > 0) {
                                        $("#societie_id > option").each(function () {
                                            var societyID = this.value;
                                            if (typeof societyID !== "undefined" && societyID !== '') {
                                                if ($.inArray(societyID, resp.assignedSocietyList) > -1) {
                                                    $("#societie_id").find('option[value="' + societyID + '"]').attr("selected", "selected");
                                                    $('#societie_id').multiselect('select', societyID);
                                                } else {
                                                    $("#societie_id").find('option[value=' + societyID + ']').removeAttr('selected');
                                                    $('#societie_id').multiselect('deselect', societyID);
                                                }
                                            }
                                        });
                                    }
                                    showNotificationToastr('success', "Society assigned sucessfully.");
                                } else {
                                    $("#societie_id > option").each(function () {
                                        var societyID = this.value;
                                        if (typeof societyID !== "undefined" && societyID !== '') {
                                            $("#societie_id").find('option[value="' + societyID + '"]').removeAttr('selected');
                                            $('#societie_id').multiselect('deselect', societyID);
                                        }
                                    });
                                    //showNotificationToastr('success',"Society assigned sucessfully.");
                                }
                            }

                        });
                    }
                }
            
            <?php if (isset($singleSocietyRecord['User']['access_level']) && $singleSocietyRecord['User']['access_level'] == 3) { ?>
                    $('.access_level').hide();
            <?php }?>

            function multiSelectDropDown(){
			$('#societie_id').multiselect('destroy');
                                     $('#societie_id').multiselect({
                                        nonSelectedText: 'Select Societies',
                                        enableFiltering: true,
                                        selectAllValue: 'multiselect-all',
                                        enableCaseInsensitiveFiltering: true,
                                        buttonWidth:'650px',
                                        selectAllText: ' Select all',
                                        selectAllName: true
                                    });
            }
            
            function selectMenu(clkLink) {
                        $.ajax({
                                url: "<?php echo $this->Html->url(array(
                                                "controller" => "users",
                                                "action" => "setMenu",
                                        ));?>?selected="+clkLink,
                                cache: false,
                                async: false,
                                dataType: "html"
                          });
                        }
                        
                      function selectSelectedMenu(data) {
                                                $(eval(data)).addClass("active");
                                        } 
    </script>