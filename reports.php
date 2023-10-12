<?php 
include 'header.php';
?>
<!-- Left Sidebar End -->
<div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="dashboard.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"> Reports List</li>
    </ol>
    <!-- DataTables Example -->
    <div class="card mb-3">
        
        <div class="card-body">
            <!--here your code will go-->
           <div class="row">
							<div class="col-sm-4">
								<h5>Material Reports</h5>
								<ul>
									<a href="materialinfo_report.php"><li><b>Material List</b></li></a>
									<a href="stock_report.php"><li><b>Stock Reports</b></li></a>
									<a href="movement_report.php"><li><b>Movement Reports</b></li></a>
								</ul>
								<h5>Equipment Reports</h5>
								<ul>
									<a href="equipments-history.php"><li><b>Equips.History Report</b></li></a>
								</ul>
							</div>
							
							<div class="col-sm-4">
								<h5>Assets Reports</h5>
								<ul>
									<a href="material-history.php"><li><b>Material History Report</b></li></a>
								</ul>
							</div>
						</div>
            <!--here your code will go-->
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<?php include 'footer.php' ?>