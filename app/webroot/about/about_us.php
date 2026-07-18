<!DOCTYPE html>
<?php session_start()?>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        <title>Easy Logics Technology</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="https://easylogicstechnology.com/images/fvcon.png">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Alegreya|Cabin|Josefin+Sans|Merriweather|Mukta+Mahee|Roboto+Slab|Work+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.css"/><link rel="stylesheet" type="text/css" href="/css/custom.css"/><link rel="stylesheet" type="text/css" href="/css/custom_society.css"/><link rel="stylesheet" type="text/css" href="/css/custom_bill_report.css"/><link rel="stylesheet" type="text/css" href="/css/header-loginFrm.css"/><link rel="stylesheet" type="text/css" href="/css/font-awesome.css"/>    </head>
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
                    <a class="navbar-brand" href="index.php"><img src="https://easylogicstechnology.com/images/easylogistic-7-6.png" class="img-responsive"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style='display:none'>
                    <ul class="nav navbar-nav navbar-right" style='display:none'>>
                        <li><a href="">About</a></li>		
                        <li><a href="#">Services</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Login <span class="caret"></span></a>
                            <ul id="login-dp" class="dropdown-menu">
                                <li>
                                    <div class="row">
                                        <div class="col-md-12">
                                                                                        <form class="form" action="/users/login" role="form" method="post" id="login_form" method="post" id="login_form">
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
                                            <a href="/users/login"><b>Register Now</b></a>
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
            <img src="https://easylogicstechnology.com/images/society2.jpg" class="img-responsive">
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
                <div class="software-description-title" >Easy Logics Technology Society Management Software</div>
                <div class="row software-description-box" style='display:none'>
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
                    <div class="col-md-6 col-sm-6" style='display:none'>
                        <div class="software-description-img float-right"><img src="https://easylogicstechnology.com/images/billing.png" class="img-responsive"></div>
                    </div>
                </div>				
            </div>
        </div>
        <div class="software-description" style='display:none'>
            <div class="container">			
                <div class="row software-description-box">				
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-img-left"><img src="https://easylogicstechnology.com/images/ledger-heads.png" class="img-responsive"></div>
                    </div>
                    <div class="col-md-6 col-sm-6" >
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
        <div class="software-description-bg" >
            <div class="container">			
                <div class="row software-description-box">
                    <div class="col-md-6 col-sm-6">
                        <div class="software-description-list-title"><b></b>Office Address</b></div>
                        <div class="software-description-list">	
                                    MAXVENTUR OFFICS N.19<br/>
                                    NEAR CENTRAL PARK  NALLASOPRA (E)<br/>
                                    CONTACT NO.8208902649<br/>
		                            EMAIL ID:  akhileshchoudhary843@gmail.com<br/><br/>
	  
	                        FOUNDER: AKHILESH CHOUDHARY<br/>
	          				        (GNIT)   MUMBAI
                        </div>
                    </div>					
                    <div class="col-md-4 col-sm-4">
                        <div class="software-description-img float-right">
                            <form action="about_us.php" method="POST"><b>Enter Captcha</b><br/><br />
			<img src="captcha.php" />
			<br />
			<br />
			<input type="text" name="captcha" />
			<?php
				if(isset($_POST['verify'])){
					if(!empty($_POST['captcha'])){
						if($_SESSION['captcha'] == $_POST['captcha']){
							echo "<label class='text-success'>Captcha Validated</label>";
						}else{
							echo "<label class='text-danger'>Invalid captcha!</label>";
						}
					}else{
						echo "<label class='text-warning'>Please fill up the required field!</label>";
					}
				}
			?>
			<br /><br />
			<button name="verify" class="btn btn-primary">Verify</button>
			
		</form>
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
                    <p class="copyright">2025 Easy Logics Technology. All Rights Reserved.</p>
                </footer>
            </div>
        </div>
        <script type="text/javascript" src="/js/jquery-3.2.1.js"></script><script type="text/javascript" src="/js/bootstrap.js"></script><script type="text/javascript" src="/js/jquery.blockui.min.js"></script><script type="text/javascript" src="/js/common.js"></script>    </body>
<script>'undefined'=== typeof _trfq || (window._trfq = []);'undefined'=== typeof _trfd && (window._trfd=[]),_trfd.push({'tccl.baseHost':'secureserver.net'},{'ap':'cpbh-mt'},{'server':'p3plmcpnl499535'},{'dcenter':'p3'},{'cp_id':'7225095'},{'cp_cache':''},{'cp_cl':'8'}) // Monitoring performance to make your website faster. If you want to opt-out, please contact web hosting support.</script><script src='https://img1.wsimg.com/traffic-assets/js/tccl.min.js'></script></html>
