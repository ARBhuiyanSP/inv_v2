<?php include 'header.php' ?>
<?php if(!check_permission('material-stock-report')){ 
        include("404.php");
        exit();
 } ?>
<style>
.dtext{
	text-decoration:underline;
}
.linktext{
	font-size:12px;
}

</style>
<div class="card mb-3">
    <div class="card-header">
	
		<button class="btn btn-success linktext"> Stock Report Search</button>
		<button class="btn btn-info linktext" onclick="window.location.href='categorywise_stock_report.php';"> Categorywise Stock Report </button>
		<button class="btn btn-info linktext" onclick="window.location.href='material_wise_stock_report.php';"> Materialwise Stock Report </button>
		<!-- <button class="btn btn-info linktext" onclick="window.location.href='typewise_stock_report.php';"> Typeywise Stock Report </button>
		<?php if($_SESSION['logged']['user_type'] !== 'whm') {?>
		<button class="btn btn-info linktext" onclick="window.location.href='total_stock_report.php';"> Total Stock Report</button>
		<button class="btn btn-info linktext" onclick="window.location.href='warehouse_stock_report.php';"> Warehouse Stock Report </button>
		<button class="btn btn-info linktext" onclick="window.location.href='warehouse_categorywise_stock_report.php';"> Warehouse Categorywise Stock Report </button>
		<?php } ?> -->
	</div>

    <div class="card-body">
        <form class="form-horizontal" action="" id="warehouse_stock_search_form" method="GET">
            <div class="table-responsive">          
                <table class="table table-borderless search-table">
                    <tbody>
                        <tr>  
							<td>
                                <div class="form-group">
                                    <label for="todate">To Date</label>
                                    <input type="text" class="form-control" id="to_date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" autocomplete="off" required >
                                </div>
                            </td>
							
							<td style="width:15%">
                                <div class="form-group">
                                    <label for="todate">.</label>
									<button type="submit" name="stockSubmit" class="form-control btn btn-primary" style="padding: 3px;">Stock Search</button>
                                </div>
                            </td>
							<td style="width:15%">
                                <div class="form-group">
                                    <label for="todate">.</label>
									<button type="submit" name="submit" class="form-control btn btn-primary" style="padding: 3px;">All Stock Search</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<?php
