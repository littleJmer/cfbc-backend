<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

	<style>

		body {
			font-size: 12px;
			font-family: Helvetica;
		}

		table {
			border-collapse: collapse;
		}

		table, th, td {
			border: 1px solid black;
		}

	</style>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>Order Ship</th>
				<th>Load Date</th>
				<th>Sun Valley order</th>
				<th>Customer Name / Acct</th>
				<th>Description</th>
				<th>Stem</th>
				<th># of Box</th>
				<th>Bunches</th>
				<th>Box Type</th>
				<th>Suma de TOTAL DE TALLOS</th>
				<th>Suma de CAPACIDAD</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$totalTallos = 0;
			foreach ($ordenes as $orden) {
			?>
			<tr>
				<td></td>
				<td></td>
				<td><?=$orden->sun_valley_order?></td>
				<td><?=$orden->customer_name_acct?></td>

				<?php
				$first = true;
				foreach ($orden->cajas as $caja) {

					if(!$first) {
				?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				<?php
					}
					$totalRow = ($caja->bunches_per_box*$caja->number_of_cases*$caja->stem_per_bunches);
					$totalTallos += $totalRow;
				?>
					<td><?=$caja->description?></td>
					<td><?=$caja->stem_per_bunches?></td>
					<td><?=$caja->number_of_cases?></td>
					<td><?=$caja->bunches_per_box?></td>
					<td><?=$caja->box_type?></td>
					<td align=right><?=$totalRow?></td>
					<td></td>

				</tr>

				<?php
					$first = false;
				}
				?>

			<?php
			}
			?>
			<tr>
				<td colspan=2>Total general</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td align=right><?=$totalTallos?></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</body>
</html>