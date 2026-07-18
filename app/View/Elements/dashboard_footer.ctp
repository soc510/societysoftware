<footer class="footer container-fluid pl-30 pr-30">
    <div class="row">
        <div class="col-sm-12">
            <p>&copy <?php 2018;?> Easy Logics Technology. All Rights Reserved.</p>
        </div>
    </div>
</footer>
</div>
    </div>
    <script src="<?php echo $this->webroot; ?>js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/jasny-bootstrap.min.js"></script>
    <script src="https://www.jquery-az.com/jquery/js/multiselect-checkbox/jquery.multiselect.js" ></script>
	<!-- Data table JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/jquery.dataTables.min.js"></script>
    <!-- Slimscroll JavaScript -->
    <?php if(!empty($this->params['action']) && $this->params['controller'] == 'societys' || $this->params['controller'] == 'societysemployees' || $this->params['controller'] == 'societysmembers' || $this->params['controller'] == 'societys_members' || $this->params['controller'] == 'society_bills' || $this->params['controller'] == 'reports'){?>
            <script src="<?php echo $this->webroot; ?>js/dataTables-data.js"></script>
    <?php } ?>
    <script src="<?php echo $this->webroot; ?>js/jquery.slimscroll.js"></script>
    <!-- simpleWeather JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/moment.min.js"></script>
    <!-- Progressbar Animation JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/jquery.waypoints.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/jquery.counterup.min.js"></script>
    <!-- Fancy Dropdown JS -->
    <script src="<?php echo $this->webroot; ?>js/dropdown-bootstrap-extended.js"></script>
    <!-- Sparkline JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/jquery.sparkline.min.js"></script>
    <!-- Owl JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/owl.carousel.min.js"></script>
    <!-- ChartJS JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/jquery.toast.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/parsley.min.js"></script>
	<!-- Switchery JavaScript -->
    <script src="<?php echo $this->webroot; ?>js/switchery.min.js"></script>
    <script src="<?php echo $this->webroot; ?>vendors_js/select2.full.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/init.js"></script>
    <?php if(!empty($this->params['action']) && $this->params['action'] == 'dashboard' && $this->params['controller'] == 'admin'){?>
    <!--<script src="<?php echo $this->webroot; ?>js/dashboard-data.js"></script>
    <script src="<?php echo $this->webroot; ?>js/jquery.simpleWeather.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/simpleweather-data.js"></script>
    <script src="<?php echo $this->webroot; ?>js/raphael.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/morris.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/Chart.min.js"></script>-->
    <?php } ?>
    <?php if(!empty($this->params['action']) && $this->params['action'] == 'assign_societies' && $this->params['controller'] == 'admin'){?>
    <script src="<?php echo $this->webroot; ?>vendors_js/bootstrap-multiselect.js" type="text/javascript"></script>
    <?php }?>
    <script src="<?php echo $this->webroot; ?>js/jquery.blockui.min.js"></script>
    <script type="text/javascript">
        var webrootUrl = "<?php echo $this->webroot; ?>";
        var societyAjaxUrl = "<?php echo Router::url(array('controller'=>'societys')); ?>";
       <?php if(!empty($this->params['action']) && $this->params['controller'] == 'societys_members' && $this->params['action'] == "add_member_payment"){?> 
        if(societyMemberPaymentID && societyMemberPaymentID !== ''){
           var societyMemberPaymentID = societyMemberPaymentID;
        }
       <?php }else{ ?>
           var societyMemberPaymentID = 0;
       <?php }?>
       <?php if(isset($financialStartEndDate['firstDate']) && !empty($financialStartEndDate['firstDate'])){?>
               var financialStartEndDate = "<?php echo $financialStartEndDate['firstDate']; ?>"; 
       <?php }else{?>
               var financialStartEndDate = "<?php echo date('Y-m-d'); ?>"; 
       <?php }?> 
    </script>
    <?php
        $this->Js->get('window')->event('load', $this->Js->request(
                array(
                        'controller' => 'users',
                        'action' => 'getMenu'
                        ), array(
                        'success' => 'selectSelectedMenu(data)',
                        'async' => true,
                        'method' => 'get',
                        'cache' => false,
                        'dataExpression' => true,
                        )
                )
        );
        echo $this->Js->writeBuffer();
    ?>
    <script src="<?php echo $this->webroot; ?>js/common.js"></script>
    <?php echo $this->element('admin_js'); ?>
    <?php if(!empty($this->params['action']) && $this->params['action'] == 'society_tariff_orders' && $this->params['controller'] == 'societys'){?>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <?php }?>
    <?php if(!empty($this->params['action']) && $this->params['action'] == 'account_balance_sheet' && $this->params['controller'] == 'account_reports'){?>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo $this->webroot; ?>js/account_js.js?version=<?php echo Configure::read('FileVersion');?>" type="text/javascript"></script>
    <?php }?>
    <script src="<?php echo $this->webroot; ?>js/reseller_js.js?version=<?php echo Configure::read('FileVersion');?>" type="text/javascript"></script>
    <script src="<?php echo $this->webroot; ?>js/society_js.js?version=<?php echo Configure::read('FileVersion');?>" type="text/javascript"></script>
    <script src="<?php echo $this->webroot; ?>js/society_bills.js?version=<?php echo Configure::read('FileVersion');?>" type="text/javascript"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
		$("#assigned_society_id").select2({
		    ajax:{
		        url : 'getAssignedsociety',
		        dataType: 'json',
		        // data:function(params){
		            // return data.items;
		        // }
		        data: function (params) {
                  var query = {
                    search: params.term,
                    type: 'public'
                  }
            
                  // Query parameters will be ?search=[term]&type=public
                  return query;
                }
		    }
		});
    });
    

    function changeSociety(){
        var action = "/users/change_society";
        $('body').append($('<form></form>').attr('id','society_auto_login').attr('method', 'post').attr('action',action));
        $('#society_auto_login').submit();
    }    
    </script>    
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $('#param-settle-order').sortable();
    let settleOrder = $('#settlement').val(); 
       if(settleOrder == 2) 
            $('#set-order-box').show();
        else 
            $('#set-order-box').hide()
            
    $('#settlement').change(function(){
        settleOrder = $(this).val();
       if(settleOrder == 2) 
            $('#set-order-box').show();
        else
            $('#set-order-box').hide();
    });
    
    function updateSettlementOrder(){
        var societyLedgerArr = new Array();
        $('ul#sortable li').each(function() {
            societyLedgerArr.push($(this).attr("id"));
        });  
        $('#sorted_order').val(societyLedgerArr);
    }
        
    function submitSocietyParameter(){
        updateSettlementOrder();
        $('#societyParameters').submit();
    }
</script>
    <script>
        $('#remove_details').multiselect({
    columns: 4,
    placeholder: 'Select options'
});
</script>
    </script>
    <?php echo $this->element('sql_dump'); ?>    
    
</body>
<!-- Mirrored from hencework.com/theme/magilla/full-width-light/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 24 Dec 2017 10:01:25 GMT -->
</html>