@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			
			<div class="panel panel-default panel-cfbc">


				<div class="panel-heading">
					<div class="row">
						<div class="col-sm-4">Planeaciones</div>
					</div>
				</div>

				<div class="panel-body">
					<div>
						<table id="planes_table" class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Nombre</th>
                                    <th># Bonches</th>
                                    <th># Cajas</th>
									<th># de Empleados</th>
                                    <th>Hora X empleado</th>
									<th>Estatus</th>
									<th>Controles</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection

@section('modales')
@include('app.ordenes.explosion');
@endsection

@section('extrajs')
<script type="text/javascript">

var $table = $("#planes_table"),
	DTobj;

$(document).ready(function()
{
	DTobj = $table.DataTable({
        responsive: true,
        stateSave: false,
        columnDefs:
        [
            {
                targets: [-1, 0],
                orderable: false
            }
        ],
        ajax:
        {
            url: 'app/apiv2/planes',
            dataSrc: ''
        },
        aoColumns:
        [
            { mData: 'id' },
            { mData: 'name' },
            { mData: 'num_bonches' },
            { mData: 'num_cajas' },
            { mData: 'num_empleados' },
            { mData: 'horas_empleados' },
            { mData: 'statusName' },
            {
                sClass: 'text-right',
                mRender: function(d, t, r)
                {
                    var output=
                    "<button class='btn btn-sm btn-primary' onclick=\"Explosion.open("+r.id+");\">"+
                        "<i class='fa fa-edit'></i>"+
                    "</button>";

                    return output;
                }
            },
        ]
    });
});

</script>
@endsection