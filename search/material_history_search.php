<style>
.dtext{
	text-decoration:underline;
}


table th{
	 font-size:14px;
}
table td{
	 font-size:14px;
}
.table th, .table td{
	padding:5px;
}
</style>
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-search"></i>
        Material Report Search</div>
    <div class="card-body">
        <form class="form-horizontal" action="" id="warehouse_stock_search_form" method="GET">
            <div class="table-responsive">          
                <table class="table table-borderless search-table">
                    <tbody>
                        <tr>  
							<td width="30%">
                                <div class="form-group">
                                    <label for="todate">Input Material Name/Part No/Specifications</label>
                                    <select class="form-control material_select_2" id="material_name" name="material_name" required>
										<option value="">Select</option>
										<?php
										$projectsData = get_product_with_category();
										if (isset($projectsData) && !empty($projectsData)) {
											foreach ($projectsData as $data) {
												if($_GET['material_name'] == $data['item_code']){
												$selected	= 'selected';
												}else{
												$selected	= '';
												}
												?>
												<option value="<?php echo $data['item_code']; ?>"  <?php echo $selected; ?>><?php echo $data['material_name']; ?> - <?php echo $data['part_no']; ?> - <?php echo $data['spec']; ?></option>
												<?php
											}
										}
										?>
									</select>
                                </div>
                            </td>
							<td width="15%">
                                <div class="form-group">
                                    <label for="todate">From Date</label>
                                    <input type="text" class="form-control" id="from_date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" autocomplete="off" required >
                                </div>
                            </td>
							<td width="15%">
                                <div class="form-group">
                                    <label for="todate">To Date</label>
                                    <input type="text" class="form-control" id="to_date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" autocomplete="off" required >
                                </div>
                            </td>
							
							<td width="15%">
                                <div class="form-group">
                                    <label for="todate">.</label>
									<button type="submit" name="submit" class="form-control btn btn-primary">Search</button>
                                </div>
                            </td>
							<td width="25%"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<?php
