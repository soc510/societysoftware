<div class="container-fluid">
    <div class="row">
    <?php echo $this->Session->flash(); ?>
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Assigned Societies</h6>
                    </div>
                    <div class="pull-right">
                        
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="">
                    <div class="row">
                        <!--<div class="col-md-3 assigned-society-box">                            
                            <div class="assigned-society-name"><i class="zmdi zmdi-city-alt mr-10"></i>MALHARI RESIDENECY C.h.s.Ltd</div>
                            <div class="assigned-society-years"><i class="zmdi zmdi-calendar mr-10"></i>2016-17</div>
                            <div class="society-login-btn"><button type="button" class="btn btn-succes" data-toggle="modal" data-target="#society-login-modal">Login</button></div>                            
                        </div>-->
                        <?php if(isset($assignedSocietysList) && count($assignedSocietysList > 0)){
                            foreach($assignedSocietysList as $societysInfo){?>
                                <div class="col-md-3 assigned-society-box">                            
                                    <div class="assigned-society-name"><i class="zmdi zmdi-city-alt mr-10"></i><?php echo isset($societysInfo['Society']['society_name']) ? $societysInfo['Society']['society_name'] : ''; ?></div>
                                    <div class="society-login-btn"><button type="button" data-toggle="tooltip" data-original-title="Login" class="btn btn-succes" onclick="societyAutoLogin201920('<?php echo $societysInfo['User']['username']; ?>','<?php echo $societysInfo['User']['password']; ?>',0)">2019-20</button></div>                         
                                    <div class="assigned-society-years"><i class="zmdi zmdi-calendar mr-10"></i><?php echo isset($societysInfo['Society']['udate']) ? date('Y-m-d',strtotime($societysInfo['Society']['udate'])) : ''; ?></div>
                                    <div class="society-login-btn"><button type="button" data-toggle="tooltip" data-original-title="Login" class="btn btn-succes" onclick="societyAutoLogin('<?php echo $societysInfo['User']['username']; ?>','<?php echo $societysInfo['User']['password']; ?>',0)">Auto Login</button></div>                         
                                </div>
                        <?php } } ?>      
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>

<!-- Modal -->
<!--<div class="modal fade" id="society-login-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">MALHARI RESIDENECY C.H.S.LTD</h6>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Email:</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Password:</label>
                        <div class="col-sm-10">          
                            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
                        </div>
                    </div>
                    <div class="form-group">        
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
                    </div>
                </form>
                <form action="" name="" id="">
                    <div class="form-group">
                      <label for="">User Id:</label>
                      <input type="text" class="form-control" id="">
                    </div>
                    <div class="form-group">
                      <label for="">Password:</label>
                      <input type="password" class="form-control" id="">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
              </form>
            </div>
        </div>
    </div>
</div>-->