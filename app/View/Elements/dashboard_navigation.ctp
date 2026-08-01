<body>
    <?php

    /* $db = $this->Session->read('Auth.dbConfig');
    if ($db == 'default'){
        $firstYear = date('Y');
        $secondYear = date('Y', strtotime('+1 year'));
        $currentdb = $firstYear.'-'.$secondYear;
        $linktext = 'Go To 2021-22';
    } else {
        $currentdb = '2021-22';
        $linktext = 'Go To 2022-23';
    }*/
    
     $currentdb = '2021-22';
     $currentdb = $this->Session->read('Auth.start_year').'-'.$this->Session->read('Auth.end_year');

     // Financial years this society is assigned to (society_year_mapping), for the
     // header year switcher. Rendered as a dropdown below when there is more than one.
     App::uses('ClassRegistry', 'Utility');
     $__assignedYearOptions = array();
     $__curYearId = $this->Session->read('Auth.year_id');
     $__societyIdForYear = $this->Session->read('Auth.User.id');
     if (!empty($__societyIdForYear)) {
         $__yearMapModel = ClassRegistry::init('SocietyFinancialYearMapping');
         $__yearMaps = $__yearMapModel->find('all', array('conditions' => array('SocietyFinancialYearMapping.society_id' => $__societyIdForYear, 'SocietyFinancialYearMapping.is_active' => 1), 'recursive' => -1));
         $__assignedYearIds = array();
         foreach ($__yearMaps as $__ym) { if (!empty($__ym['SocietyFinancialYearMapping']['year_id'])) { $__assignedYearIds[] = $__ym['SocietyFinancialYearMapping']['year_id']; } }
         if (!empty($__assignedYearIds)) {
             $__fyMasterModel = ClassRegistry::init('FinancialYearMaster');
             $__assignedYearOptions = $__fyMasterModel->find('all', array('conditions' => array('FinancialYearMaster.id' => $__assignedYearIds, 'FinancialYearMaster.is_active' => 1), 'order' => array('FinancialYearMaster.year_start_date' => 'asc'), 'recursive' => -1));
         }
     }

    ?>






    <div class="preloader-it">
        <div class="la-anim-1"></div>
    </div>



   <!-- 
        <div id="google_translate_element" style="display: none">
    </div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, Display: false }, 'google_translate_element');
       
       }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
        type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
        function translateLanguage(lang) {

            var $frame = $('.goog-te-menu-frame:first');
            if (!$frame.size()) {
                alert("Error: Could not find Google translate frame.");
                return false;
            }
            $frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
            return false;
        }
    </script>
    
    -->

    <style>
    .goog-te-banner-frame{
        z-index: 0000001;
    }
    .navbar.navbar-inverse.navbar-fixed-top .nav-header .logo-wrap{
        width: 203px;
    }