if(isset($_GET['submit'])){
	
	$material_name	=	$_GET['material_name'];
	$from_date		=	$_GET['from_date'];
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
							<span>Material History Report</span></br>
							<?php 
								$material_id_code = $material_name;
								$sqlmat	=	"SELECT * FROM `inv_material` WHERE `material_id_code` = '$material_id_code' ";
								$resultmat = mysqli_query($conn, $sqlmat);
								$rowmat=mysqli_fetch_array($resultmat);
							?>
							<span style="font-size:18px;font-weight:bold;">Material Name : <?php echo $rowmat['material_description']; ?></span></br>
							<span style="font-size:18px;">Part No : <?php echo $rowmat['part_no']; ?></span> | 
							<span style="font-size:18px;">Specifications : <?php echo $rowmat['spec']; ?></span></br>
							From <span class="dtext"><?php echo date("jS F Y", strtotime($from_date));?></span> To  <span class="dtext"><?php echo date("jS F Y", strtotime($to_date));?> </span><br>
						</p>
					</center>
				</div>
			</div>
				<table id="" class="table table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Date</th>
							<th>Ref Voucher/Issue No</th>
							
							<th>Unit</th>
							<th>In QTY</th>
							<th>Out QTY</th>
							<th>Stock QTY</th>
							<th>Remarks</th>

						</tr>
					</thead>
					<tbody>
						<tr>
							<th colspan="4" style="text-align:right;">Opening Quantity</th>
							<th></th>
							<th style="text-align:right;">
									<?php 
									if($_SESSION['logged']['user_type'] !== 'whm'){
										$sqlpreinqty = "SELECT SUM(`mbin_qty`)- SUM(`mbout_qty`) AS totalpre FROM `inv_materialbalance` WHERE `mb_materialid` = '$material_id_code' AND `mb_date` < '$from_date'";
									}else{
										$sqlpreinqty = "SELECT SUM(`mbin_qty`)- SUM(`mbout_qty`) AS totalpre FROM `inv_materialbalance` WHERE `warehouse_id` = '$warehouse_id' AND `mb_materialid` = '$material_id_code' AND `mb_date` < '$from_date'";
									}
									
									$resultpreinqty = mysqli_query($conn, $sqlpreinqty);
									$rowpreinqty = mysqli_fetch_object($resultpreinqty);
									
									if($rowpreinqty->totalpre > 0){
										$opening_stock = $rowpreinqty->totalpre;
									}
									else {
											$opening_stock = 0;
										}
									echo $opening_stock;
									//echo number_format((float)$opening_stock, 2, '.', '');
								?>
							</th>
							<th colspan="3"></th>
						</tr>
						<?php
							$sl = 0;
							$totalin = 0;
							$totalout = 0;
							$sqlall	=	"SELECT * FROM `inv_materialbalance` WHERE `mb_materialid` = '$material_name' AND `mb_date` BETWEEN '$from_date' AND '$to_date';";
							//$sqlall	=	"SELECT * FROM `inv_materialbalance` WHERE `mb_materialid` = '$material_name';";
							$resultall = mysqli_query($conn, $sqlall);
							while($rowall=mysqli_fetch_array($resultall))
							{	
								$sl++;
						
								$inQty = $rowall['mbin_qty'];
								$totalin += $rowall['mbin_qty'];
								
								$outQty = $rowall['mbout_qty'];
								$totalout += $rowall['mbout_qty'];
									
								$balance = $totalin - $totalout;
						?>
						<tr>
							<td><?php echo $sl;?></td>
							<td><?php echo date("j M y", strtotime($rowall['mb_date']));?></td>
							<td><?php echo $rowall['mb_ref_id']; ?></td>
							
							<!-- <td><?php 
							$issue_id = $rowall['mb_ref_id'];
							$material_id = $rowall['mb_materialid'];
							$sqluse	=	"SELECT * FROM `inv_issuedetail` WHERE `issue_id` = '$issue_id' AND `material_id` = '$material_id';";
							$resultuse = mysqli_query($conn, $sqluse);
							$rowuse=mysqli_fetch_array($resultuse);
								echo $rowuse['use_in'];
							?></td> -->
							<td><?php echo getDataRowByTableAndId('inv_item_unit', $rowmat['qty_unit'])->unit_name;?>
							</td>
							
							<td style="text-align:right;"><?php echo $rowall['mbin_qty']; ?></td>
							<td style="text-align:right;"><?php echo $rowall['mbout_qty']; ?></td>
							<td style="text-align:right;"><?php echo $opening_stock + $balance; ?></td>
							
							
							<td></td>
						</tr>
						<?php } ?>
						<tr style="text-align:right;font-weight:bold;">
							<td colspan="4">Total:</td>
							<td>
								<?php 
									$sqlin = "SELECT sum(mbin_qty) FROM `inv_materialbalance` WHERE `mb_materialid` = '$material_name' AND `mb_date` BETWEEN '$from_date' AND '$to_date'";
									//$sqlin = "SELECT sum(mbin_qty) FROM `inv_materialbalance` WHERE `mb_materialid` = '$material_name'";
									$resultin = mysqli_query($conn, $sqlin);
									for($i=0; $rowin = mysqli_fetch_array($resultin); $i++){
									$totalIn=number_format((float)$rowin['sum(mbin_qty)'], 2, '.', '');
									
									echo $totalIn = $totalIn + $opening_stock ;
									}
								?>
							</td>
							<td>
								<?php 
									$sqlout = "SELECT sum(mbout_qty) FROM `inv_materialbalance` WHERE `mb_materialid` = '$material_name' AND `mb_date` BETWEEN '$from_date' AND '$to_date'";
									//$sqlout = "SELECT sum(mbout_qty) FROM `inv_materialbalance` WHERE `mb_materialid` = '$material_name'";
									$resultout = mysqli_query($conn, $sqlout);
									for($i=0; $rowout = mysqli_fetch_array($resultout); $i++){
									$totalOut=number_format((float)$rowout['sum(mbout_qty)'], 2, '.', '');
									
									echo $totalOut ;
									}
								?>
							</td>
							<td><?php echo $totalIn-$totalOut ; ?></td>
							<td></td>
						</tr>
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


