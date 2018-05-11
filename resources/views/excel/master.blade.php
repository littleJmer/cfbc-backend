<html>
<body>
	<table>
		<tr></tr>
		<?php
		echo "<tr>";
			echo "<td></td>";
			foreach ($fechas as $key => $value)
			{
				echo "<td colspan=5 align=center>";
				echo date("d-M-Y", strtotime($value));
				echo "</td>";
			}
		echo "</tr>";
		?>
		<?php
		$subHeader      = [];
        $subHeader[0]   = "Flor";
        $total_pages = count($fechas);

        $i = 0;
        while($i < $total_pages)
        {
            $subHeader[] = "Inventario";
            $subHeader[] = "Requerido";
            $subHeader[] = "Corte";
            $subHeader[] = "Desecho";
            $subHeader[] = "Inv. Final";
            $i++;
        }

        echo "<tr>";
        foreach ($subHeader as $key => $value)
        {
        	echo "<td>$value</td>";
        }
        echo "</tr>";
		?>
		<?php
		foreach ($inventario as $flor)
        {
        	echo "<tr>";

            $t = [];

            // $t[0] = $flor['location'].$flor['flower_type'].$flor['variety_color'];
            $t[0] = $flor['skudesc'];

            $i      = 1;
            $next   = true;
            while($next)
            {
               if( isset($flor[$i]) )
               {
                    $t[$i] = $flor[$i];
                    $i++;
               }
               else
                $next = false;
            }

            // $sheet->row($row, $t);
            // $row++;

            foreach ($t as $key => $value)
            {
                if($key%5 === 0 && $value < 0)
                {
                    echo "<td style='background-color: #ff0000;color: #ffffff;'>$value</td>";
                }
                else
                {
                    echo "<td>$value</td>";
                }
            }

            echo "</tr>";
        }
		?>
	</table>
</body>
</html>