</style>
    <div class="wrapper theme-1-active pimary-color-red">
        <!-- Top Menu Items -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="mobile-only-brand pull-left">
                <div class="nav-header pull-left">
                    <div class="logo-wrap">
                      <a href="<?php echo $this->webroot; ?>societys/dashboard">
                            <img class="brand-img" src="<?php echo $this->webroot; ?>img/fvcon.png" alt="brand"/>
                            <span class="brand-text">Easy Logics</span>
                            
                        </a>
                    </div>
                </div>	
                <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu"></i></a>
              <!--  <a id="toggle_mobile_search" data-toggle="collapse" data-target="#search_form" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-search"></i></a> -->
                <a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
               <!-- <form id="search_form" role="search" class="top-nav-search collapse pull-left">
                    <div class="input-group">
                        <input type="text" name="example-input1-group2" class="form-control" placeholder="Search">
                        <span class="input-group-btn">
                            <button type="button" class="btn  btn-default"  data-target="#search_form" data-toggle="collapse" aria-label="Close" aria-expanded="true"><i class="zmdi zmdi-search"></i></button>
                        </span>
                    </div>
                </form> -->
               
            </div>
            <?php if ($this->Session->read('Auth.User.role') == 'Society'){ ?>
            <div class="dash-top-header pull-left">
                   <div class="dash-top-header-menu">
                    <?php //echo $this->Html->link('Ledger Heads',array('controller' => 'SocietyBillsController','action' => 'ledger_head_bills')); ?>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#society_ledger_heads_modal');"><i class="fa fa-check-square-o"></i> Ledger Heads</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#flat_shop_details');"><i class="fa fa-list"></i> Flat / Shop Detail</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#bill_modal');"><i class="fa fa-file-text-o"></i> Bill</a>
                     <!--<a href="javascript:void(0);" onclick="loadSocietyBillsModel('#member_receipt_modal');"><i class="fa fa-outdent"></i> Member Receipts</a>-->
                     <?php echo $this->Html->link('Member Receipts',array('controller' => 'societys_members','action' => 'member_receipt')); ?>
                    <!-- <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#member_receipt_modal');"><i class="fa fa-outdent"></i> Member Receipts</a>-->
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#payment_entry_modal');"><i class="fa fa-rupee"></i> Payment Entry</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#Member_Generate_Bill');"><i class="fa fa-th-list"></i> Generate Bill</a>
                     <a href="javascript:void(0);" onclick="loadSocietyBillsModel('#print_bill_modal');"><i class="fa fa-print"></i> Print Bill</a>
                     <!--<a href="javascript:void(0);" onclick="loadSocietyBillsModel('#email_bill_modal');"><i class="fa fa-email"></i> Email Bill</a>-->
                    <?php echo $this->Html->link('Email Bill',array('controller' => 'society_bills','action' => 'send_bill_email')); ?>

                   </div>
            </div>
           <?php } ?>
            <div class="dash-top-header pull-left">
            <?php if (count($__assignedYearOptions) > 1) { ?>
                <select onchange="if(this.value){window.location.href='<?php echo $this->webroot; ?>societys/change_financial_year/'+this.value;}" title="Change financial year" style="background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.5);border-radius:3px;padding:2px 4px;font-weight:bold;cursor:pointer;">
                <?php foreach ($__assignedYearOptions as $__yo) {
                    $__yid = $__yo['FinancialYearMaster']['id'];
                    $__ytext = $__yo['FinancialYearMaster']['year'];
                    $__isCur = ((!empty($__curYearId) && $__yid == $__curYearId) || $__ytext === $currentdb);
                ?>
                    <option value="<?php echo $__yid; ?>" style="color:#000;" <?php echo $__isCur ? 'selected' : ''; ?>><?php echo $__ytext; ?></option>
                <?php } ?>
                </select>
            <?php } else { ?>
                <b><?php echo $currentdb;?></b>
            <?php } ?>
            </div>
            <div class="dash-top-header pull-left block txt-dark weight-500 font-20"><b><?php echo isset($societyName) ?  $societyName : '';?></b></div>
            <div id="mobile_only_nav" class="mobile-only-nav pull-right">
                <ul class="nav navbar-right top-nav pull-right">
                    <li class="dropdown auth-drp">
                        <a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown"><img src="<?php echo $this->webroot; ?>img/user2.png" alt="user_auth" class="user-auth-img img-circle"/> &nbsp; <?php if($this->Session->read('Auth.User')){ ?><?php echo $this->Session->read('Auth.User.username'); ?><?php } ?><span class="user-online-status"></span></a>
                        <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">

                            <li>
                              <a href="javascript:;" id="English" onclick="translateLanguage(this.id);"><i class="fa fa-language" aria-hidden="true"></i><span>English</span></a>
                            </li>
 <li class="divider"></li>
                            <li>
                              <a href="javascript:;" id="Hindi" onclick="translateLanguage(this.id);"><i class="fa fa-language" aria-hidden="true"></i><span>Hindi</span></a>
                            </li>
 <li class="divider"></li>
                            <li>
                             <a href="javascript:;" id="Marathi" onclick="translateLanguage(this.id);"><i class="fa fa-language" aria-hidden="true"></i><span>Marathi</span></a>
                            </li>                            
 <li class="divider"></li>
  			<li>
                             <a href="javascript:;" id="Gujarati" onclick="translateLanguage(this.id);"><i class="fa fa-language" aria-hidden="true"></i><span>Gujarati</span></a>
                            </li>
 <li class="divider"></li>
			<li>
                                <?php echo $this->Html->link('<i class="fa fa-lock"></i><span>Change Password</span>',array('controller' => 'societys','action' => 'change_password'),array('escape' => false)); ?>
                            </li>
                            <li class="divider"></li>
                            <?php if ($this->Session->read('Auth.User')){ ?>
                            <li><?php echo $this->Html->link('<i class="fa fa-calendar"></i><span>'.$linktext.'</span>',array('controller' => 'Users','action' => 'switchlogin'),array('escape' => false)); ?> </li>
                            <li class="divider"></li>
                            <?php } ?>
                            <?php if ($this->Session->read('Auth.User')){ ?>
                            <li> <?php echo $this->Html->link('<i class="zmdi zmdi-power"></i><span>Log Out</span>',array('controller' => 'Users', 'action' => 'logout'),array('escape' => false)); ?></li>
                             
                            <?php } 
                            if($this->Session->read('Auth.reseller_Flag') && $this->Session->read('Auth.reseller_Flag') == 1){
                            ?>
                            <li> <a href="javascript:void(0);" onclick="changeSociety();"><i class="zmdi zmdi-city-alt mr-20"></i><span>Go To Another Society</span></a></li>
                            
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
            
        </nav>
        <div class="fixed-sidebar-left">
            <ul class="nav navbar-nav side-nav nicescroll-bar">
                <li class="navigation-header">
                    <span>Main</span> 
                    <i class="zmdi zmdi-more"></i>
                </li>
                <?php if(isset($menus['menu'])) {
                    $menuId = '';
                    foreach ($menus['menu'] as $mkey => $menu) { 
                        $menuId++;
                        $menuLink = '#.';
                        $menuICon = isset($menu['icon']) ? $menu['icon'] : 'zmdi zmdi-google-pages mr-20';

                    ?>
                    <?php if(isset($menu['link'])) { 
                        $menuLink = $menu['link'];
                        ?>
                <li>
                    <a class="collapsed" onClick="selectMenu(<?php echo $menuId; ?>)" id="menu-<?php echo $menuId; ?>" href="<?php echo $menuLink; ?>"><div class="pull-left"><?php echo $menuICon; ?><span class="right-nav-text"><?php echo $mkey; ?></span></div><div class="clearfix"></div></a>
                </li>
                    <?php } ?>
                    <?php if(isset($menu['submenu'])) { ?>
                <li>
                    <a class="" href="javascript:void(0);" onClick="selectMenu(<?php echo $menuId; ?>)" id="menu-<?php echo $menuId; ?>" data-toggle="collapse" data-target="#dashboard_dr<?php echo $menuId; ?>"><div class="pull-left"><?php echo $menuICon; ?><span class="right-nav-text"><?php echo $mkey; ?></span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
                    <ul id="dashboard_dr<?php echo $menuId; ?>" class="collapse collapse-level-1">
                        <?php 
                        if(count($menu['submenu']) > 0):
                            foreach ($menu['submenu'] as $smkey => $smvalue) {
                            $submenuLink = isset($smkey) ? $smkey : '#.';
                        ?>
                        <li>
                            <a  href="<?php echo $submenuLink; ?>"><?php echo $smvalue; ?></a>
                        </li>
                        <?php  } endif; ?>
                    </ul>
                </li>
                    <?php } ?>    
                    <?php }}?>
            </ul>
        </div>
        <div class="right-sidebar-backdrop"></div>
        <div class="page-wrapper">
