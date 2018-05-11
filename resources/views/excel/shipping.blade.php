<?php
	$total = 0;
?>
<html>
<body>
<table>
	<tr>
		<td style="background-color: #bbdefb;">Order Ship Date</td>
		<td style="background-color: #bbdefb;">Sun Valley Order</td>
		<td style="background-color: #bbdefb;">Customer Name / Acct #</td>
		<td style="background-color: #bbdefb;">Description</td>
		<td style="background-color: #bbdefb;">Steam/Bunch</td>
		<td style="background-color: #bbdefb;"># of Cases</td>
		<td style="background-color: #bbdefb;">Bunches per Box</td>
		<td style="background-color: #bbdefb;">Box Type</td>
		<td style="background-color: #bbdefb;">Total</td>
	</tr>
	<?php
	foreach ($data as $row)
	{
	?>
	<tr>
		<td><?=$row[0]?></td>
		<td><?=$row[1]?></td>
		<td><?=$row[2]?></td>
		<td><?=$row[3]?></td>
		<td><?=$row[4]?></td>
		<td><?=$row[5]?></td>
		<td><?=$row[6]?></td>
		<td><?=$row[7]?></td>
		<td><?=$row[8]?></td>
	</tr>
	<?php
		$total += $row[8];
	}
	?>
	<tr>
		<td align=right style="background-color: #bbdefb;" colspan=8> Total General</td>
		<td style="background-color: #bbdefb;"><?=$total?></td>
	</tr>
</table>
</body>
</html>