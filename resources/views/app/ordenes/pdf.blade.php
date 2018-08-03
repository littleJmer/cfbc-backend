<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Impresión de Orden</title>
	<style type="text/css">
		.page-break { page-break-after: always; }

		table {
			width: 100%;
			font-size: 12px;
			font-family: Arial, Helvetica, sans-serif;
		}

		td, th {
			vertical-align: top;
			padding: 5px;
		}

		table.border,
		table.border td,
		table.border th {
		   border: 1px solid black;
		   border-collapse: collapse;
		}

		table.items {
			width: 100% !important;
			border-collapse: collapse;
		}

		table.items thead tr th {
			background-color: #A2A2A2;
			color: #fff;
		}

		table.items tr:nth-child(even) {background: #F4F4F4}
		table.items tr:nth-child(odd) {background: #FFF}

		.logo {
			width: 100%;
			height: auto;
			margin: 0;
			padding: 0;
			display: block;
		}
	</style>
</head>
<body>
<?php /*echo "<pre>"; print_r($orden); echo "</pre>";*/ ?>
<?php
$contador = 0;
foreach ($ordenes as $orden) {

	/*
	 |--------------------------------------------------------------------------
	 | Ciclo por cajas
	 |--------------------------------------------------------------------------
	 |
	 | 
	 */
	foreach ($orden["cases"] as $case ) { 

		if($contador > 0)
			echo "<div class=\"page-break\"></div>";

		?>
		<table>
			<tr>
				<td width="470px">
					<!-- <img class="logo" src="{{ asset('img/sun_valley.png') }}" alt=""> -->
				</td>
				<td>
					<table class="border">
						<tr>
							<th>Customer Name / Acct</th>
							<td><?php echo $orden["client"]." / " . $orden["acc"]; ?></td>
						</tr>
						<tr>
							<th>Customer PO Number</th>
							<td><?php echo $orden["customer_po_number"]; ?></td>
						</tr>
						<tr>
							<th>Salesman Name / Number</th>
							<td><?php echo $orden["sales_rep_name"]; ?></td>
						</tr>
						<tr>
							<th>Sun Valley Order #</th>
							<td><?php echo $orden["sun_valley_order"]; ?></td>
						</tr>
						<tr>
							<th>Oxnard Ship Via</th>
							<td><?php echo $orden["orig_carrier"]; ?></td>
						</tr>
						<tr>
							<th>Oxnard Ship Date</th>
							<td><?php echo $orden["load_date"]; ?></td>
						</tr>
						<tr>
							<th>Farm Ship Date</th>
							<td><?php echo $orden["ship_date"]; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<hr />

		<table class="border">
			<tr>
				<th>Product Description</th>
				<th>Stem / Bunch</th>
				<th># of Cases</th>
				<th>Bunches per Box</th>
				<th>Box Type</th>
				<th>Box Code / SKU</th>
			</tr>
			<tr>
				<td><?php echo $case["description"]; ?></td>
				<td><?php echo $case["stem_per_bunches"]; ?></td>
				<td><?php echo $case["number_of_cases"]; ?></td>
				<td><?php echo $case["bunches_per_box"]; ?></td>
				<td><?php echo $case["box_type"]; ?></td>
				<td><?php echo $case["box_code_sku"]; ?></td>
			</tr>
			<tr>
				<th>UPC Type</th>
				<th colspan=2>Sleeve Name & Size</th>
				<th>Insert</th>
				<th colspan=2>Flower Food</th>
			</tr>
			<tr>
				<td><?php echo $case["upc_type"]; ?></td>
				<td colspan=2></td>
				<td></td>
				<td colspan=2></td>
			</tr>
			<tr>
				<th>UPC # (Include Check Digital)</th>
				<th colspan=2>Description on Label</th>
				<th>Date Code</th>
				<th colspan=2>Retail Price</th>
			</tr>
			<?php
				// \d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})
				$temporal = explode( "-", $case["upc_number"]);

				if(!isset($temporal[1])) {
					$temporal = ['',''];
				}

				preg_match("/\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})/", $case["upc_type"], $price);
				
				if( count($price) === 1 ) {
					$price = "$".$price[0];
				}
				else {
					$price = "$0.00";
				}
			?>
			<tr>
				<td><?php echo $case["upc_number"]; ?></td>
				<td colspan=2><?php echo trim($temporal[1]); ?></td>
				<td><?php echo $orden["date_code"]; ?></td>
				<td colspan=2><?php echo $price; ?></td>
			</tr>
		</table>

		<br>

		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<!--/*
				 |--------------------------------------------------------------------------
				 | flowers
				 |--------------------------------------------------------------------------
				 |
				 | 
				 */-->
				<td width=100%>
					<table class="items">
						<thead>
							<tr>
								<th>Skunumber</th>
								<th>Flower</th>
								<th>Variety / Color</th>
								<th>Bunches</th>
								<th>Steam</th>
								<th>Total Steam</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$flowers = $case["flowers"];
						$total = 0;
						$totalBunch = 0;
						foreach ($flowers as $flower) {
							
							$totalBunch += $flower["bunch_qty"];

							$result_qty = $flower["stem_count"] * $flower["bunch_qty"];
							// $result_qty = $flower["stem_count"] * $flower["bunch_qty"] * $case["number_of_cases"];
							// $result_qty = $case["stem_per_bunches"] * $case["bunches_per_box"] * $case["number_of_cases"];

						?>
							<tr>
								<td><?php echo $flower["skunumber"]; ?></td>
								<td><?php echo $flower["flower_text"]; ?></td>
								<td><?php echo $flower["variety_color_text"]; ?></td>
								<td><?php echo $flower["bunch_qty"]; ?></td>
								<td><?php echo $flower["stem_count"]; ?></td>
								<td><?php echo $result_qty; ?></td>
							</tr>
						<?php
							$total += $result_qty;
						}?>
							<tr>
								<td style="text-align:center;background-color: yellow;">[ T O T A L ]</td>
								<td style="background-color: yellow;"></td>
								<td style="background-color: yellow;"></td>
								<td style="background-color: yellow;"><?php echo $totalBunch; ?></td>
								<td style="background-color: yellow;"></td>
								<td style="background-color: yellow;"><?php echo $total; ?></td>
							</tr>
						</tbody>
					</table>
				</td>
				<!--
				 |--------------------------------------------------------------------------
				 | Materiales
				 |--------------------------------------------------------------------------
				 |
				 | 
				 -->
				<!-- <td width=50%>
					<table class="items">
						<thead>
							<tr>
								<th>Name</th>
								<th>Description</th>
								<th>Size</th>
								<th>Qty</th>
								<th>Qty.</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan=5 style="text-align:center;background-color: red;color: #fff;">MATERIALES NO DISPONIBLES</td>
							</tr>
							<tr>
								<td colspan=4 style="text-align:right;">[ T O T A L ]</td>
								<td>0</td>
							</tr>
						</tbody>
					</table>
				</td> -->
			</tr>
		</table>
		<!-- <div class="page-break"></div> -->
	<?php $contador++; } ?>

<?php } ?>
</body>
</html>
