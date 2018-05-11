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

<?php
foreach ($order->products as $OrderProduct)
{ ?>
	<table>
		<tr>
			<td width="470px">
				<!-- <img class="logo" src="{{ asset('img/sun_valley.png') }}" alt=""> -->
			</td>
			<td>
				<table class="border">
					<tr>
						<th>Customer Name / Acct</th>
						<td>{{$order->customer_name_acct}}</td>
					</tr>
					<tr>
						<th>Customer PO Number</th>
						<td>{{$order->customer_po_number}}</td>
					</tr>
					<tr>
						<th>Salesman Name / Number</th>
						<td>{{$order->sales_rep_name}}</td>
					</tr>
					<tr>
						<th>Sun Valley Order #</th>
						<td>{{$order->sun_valley_order}}</td>
					</tr>
					<tr>
						<th>Oxnard Ship Via</th>
						<td>{{$order->orig_carrier}}</td>
					</tr>
					<tr>
						<th>Oxnard Ship Date</th>
						<td>{{$order->load_date}}</td>
					</tr>
					<tr>
						<th>Farm Ship Date</th>
						<td>{{$order->order_ship_date}}</td>
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
			<td><?=@$OrderProduct->description?></td>
			<td><?=@$OrderProduct->stem_bunch?></td>
			<td><?=@$OrderProduct->no_cases?></td>
			<td><?=@$OrderProduct->bunches_per_box?></td>
			<td><?=@$OrderProduct->box_type?></td>
			<td><?=@$OrderProduct->box_code_sku?></td>
		</tr>
		<tr>
			<th>UPC Type</th>
			<th colspan=2>Sleeve Name & Size</th>
			<th>Insert</th>
			<th colspan=2>Flower Food</th>
		</tr>
		<tr>
			<td><?=@$OrderProduct->upc_type?></td>
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
			$temporal = explode( "-", $OrderProduct->box_code_sku);
			preg_match("/\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})/", $OrderProduct->upc_type, $price);
			
			if( count($price) === 1 )
			{
				$price = "$".$price[0];
			}
			else
			{
				$price = "$0.00";
			}
		?>
		<tr>
			<td><?=@$OrderProduct->upc_no?></td>
			<td colspan=2><?php echo trim($temporal[1]); ?></td>
			<td><?=@$OrderProduct->date_code?></td>
			<td colspan=2><?php print_r($price); ?></td>
		</tr>
	</table>

	<br>

	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width=50%>
				

				<!--  -->
				<table class="items">
					<thead>
						<tr>
							<th>Name</th>
							<th>Flower</th>
							<th>Variedad</th>
							<th>Color</th>
							<th>Qty. Recipe</th>
							<th>Qty.</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$total 				= 0;

					$stem_bunch 		= $OrderProduct->stem_bunch;
					$no_cases 			= $OrderProduct->no_cases;
					$bunches_per_box 	= $OrderProduct->bunches_per_box;

					if( isset($OrderProduct->recipe->flowers) )
					{
						$recipe = $OrderProduct->recipe;
						foreach($OrderProduct->recipe->flowers as $flower)
						{
							# consolidado surtido
							if($recipe->type == 3)
								$quantity = $stem_bunch * $flower->pivot->quantity * $no_cases;
							else
								$quantity = $flower->pivot->quantity * $bunches_per_box * $no_cases;
					?>
							<tr>
								<td><?=@$flower->name_posco?></td>
								<td><?=@$flower->especie?></td>
								<td><?=@$flower->variedad?></td>
								<td><?=@$flower->color?></td>
								<td><?=@$flower->pivot->quantity?></td>
								<td><?=@$quantity?></td>
							</tr>
					<?php
							$total += $quantity;
						}
					}
					else
					{
					?>
						<tr>
							<td colspan=6 style="text-align:center;background-color: red;color: #fff;">ESTA RECETA NO EXISTE</td>
						</tr>
					<?php
					}
					?>
					<tr>
						<td colspan=5 style="text-align:right;">[ T O T A L ]</td>
						<td><?php echo $total; ?></td>
					</tr>
					</tbody>
				</table>
				<!--  -->

			</td>
			<td width=50%>
				
				<!--  -->
				<table class="items">
					<thead>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Size</th>
							<th>Qty. Recipe</th>
							<th>Qty.</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total 		= 0;
						$no 		= $OrderProduct->no_cases;
						$per 		= $OrderProduct->bunches_per_box;

						if( isset($OrderProduct->recipe->material) )
						{
							foreach($OrderProduct->recipe->material as $item)
							{
								$quantity = $item->quantity * $per * $no;
						?>
								<tr>
									<td><?=@$item->name?></td>
									<td><?=@$item->description?></td>
									<td><?=@$item->size?></td>
									<td><?=@$item->quantity?></td>
									<td><?=@$quantity?></td>
								</tr>
						<?php
								$total += $quantity;
							}
						}
						else
						{
						?>
							<tr>
								<td colspan=6 style="text-align:center;background-color: red;color: #fff;">ESTA RECETA NO EXISTE</td>
							</tr>
						<?php
						}
						?>
						<tr>
							<td colspan=4 style="text-align:right;">[ T O T A L ]</td>
							<td><?php echo $total; ?></td>
						</tr>
					</tbody>
				</table>
				<!--  -->

			</td>
		</tr>
	</table>

	<div class="page-break"></div>

<?php }
?>

</body>
</html>
