<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body{background-color:#fafafa; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; text-decoration:none; font-size:15px; color:#333; line-height:25px}
a:link{color:#005580; text-decoration:none}
a:active{color:#005580; text-decoration:none}
a:visited{color:#005580; text-decoration:none}
a:hover{color:#0085c9; text-decoration:none}
#header{ background:url(img/header_bg.jpg) repeat-x 0 0; height:100px; width:100%; border-bottom:2px solid #0e7cd3}
.logo{ float:left; margin: 0 0 0 2%}
.header_title{color:#005395; font-size:45px;font-family: "myriad Pro", arial, sans-serif; float:left; margin:17px 0 0 182px;}
#footer {padding:0;	border-top:1px solid #ddd;	background-color:#e6e6e6;
					background: -moz-linear-gradient(#e6e6e6, #eeeeee);
					background-image: -webkit-gradient(linear, left top, left bottom, from(#eeeeee), to(#eeeeee));
					background: -webkit-linear-gradient(#e6e6e6, #eeeeee);
					background: -o-linear-gradient(#e6e6e6, #eeeeee);
					background: -ms-linear-gradient(#e6e6e6, #eeeeee);
					background: linear-gradient(#e6e6e6, #eeeeee);
	width:100%;
	height:50px;
	margin:30px 0 0 0; text-align:center; font-size:12px;
}
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" id="header">
	<a href="#" class="logo"><img src="app/webroot/img/logo_login.png" /></a>
    <p class="header_title">Project Tracker</p>
    </td>
  </tr>
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="96%">
        <?php echo $this->fetch('content'); ?>
    </td>
    <td width="2%">&nbsp;</td>
  </tr>
   <tr>
    <td colspan="3" height="50"></td>
  </tr>
  <tr>
    <td colspan="3" id="footer">&copy; <?php echo date('Y'); ?> TAXease</td>
  </tr>
</table>

</body>
</html>