if(isset($_GET['submit'])){
	
	$to_date		=	$_GET['to_date'];
	$warehouse_id	=	$_SESSION['logged']['warehouse_id'];
	
	
?>
<center>
	
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10" id="printableArea">
			<div class="row">
				<div class="col-sm-12">	
					<center>
						<p>
							<img src="images/Saif_Engineering_Logo_165X72.png" height="50px;"/><br>
							<h5>CTED CHATTOGRAM</h5> 
							<span>All Material Stock Report</span><br>
							Till  <span class="dtext"><?php echo date("jS F Y", strtotime($to_date));?> </span><br>
						</p>
					</center>
				</div>
			</div>
				<table id="" class="table table-bordered table-striped ">
					<thead>
						<tr>
							<th colspan="2">Material Name</th>
							<th>Part No</th>
							<th>Specification</th>
							<th>Unit</th>
							<th width="10%">In Stock</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$sql	=	"SELECT * FROM inv_material  GROUP BY `material_id`";
						$result = mysqli_query($conn, $sql);
						while($row=mysqli_fetch_array($result))
						{
					?>
						<tr>
							<td>
								<?php 
								$dataresult =   getDataRowByTableAndId('inv_materialcategorysub', $row['material_id']);
								echo (isset($dataresult) && !empty($dataresult) ? $dataresult->category_description : '');
								?>
							</td>
							<td colspan="5"></td>
						</tr>
								<?php 
									$material_id = $row['material_id'];
									$sqlall	=	"SELECT * FROM inv_material WHERE `material_id` = '$material_id' GROUP BY `material_id_code`;";
									$resultall = mysqli_query($conn, $sqlall);
									while($rowall=mysqli_fetch_array($resultall))
									{ ?>
								
										
										<tr>
											<td></td>
											<td><?php echo $rowall['material_description']; ?></td>
											<td><?php echo $rowall['part_no']; ?></td>
											<td style=""><?php echo $rowall['spec']; ?></td>
											<td><?php echo getDataRowByTableAndId('inv_item_unit', $rowall['qty_unit'])->unit_name; ?></td>
											<td style="text-align:right;">
												<?php 
													$mb_materialid = $rowall['material_id_code'];
													
													if($_SESSION['logged']['user_type'] !== 'whm'){
														$sqlinqty = "SELECT SUM(`mbin_qty`) AS totalin FROM `inv_materialbalance` WHERE `mb_materialid` = '$mb_materialid' AND mb_date <= '$to_date'";
													}else{
														$sqlinqty = "SELECT SUM(`mbin_qty`) AS totalin FROM `inv_materialbalance` WHERE warehouse_id = $warehouse_id AND `mb_materialid` = '$mb_materialid' AND mb_date <= '$to_date'";
													}
													
													
													$resultinqty = mysqli_query($conn, $sqlinqty);
													$rowinqty = mysqli_fetch_object($resultinqty) ;
													
													if($_SESSION['logged']['user_type'] !== 'whm'){
														$sqloutqty = "SELECT SUM(`mbout_qty`) AS totalout FROM `inv_materialbalance` WHERE `mb_materialid` = '$mb_materialid' AND mb_date <= '$to_date'";
													}else{
														$sqloutqty = "SELECT SUM(`mbout_qty`) AS totalout FROM `inv_materialbalance` WHERE warehouse_id = $warehouse_id AND `mb_materialid` = '$mb_materialid' AND mb_date <= '$to_date'";
													}
													
													$resultoutqty = mysqli_query($conn, $sqloutqty);
													$rowoutqty = mysqli_fetch_object($resultoutqty) ;
													
													$instock = $rowinqty->totalin -$rowoutqty->totalout;
													
												
												
													$minStock = $rowall['material_min_stock'];
													if($minStock >= $instock){
											?>
												<span><img src="images/alert.gif" height="15px"/></span>
											<?php }echo number_format((float)$instock, 2, '.', ''); ?>
												
											</td>
										</tr>
								<?php } 
									
								} 
								?>
					</tbody>
				</table>
				<center><div class="row">
					<div class="col-sm-6"></br></br>--------------------</br>Receiver Signature</div>
					<div class="col-sm-6"></br></br>--------------------</br>Authorised Signature</div>
				</div></center></br>
				<div class="row">
					<div class="col-sm-12" style="border:1px solid gray;border-radius:5px;padding:10px;color:#f26522;">
						<center><h5>Notice***</br><span style="font-size:14px;color:#000000;">Please Check Everything Before Signature</span></h5></center>
						
					</div>
				</div>
			</div>			
		</div>
		<center><button class="btn btn-default" onclick="printDiv('printableArea')"><i class="fa fa-print" aria-hidden="true" style="    font-size: 17px;"> Print</i></button></center>
		<div class="col-md-1"></div>
</center>
<?php } else if(isset($_GET['stockSubmit'])){ 
	$to_date		=	$_GET['to_date'];
	$warehouse_id	=	$_SESSION['logged']['warehouse_id'];
?>
<center>
	
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10" id="printableArea">
			<div class="row">
				<div class="col-sm-12">	
					<center>
						<p>
							<img src="images/Saif_Engineering_Logo_165X72.png" height="50px;"/><br>
							<h5>CTED CHATTOGRAM</h5>
							<span>Material Stock Report</span><br>
							Till  <span class="dtext"><?php echo date("jS F Y", strtotime($to_date));?> </span><br>
						</p>
					</center>
				</div>
			</div>
			<?php
					
			$sql = "SELECT t2.id,t2.id as category_id,t2.parent_id,t2.category_description
FROM `inv_material` as t1
INNER JOIN `inv_materialcategorysub` as t2 ON t1.material_id=t2.id
INNER join `inv_materialbalance` as t3 ON t3.mb_materialid=t1.material_id_code
WHERE 1=1 AND t3.mb_date <= '$to_date' GROUP BY t2.id ";
$result = mysqli_query($conn, $sql);	
$category_resize_data =[]; 
 while ($row = $result->fetch_assoc()) {
        $category_resize_data[] = $row;
    }
    $data =  buildTreeCateogy($category_resize_data);	
	
					
 function fetch_category_wise_data($cateory_id,$to_date){
 	global $conn;
 	$sql2=" SELECT t1.mb_date,t2.material_id,t1.mb_materialid,t2.material_description,t2.spec,t2.part_no,t2.qty_unit, t3.unit_name,SUM(t1.mbin_qty-t1.mbout_qty) as qty_balance,t1.mb_materialid FROM `inv_materialbalance` AS t1
			INNER JOIN inv_material AS t2 ON t1.mb_materialid=t2.material_id_code
			INNER JOIN inv_item_unit AS t3 ON t3.id=t2.qty_unit
			WHERE 1=1 AND t2.material_id=$cateory_id AND t1.mb_date <= '$to_date'   GROUP BY t1.mb_materialid ";
$result = mysqli_query($conn, $sql2);
while ($val = $result->fetch_assoc()) {
	echo  "<tr>
			<td colspan='2'>".$val['mb_materialid']."-".$val['material_description']."</td>
			<td>".$val['part_no']."</td>
			<td>".$val['spec']."</td>
			<td>".$val['unit_name']."</td>
			<td>".$val['qty_balance']."</td>
		</tr>";
}

 }			
					  

?>
				<table id="" class="table table-bordered table-striped ">
					<thead>
						<tr>
							<th colspan="2">Material Name</th>
							<th>Part No</th>
							<th>Specification</th>
							<th>Unit</th>
							<th width="10%">In Stock</th>
						</tr>
					</thead>
					<tbody>
					 
					 <?php
                                    $html = '';
                                    function generateOptions($data, $indent = 0,$to_date='') {
                                        foreach ($data as $key => $value) {?>
                                           <tr>
                                           	<td colspan="6"><b><?php echo str_repeat('-', $indent * 1).$value['category_id']."-" .$value['category_description']; ?></b></td>
                                           </tr>
                                          
                                           <?php
                                            //Fetch Root Level Data By Category Id
                                            fetch_category_wise_data($value['category_id'],$to_date);
                                            ?>
                                          <?php   if (isset($value['children']) && is_array($value['children']) && !empty($value['children'])) {
                                                generateOptions($value['children'], $indent + 1,$to_date);
                                            }
                                        }
                                    }
                                    
                                    generateOptions($data,$indent = 0,$to_date);
                                    ?>
					
					
					
					
						 
					</tbody>
				</table>
				<center><div class="row">
					<div class="col-sm-6"></br></br>--------------------</br>Receiver Signature</div>
					<div class="col-sm-6"></br></br>--------------------</br>Authorised Signature</div>
				</div></center></br>
				<div class="row">
					<div class="col-sm-12" style="border:1px solid gray;border-radius:5px;padding:10px;color:#f26522;">
						<center><h5>Notice***</br><span style="font-size:14px;color:#000000;">Please Check Everything Before Signature</span></h5></center>
						
					</div>
				</div>
			</div>			
		</div>
		<center><button class="btn btn-default" onclick="printDiv('printableArea')"><i class="fa fa-print" aria-hidden="true" style="    font-size: 17px;"> Print</i></button></center>
		<div class="col-md-1"></div>
</center>
<?php } ?>
<script>
function printDiv(divName) {
	 var printContents = document.getElementById(divName).innerHTML;
	 var originalContents = document.body.innerHTML;

	 document.body.innerHTML = printContents;

	 window.print();

	 document.body.innerHTML = originalContents;
}
</script>
<script>
    $(function () {
        $("#from_date").datepicker({
            inline: true,
            dateFormat: "yy-mm-dd",
            yearRange: "-50:+10",
            changeYear: true,
            changeMonth: true
        });
    });
</script>
<script>
    $(function () {
        $("#to_date").datepicker({
            inline: true,
            dateFormat: "yy-mm-dd",
            yearRange: "-50:+10",
            changeYear: true,
            changeMonth: true
        });
    });
</script>

<?php include 'footer.php' ?>


