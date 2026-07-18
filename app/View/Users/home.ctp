<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>Easy Logics Technology</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/fvcon.png">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Alegreya|Cabin|Josefin+Sans|Merriweather|Mukta+Mahee|Roboto+Slab|Work+Sans" rel="stylesheet">
        <?php
              echo $this->Html->css('bootstrap');
              echo $this->Html->css('custom');
              echo $this->Html->css('custom_society');
              echo $this->Html->css('custom_bill_report');
              echo $this->Html->css('header-loginFrm');
              echo $this->Html->css('font-awesome');
              echo $this->fetch('css');
        ?>
    </head>
    <body>
        <div class="top-row">
            <div class="container">
                <div class="col-md-7">
                    <div class="top-row-lefticons">
                        <span><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;Call Us : +91 8108107496 &nbsp;/ +91 8208902649</span>&nbsp;
                        <span class="border:1px solid #fff;">|</span>&nbsp;
                        <span><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email : info@easylogicstechnology.com</span>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="top-row-righticons">
                        <span><i class="fa fa-facebook" aria-hidden="true" style="border-right:1px solid #fff;"></i></span>
                        <span><i class="fa fa-linkedin" aria-hidden="true" style="border-right:1px solid #fff;"></i></span>
                        <span><i class="fa fa-twitter" aria-hidden="true" style="border-right:1px solid #fff;"></i></span>			
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-default navbar-default" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><img src="images/easylogistic-7-6.png" class="img-responsive"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            
                            <!--a href="<?php //$this->Html->link(array('controller' => 'users','action' => 'aboutus')); ?>">About</a-->
                            <a href="https://easylogicstechnology.com/about/about_us.php" target="_blank">About</a>
                        </li>		
                        <li><a href="#">Services</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Login <span class="caret"></span></a>
                            <ul id="login-dp" class="dropdown-menu">
                                <li>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php echo $this->Session->flash(); ?>
                                            <form class="form" action="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>" role="form" method="post" id="login_form" method="post" id="login_form">
                                                <div class="form-group">
                                                    <label class="sr-only" for="exampleInputEmail2">Username</label>
                                                    <input type="text" class="form-control" id="username" name="data[User][username]" placeholder="Username" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label class="sr-only" for="exampleInputPassword2">Password</label>
                                                    <input type="password" class="form-control" name="data[User][password]" id="password" placeholder="Password" required="">
                                                    <div class="help-block text-right"><a href="">Forget the password ?</a></div>
                                                </div>
                                                <div class="form-group">
                                                    <a href="javascript:void(0)"><input type="submit" class="btn btn-primary btn-block" value="Sign in"></a>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox"> keep me logged-in
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="bottom text-center">
                                            <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'login')); ?>"><b>Register Now</b></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li> 
                    </ul>
                </div>
            </div>
        </nav>
        <div class="">
            <img src="images/society2.jpg" class="img-responsive">
        </div>
        <div class="features-section">
            <div class="container">
                <div class="features-title">Easy Logics Technology is a Software for Managing Committees & Property Managers, GST Compliant Society Accounting Software for Accountants & Auditors on a seamlessly integrated platform.</div>
                <div class="features-tagline">Take a look at some of the features</div>
                <div class="row">
                    <div class="col-md-2 col-sm-3 col-xs-4">
                        <div class="features-box">
                            <i class="fa fa-database" aria-hidden="true"></i>
                            <div class="features-box-content">Society Data Management</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-3 col-xs-4">
                        <div class="features-box">
                            <i class="fa fa-calculator" aria-hidden="true"></i>
                            <div class="features-box-content">Accounting Management</div>
                        </div>
                    </div>				
                    <div class="col-md-2 col-sm-3 col-xs-4">
                        <div class="features-box">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <div class="features-box-content">Maintenance Bill</div>
                        </div>
                    </div>							
                    <div class="col-md-2 col-sm-3 col-xs-4">
                        <div class="features-box">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                            <div class="features-box-content">Notices or Circulars</div>
                        </div>
                    </div>							
                    <div class="col-md-2 col-sm-3 col-xs-4">
                        <div class="features-box">
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <div class="features-box-content">Manage Registers</div>
                        </div>
                    </div>										
                    <div class="col-md-2 col-sm-3 col-xs-4">
                        <div class="features-box">
                            <i class="fa fa-question" aria-hidden="true"></i>
                            <div class="features-box-content">Manage Compliance</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="software-description-bg">
            <div class="container">			
                <div class="software-description-title">Easy Logics Technology Society Management Software</div>
                <div class="row software-description-box">
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-list-title">Society Billing</div>
                        <div class="software-description-list">	
                            <ul>
                                <li>Maintenance Charges Bills on regular periodical basis or for specific purpose / Debit Notes can be generated with ease.</li>
                                <li>Interest and / Penalty can be charged for delayed payment.</li>
                                <li>Rebate can be given for prompt payment, as per rules set by the Society.</li>
                            </ul>
                        </div>
                    </div>				
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-img float-right"><img src="images/billing.png" class="img-responsive"></div>
                    </div>
                </div>				
            </div>
        </div>
        <div class="software-description">
            <div class="container">			
                <div class="row software-description-box">				
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-img-left"><img src="images/ledger-heads.png" class="img-responsive"></div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-list-title">Manage Registers</div>
                        <div class="software-description-list">	
                            <ul>
                                <li>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</li>
                                <li>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</li>
                            </ul>
                        </div>
                    </div>	
                </div>		
            </div>
        </div>
        <div class="software-description-bg">
            <div class="container">			
                <div class="row software-description-box">
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-list-title">Manage Registers</div>
                        <div class="software-description-list">	
                            <ul>
                                <li>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</li>
                                <li>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</li>
                            </ul>
                        </div>
                    </div>					
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-img float-right"><img src="images/ledger-heads.png" class="img-responsive"></div>
                    </div>
                </div>		
            </div>
        </div>
        <div class="footer-section">
            <div class="container">
                <footer>
                    <div class="footer-list">
                        <ul>
                            <li><a href="#">Home</a></li>|
                            <li><a href="#">Features</a></li>|
                            <li><a href="#">About Us</a></li>|
                            <li><a href="https://easylogicstechnology.com/terms/termandcondition" target="_blank">Terms & Conditions</a></li>|
                            <li><a href="#">Contact Us</a></li>					
                        </ul>
                    </div>
                    <p class="copyright"><?php echo date('Y');?> Easy Logics Technology. All Rights Reserved.</p>
                </footer>
            </div>
        </div>
        <?php
        echo $this->Html->script('jquery-3.2.1');
        echo $this->Html->script('bootstrap');
        echo $this->Html->script('jquery.blockui.min');
        echo $this->Html->script('common');
        echo $this->fetch('script');
        ?>
    </body>
</html>
