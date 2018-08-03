<?php 

	
	$colspan = count($dates);

?>

<table>
	<tbody>
		<tr>
			<td></td>
			<td></td>
			<td style="font-size: 16" colspan=<?=$colspan?> align=center>
				Días
			</td>
		</tr>
		<tr>
			<td style="font-size: 16; background-color: blue; color: #FFFFFF;">Flor</td>
			<td style="font-size: 16; background-color: blue; color: #FFFFFF;"></td>
			<?php
				for ($i=0; $i < $colspan ; $i++) { 
			?>
				<td style="font-size: 16; background-color: blue; color: #FFFFFF;"><?=$dates[$i]?></td>
			<?php
				}
			?>
		</tr>
		<!-- inventory row -->
		<?php foreach ($inventory as &$inv) { ?>\

		<tr>
			<td style="background-color: #31B404; color: #FFFFFF; font-size: 14;"><?=$inv['desc']?></td>
		</tr>

		<?php
			$tdInv = "<td></td><td style=\"font-size: 14;\">Inv. Inicial</td>";
			$tdCorte = "<td></td><td style=\"font-size: 14;\">Corte</td>";
			$tdRequerido = "<td></td><td style=\"font-size: 14;\">Requerido</td>";
			$tdInvFinal = "<td></td><td style=\"font-size: 14;\">Inv. Final</td>";
		?>
			<!-- dates column -->
			<?php foreach ($dates as $dateKey => $date) {

				$invinicial = (int)$inv["qty"];
				$corteq = 0;
				$requerido = 0;
				$invfinal = 0;
				$color = "#0000FF";
				
				$flowers_required = $required[$date]['flowers'];

				foreach ($flowers_required as $frKey => $fr) {
					
					if($fr["code"] == $inv["code"]) {
						$requerido = (int)$fr["qty"];
					}

				}

				if( isset($corte[ $inv["code"] ]) && isset($corte[ $inv["code"] ][ $date ]) ) {
					$corteq = (int)$corte[ $inv["code"] ][ $date ];
				}

				$invfinal = ($invinicial + $corteq) - $requerido;

				$inv["qty"] = $invfinal;

				if( $invfinal < 0 ) {

					$color = "#FF0000";

				}

				$tdInv .= "<td style=\"font-size: 14;\">".$invinicial."</td>";
				$tdCorte .= "<td style=\"font-size: 14;\">".$corteq."</td>";
				$tdRequerido .= "<td style=\"font-size: 14;\">".$requerido."</td>";
				$tdInvFinal .= "<td style=\"font-size: 14;color: ".$color.";\" >".$invfinal."</td>";

			} ?>

		<tr><?=$tdInv?></tr>
		<tr><?=$tdCorte?></tr>
		<tr><?=$tdRequerido?></tr>
		<tr><?=$tdInvFinal?></tr>

		<?php } ?>
	</tbody>
</table>