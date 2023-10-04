<?php session_start(); 
if(!isset($_SESSION['logged']['status'])){
    header("location: index.php");
    exit();
}
include 'connection/connect.php';
include 'helper/utilities.php';
include 'includes/item_process.php';
include 'includes/receive_process.php';
include 'includes/transfer_process.php';
//include 'includes/rlp_process.php';
include 'includes/issue_process.php';
include 'includes/search_process.php';
include 'includes/warehouse_search_process.php';
include 'includes/project_process.php';
include 'includes/unit_process.php';
include 'includes/package_process.php';
include 'includes/building_process.php';
include 'includes/warehouse_process.php';
include 'includes/suppliers_process.php';
include 'includes/format_process.php';
include 'includes/return_process.php';
include 'includes/payment_process.php';
include 'includes/equipment_process.php';
include 'function/rlp_process.php';
include 'function/rlp_chain_process.php';
include 'function/notesheet_processing.php';
include 'function/notesheet_chain_process.php';
include 'function/user_management.php';
include 'includes/user_process.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="images/fav.png">
  <title>88i Inventory</title>
  <!-- Custom fonts for this template-->
  <link href="css/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="css/sweetalert.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/jquery-ui.css" rel="stylesheet">
  <link href="css/site_style.css" rel="stylesheet">
  <link href="css/form-entry.css" rel="stylesheet">
  <link href="css/select2.min.css" rel="stylesheet">
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/select2.min.js"></script>
</head>
<style>
/* body {
  background-image: url("images/bg2.jpg");
  background-color: #cccccc;
} */

.mborder{
	padding:3px;
	border:1px solid #808080;
}
.authimg{
	position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
    max-width: 100%;
    max-height: 100%;
}

.reqfield{
	color:red;
	font-style: italic;
	font-size:10px;
	font-weight:bold;
}
.table th, .table td {
  padding:3px !important;
}
</style>
<body id="page-top">
  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <a class="navbar-brand mr-1" href="index.php"><img src="images/logo-wide.png" height="30px;"/></a>
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

	<!-- Menu -->
    <ul class="navbar-nav ml-auto ml-md-0">
	  <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i> Master Setup
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <?php if(check_permission('category-list')){ ?>
                    <a class="dropdown-item" href="category.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> Material Category</span>
            </a>
             <?php    } ?>
            
			
			<?php
            
                if(check_permission('material-list')){ ?>
                    <a class="dropdown-item" href="material.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> Material</span>
            </a>
             <?php    } ?>
			 
             <?php
            
                if(check_permission('unit-list')){ ?>
                    <a class="dropdown-item" href="unit_entry.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> UOM</span>
            </a>
             <?php    } ?>
			
        <?php
            
                if(check_permission('project-list')){ ?>
                    <a class="dropdown-item" href="project_entry.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> Projects</span>
            </a>
             <?php    } ?>
            
            <?php
            
                if(check_permission('warehouse-list')){ ?>
                    <a class="dropdown-item" href="warehouse_entry.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> Warehouse</span>
            </a>
             <?php    } ?>
           
        
            <?php
            
                if(check_permission('equipment-list')){ ?>
                    <a class="dropdown-item" href="equipment_entry.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> Euipments</span>
            </a>
             <?php    } ?>
			 
			 
			   <?php
            
                if(check_permission('role-list')){ ?>
                    <a class="dropdown-item" href="role-list.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> Role</span>
            </a>
             <?php    } ?>
			 
			 
            <?php
            
                if(check_permission('role-list')){ ?>
                    <a class="dropdown-item" href="rlp_approve_chain_list.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> RLP Approval Chain</span>
            </a>
             <?php    } ?>
			 
			  
            <?php
            
                if(check_permission('role-list')){ ?>
                    <a class="dropdown-item" href="notesheet_approve_chain_list.php">
                <i class="fa fa-bullseye" aria-hidden="true" style="color: #007BFF;"></i>
                <span class="sub_menu_text_design"> Notesheet Approval Chain</span>
            </a>
             <?php    } ?>
		   
          <!--<a class="dropdown-item" href="#">Settings</a>-->
          <!--<a class="dropdown-item" href="#">Activity Log</a>-->
        </div>
      </li>
	  <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i> Settings
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
               <?php if(check_permission('user-list')){ ?>
				
					<a class="nav-link" href="user-list.php">
						<i class="fa fa-users" aria-hidden="true" style="color: #000;"></i>
						<span style="color: #000;">Users</span></a>
				
			<?php } ?>
		   
          <!--<a class="dropdown-item" href="#">Settings</a>-->
          <!--<a class="dropdown-item" href="#">Activity Log</a>-->
        </div>
      </li>
    </ul>
	
	
	<div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" style="color: white;"><?php echo $_SESSION['logged']['user_name']; ?>-[<?php echo $_SESSION['logged']['user_name']; ?>]</div>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
           <a class="dropdown-item" href="profile.php">Profile</a>
          <a class="dropdown-item" href="includes/logout.php">Logout</a>
          <!--<a class="dropdown-item" href="#">Settings</a>-->
          <!--<a class="dropdown-item" href="#">Activity Log</a>-->
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <?php include 'sidebar.php' ?>

    <div id="content-wrapper" style="">    
        <!-- Sidebar -->
    <?php include 'operation_message.php'; ?>