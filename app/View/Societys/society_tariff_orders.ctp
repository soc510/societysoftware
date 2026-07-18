<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  
<style>
.ui-draggable, .ui-droppable {
	background-position: top;
}
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 40%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em;}
  #sortable li span { position: absolute; margin-left: -1.3em; }
 </style>
  
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div id="modal_society_ledger_order"></div>
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Set Society Tariff Order</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in ">
                    <?php 
                    if(!empty($societyLedgerHeadList)) { 
                        echo "<ul id=\"sortable\">";
                        $ledgerCount=1;
                        foreach($societyLedgerHeadList as $key=>$tariff) {
                            echo "<li id=".$tariff['SocietyLedgerHeads']['id']." class=\"ui-state-default\"><span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>".$tariff['SocietyLedgerHeads']['title']."</li>";
                            $ledgerCount++;
                        }
                        echo "</ul>";
                    }
                    ?>
                <p style="margin:10px 0 10px 0;">
                    <button type="button" data-toggle="tooltip" data-original-title="Set Order" class="btn btn-success btn-anim" onclick="updateSocietyLedgerOrder();"><i class="icon-rocket"></i><span class="btn-text">Set Order</span></button>   
                </p>
                </div>	
                 
            </div>
        </div>
    </div>
</div>
 <script type="text/javascript">
    var societyTariffOrder = true;
 </script>
 <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
  <script>
  $(function() {
    //$( "#sortable" ).sortable();
    //$( "#sortable" ).disableSelection();
  });
  </script>-->