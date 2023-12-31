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
		<button class="btn btn-info linktext" onclick="window.location.href='stock_report.php';"> Stock Report Search</button>
		<button class="btn btn-success linktext"> Categorywise Stock Report </button>
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
									<label for="sel1">Material Category:</label>
									<select class="form-control select2" id="material_id" name="material_id">
										<option value="">Select</option>
										<?php
										$parentCats = getTableDataByTableName('inv_materialcategorysub', '', 'category_description');
										if (isset($parentCats) && !empty($parentCats)) {
											foreach ($parentCats as $pcat) {
												if($_GET['material_id'] == $pcat['id']){
													$selected	= 'selected';
													}else{
													$selected	= '';
													}
												?>
												<option value="<?php echo $pcat['id'] ?>" <?php echo $selected; ?>><?php echo $pcat['category_description'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
                            </td>
							<td>
                                <div class="form-group">
                                    <label for="todate">To Date</label>
                                    <input type="text" class="form-control" id="to_date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" autocomplete="off" required >
                                </div>
                            </td>
							
							<td>
                                <div class="form-group">
                                    <label for="todate">.</label>
									<button type="submit" name="submit" class="form-control btn btn-primary">Search</button>
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
	
	$material_id	=	$_GET['material_id'];
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
							<span>Categorywise Material Stock Report</span><br>
							<span>Category:<?php 
								$dataresult =   getDataRowByTableAndId('inv_materialcategorysub', $material_id);
								echo (isset($dataresult) && !empty($dataresult) ? $dataresult->category_description : '');
								?></span><br>
							Till  <span class="dtext"><?php echo date("jS F Y", strtotime($to_date));?> </span><br>
						</p>
					</center>
				</div>
			</div>
				<table id="" class="table table-bordered table-striped ">
					<thead>
						<tr>
							<th>Material Name</th>
							<th>Part No</th>
							<th>Specification</th>
							<th>Unit</th>
							<th>In Stock</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$sql	=	"SELECT * FROM inv_material WHERE `material_id`='$material_id'  GROUP BY `material_id`";
						$result = mysqli_query($conn, $sql);
						while($row=mysqli_fetch_array($result))
						{
					 
									$material_id = $row['material_id'];
									$sqlall	=	"SELECT * FROM inv_material WHERE `material_id` = '$material_id' GROUP BY `material_id_code`;";
									$resultall = mysqli_query($conn, $sqlall);
									while($rowall=mysqli_fetch_array($resultall))
									{ ?>
								
								
										
										<tr>
											<td><?php echo $rowall['material_description']; ?></td>
											<td><?php echo $rowall['part_no']; ?></td>
											<td><?php echo $rowall['spec']; ?></td>
											<td><?php echo getDataRowByTableAndId('inv_item_unit', $rowall['qty_unit'])->unit_name; ?></td>
											<td>
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
													
													echo $rowinqty->totalin -$rowoutqty->totalout;
												?>
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
<?php }?>
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


