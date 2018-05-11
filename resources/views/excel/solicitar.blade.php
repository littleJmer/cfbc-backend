<html>
<body>
	<table>
		<tr></tr>
		<?php
		echo "<tr>";
			echo "<td></td>";
			foreach ($fechas as $key => $value)
			{
				echo "<td align=center>";
				echo date("d-M-Y", strtotime($value));
				echo "</td>";
			}
		echo "</tr>";
		?>
		<?php
		$subHeader      = [];
        $subHeader[0]   = "Flor";
        $total_pages = count($fechas);

        // $i = 0;
        // while($i < $total_pages)
        // {
        //     $subHeader[] = "Inventario";
        //     $subHeader[] = "Requerido";
        //     $subHeader[] = "Corte";
        //     $subHeader[] = "Desecho";
        //     $subHeader[] = "Inv. Final";
        //     $i++;
        // }

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

            $t = [];
            $f = "";
            $q = "";
            $s = false;

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

            foreach ($t as $key => $value)
            {
                if($key%5 === 0 && $key !== 0)
                {
        
                    if($value < 0) {
                    	$s = true;
                    	$value = $value*(-1);
                    }

                    $q .= "<td>$value</td>";
                }

                if($key===0)
                {
                    $f = "<td>$value</td>";
                }
            }

            if($s)
            {
            	echo "<tr>";
	            echo $f;
	            echo $q;
	            echo "</tr>";
            }
        }
		?>
	</table>
</body>
</html>