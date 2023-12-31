<style>
.dtext{
	text-decoration:underline;
}
.linktext{
	font-size:12px;
}

</style>
<?php 
error_reporting(E_ERROR | E_PARSE);
?>
<div class="card mb-3">
    <div class="card-header">
		<button class="btn btn-info linktext" onclick="window.location.href='stock_report.php';"> Stock Report Search</button>
		<button class="btn btn-info linktext" onclick="window.location.href='categorywise_stock_report.php';"> Categorywise Stock Report </button>
		<button class="btn btn-success linktext"> Materialwise Stock Report </button>
		<!-- <button class="btn btn-info linktext" onclick="window.location.href='typewise_stock_report.php';"> Typeywise Stock Report </button>
		<?php if($_SESSION['logged']['user_type'] !== 'whm') {?>
		<button class="btn btn-info linktext" onclick="window.location.href='total_stock_report.php';"> Total Stock Report</button>
		<button class="btn btn-info linktext" onclick="window.location.href='warehouse_stock_report.php';"> Warehouse Stock Report </button>
		<button class="btn btn-info linktext" onclick="window.location.href='warehouse_categorywise_stock_report.php';"> Warehouse Categorywise Stock Report </button>
		<?php } ?> -->
	</div>

    <div class="card-body">
        <form name="" action="" method="GET">
			<div class="col-xs-2">
				<div class="form-group">
					<label for="id">Parent Category</label><span class="reqfield"> ***required</span>
					<select class="form-control material_select_2" id="level_1_id_l5" name="parent_item_id" onchange="getMatByParent(this.value);">
						<option value="0">Parent Category</option>
						<?php
						$category_resize_data = category_tree();
						$html = '';
						function generateOptions($category_resize_data, $indent = 0) {
							foreach ($category_resize_data as $key => $value) {
								echo '<option value="' . $value['id'] . '">' . str_repeat('-', $indent * 1) . $value['id'].'-' .$value['category_description']. '</option>';
								if (is_array($value['children']) && !empty($value['children'])) {
									generateOptions($value['children'], $indent + 1);
								}
							}
						}
						
						generateOptions($category_resize_data);
						?>
					</select>
				</div>
			</div>
			<div class="col-xs-2">
				<div class="form-group">
					<label for="id">Material Name</label><span class="reqfield"> ***required</span>
					<select class="form-control material_select_2" id="level_2_id_l5" name="item_id">
						<option value="">Select</option>
						<?php
						$parentCats = getTableDataByTableName('inv_material','','material_description');
						if (isset($parentCats) && !empty($parentCats)) {
							foreach ($parentCats as $pcat) {
								/* if($_GET['item_id'] == $pcat['id']){
								$selected	= 'selected';
								}else{
								$selected	= '';
								} */
								?>
								<option value="<?php echo $pcat['id'] ?>" <?php //echo $selected; ?>><?php echo $pcat['material_description'] ?></option>
							<?php }
						} ?>
					</select>
				</div>
			</div>
			<div class="col-xs-2">
				<div class="form-group">
					<label for="id">Date</label><span class="reqfield"> ***required</span>
					<input type="text" class="form-control" id="to_date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" autocomplete="off" required >  				
				</div>
			</div>
			<div class="col-xs-2">
				<div class="form-group">
					<label for="id" style="color:#fff;">.</label>
					<input type="submit" name="submit" id="submit" class="btn btn-block" style="background-color:#007BFF;color:#ffffff;" value="SEARCH" />   				
				</div>
			</div>
			</form>
    </div>
</div>


	<?php
					if(isset($_GET['submit'])){
					$parent_item_id		=	$_GET['parent_item_id'];
					$item_id			=	$_GET['item_id'];
					$to_date			=	$_GET['to_date'];
						//$material_id_code = $material_name;
					$warehouse_id	    =	$_SESSION['logged']['warehouse_id'];
						
											
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
							<span>Materialwise Stock Report</span></br>
							<span>
								<?php
								$dataresult =   getDataRowByTableAndId('inv_materialcategorysub', $parent_item_id);
								echo (isset($dataresult) && !empty($dataresult) ? $dataresult->category_description : '');
								?>
							</span> <i class="fas fa-arrow-right"></i> 
							<span>
								<?php
								$dataresult =   getDataRowByTableAndId('inv_material', $item_id);
								echo (isset($dataresult) && !empty($dataresult) ? $dataresult->material_description : '');
								?>
							</span>
							<br>Till  <span class="dtext"><?php echo date("jS F Y", strtotime($to_date));?> </span><br>
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
							$sqlmat	=	"SELECT * FROM inv_material WHERE  `material_id` = '$parent_item_id' AND `id` = '$item_id' GROUP BY `material_id_code`;";
							$resultmat = mysqli_query($conn, $sqlmat);
							while($rowmat=mysqli_fetch_array($resultmat))
							{ ?>
						
						<tr>
							<td><?php echo $rowmat['material_description']; ?></td>
							<td><?php echo $rowmat['part_no']; ?></td>
							<td><?php echo $rowmat['spec']; ?></td>
							<td><?php echo getDataRowByTableAndId('inv_item_unit', $rowmat['qty_unit'])->unit_name; ?></td>
							<td>
								<?php 
									$mb_materialid = $rowmat['material_id_code'];
									
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
				<?php } ?>
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
	$(".material_select_2").select2();
</script>


