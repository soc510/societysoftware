<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>Easy Logics Technology Dashboard</title>
	<meta name="description" content="Easy Logics Technology is Management Software for Managing Committees & Property Managers, GST Compliant Society Accounting Software for Accountants & Auditors on a seamlessly integrated platform." />
	<meta name="keywords" content="admin, admin dashboard, admin template, cms, crm, Easy Logics Admin,software application" />
	<meta name="author" content="Easy Logics Tecnology"/>
	<link rel="shortcut icon" href="<?php echo $this->webroot; ?>img/fvcon.png">
	<link rel="icon" href="<?php echo $this->webroot; ?>img/fvcon.png" type="image/x-icon">
        <link href="<?php echo $this->webroot; ?>css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $this->webroot; ?>css/morris.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo $this->webroot; ?>css/jquery.toast.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot; ?>css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css">
        
        <link href="<?php echo $this->webroot; ?>css/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo $this->webroot; ?>css/vendors_css/select2.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo $this->webroot; ?>css/vendors_css/select2.min.css" rel="stylesheet" type="text/css">
        <link href="https://www.jquery-az.com/jquery/css/jquery.multiselect.css">
           <?php if(!empty($this->params['action']) && $this->params['action'] == 'assign_societies' && $this->params['controller'] == 'admin'){?>
    <?php }?>
        <link href="<?php echo $this->webroot; ?>css/custom_society.css" rel="stylesheet" type="text/css">
</head>