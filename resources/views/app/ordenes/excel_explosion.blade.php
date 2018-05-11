<html>
<body>
<table>
	<tr>
		<td>Skunumber</td>
		<td>Description</td>
		<td>Inventario</td>
		<td>Req. Fijas</td>
		<td>Restante</td>
		<td>Porcentaje</td>
	</tr>
	<?php
	$total_r = 0;
	$total_p = 0;
	foreach ($data as $row) {
		$total_r += $row["restante"];
		$total_p += $row["porciento"];
	?>
		<tr>
			<td><?php echo $row["skunumber"]; ?></td>
			<td><?php echo $row["skudesc"]; ?></td>
			<td><?php echo $row["inventario"]; ?></td>
			<td><?php echo $row["fijas"]; ?></td>
			<td><?php echo $row["restante"]; ?></td>
			<td><?php echo $row["porciento"]."%"; ?></td>
		</tr>
	<?php } ?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><?php echo $total_r; ?></td>
		<td><?php echo $total_p."%"; ?></td>
	</tr>
</table>
</body>
</html>