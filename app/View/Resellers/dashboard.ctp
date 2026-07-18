<div class="container-fluid pt-10">
    <div class="row">
        <?php echo $this->Session->flash(); ?>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-red">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter"><span class="counter-anim">100</span></span>
                                        <span class="weight-500 uppercase-font txt-light block font-13">No. Of Societies</span>

                                    </div>
                                    <div class="col-xs-4 text-center  pl-0 pr-0 data-wrap-right">
                                        <i class="fa fa-building-o txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                                <div class="row"> 
                                    <div class="col-xs-12 ">
                                        <span class="block txt-light font-15">Member -> &nbsp;&nbsp; <span class="txt-dark weight-500 font-20">200</span></span>                                        
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-yellow">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter"><span class="counter-anim">200</span></span>
                                        <span class="weight-500 uppercase-font txt-light block">New Societies This Year</span>
                                    </div>
                                    <div class="col-xs-4 text-center  pl-0 pr-0 data-wrap-right">
                                        <i class="zmdi zmdi-city-alt txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>	
                                <div class="row"> 
                                    <div class="col-xs-12">
                                        <span class="block txt-light font-15">Members-> &nbsp;&nbsp; <span class="txt-dark weight-500 font-20">500</span></span>                                        
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <div class="panel-wrapper collapse in">
                    <div class="panel-body pa-0">
                        <div class="sm-data-box bg-green" style="height:130px;">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
                                        <span class="txt-light block counter">
                                            <span class="counter-anim">50</span></span>
                                        <span class="weight-500 uppercase-font txt-light block">Dropped Societies This Year</span>
                                    </div>
                                    <div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
                                        <i class="fa fa-times-circle txt-light data-right-rep-icon font-50"></i>
                                    </div>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <!-- /Row -->

    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Societies</h6>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="pull-left inline-block refresh mr-15">
                            <i class="zmdi zmdi-replay"></i>
                        </a>
                        <a href="#" class="pull-left inline-block full-screen mr-15">
                            <i class="zmdi zmdi-fullscreen"></i>
                        </a>
                        <div class="pull-left inline-block dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="zmdi zmdi-more-vert"></i></a>
                            <ul class="dropdown-menu bullet dropdown-menu-right"  role="menu">
                                <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-reply" aria-hidden="true"></i>Edit</a></li>
                                <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-share" aria-hidden="true"></i>Delete</a></li>
                                <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-trash" aria-hidden="true"></i>New</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div  class="panel-body row pa-0">
                        <table class="table table-hover table-bordered padding-td-none padding-th-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Member</th>
                                    <th>Total Amount</th>
                                    <th>Total Collection</th>
                                    <th>Total Dues</th>
                                    <th>Total Expenses</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

