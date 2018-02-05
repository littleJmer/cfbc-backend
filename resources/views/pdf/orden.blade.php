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
			width: 500px !important;
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
						<td></td>
					</tr>
					<tr>
						<th>Oxnard Ship Date</th>
						<td></td>
					</tr>
					<tr>
						<th>Farm Ship Date</th>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<hr />

<?php
foreach ($order->products as $OrderProduct)
{ ?>

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
		<tr>
			<td><?=@$OrderProduct->upc_no?></td>
			<td colspan=2></td>
			<td></td>
			<td colspan=2></td>
		</tr>
	</table>

	<br>

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
		$total 		= 0;
		$no 		= $OrderProduct->no_cases;
		$per 		= $OrderProduct->bunches_per_box;

		if( isset($OrderProduct->recipe->flowers) )
		{
			foreach($OrderProduct->recipe->flowers as $flower)
			{
				$quantity = $flower->pivot->quantity * $per * $no;
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

	<div class="page-break"></div>

<?php }
?>

</body>
</html>
