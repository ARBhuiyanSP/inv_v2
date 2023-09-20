<?php 
include 'header.php';
?>
<?php if(!check_permission('user-list')){ 
        include("404.php");
        exit();
 } ?>
<!-- Left Sidebar End -->
<div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="dashboard.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"> User List</li>
    </ol>
    <!-- DataTables Example -->
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i> User List
			<a href="user_entry.php" style="float:right"><i class="fas fa-list"></i> User Add<a>
		</div>
        <div class="card-body">
                        <?php include 'partial/rlp_approve_chain_list.php'; ?>
                   </div>
    </div>

</div>
<!-- /.container-fluid -->
<?php include 'footer.php' ?>