@extends('layouts.app')

@section('content')
<!--=============================
=            Content            =
==============================-->
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-cfbc">

				<!--=====================================
				=                 Heading               =
				======================================-->
				<div class="panel-heading">
					<div class="row row-flex-acenter">

						<div class="col-sm-12 text-right">

							<button
								type="button"
								class="btn btn-sm btn-primary"
								data-toggle="modal"
								data-target="#agregarModal">
								<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar
							</button>

						</div>

					</div>
				</div>

				<!--==========================
				=            Body            =
				===========================-->
				<div class="panel-body">
					<div class="table-responsive">
						<table id="table" class="table">
							<thead>
								<tr>
									<th>Flower</th>
									<th>Variety / Color</th>
									<th>Quantity</th>
									<th>Controls</th>
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
<!--=============================
=            Modales            =
==============================-->

@include('app.ordenes.explosion');

<div id="agregarModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Inventario</h4>
			</div>
			<div class="modal-body">
				<p class="text-center">
					<form id="form" name="form">
						<div class="row">
							<div class="col-sm-6 form-group">
								<!-- <div class="form-group"> -->
									<label for="">Flower Type:</label>
									<select style="width: 100%" name="flower_type_id" id="" data-plugin-select2 class="form-control"></select>
								<!-- </div> -->
							</div>
							<div class="col-sm-6 form-group">
								<!-- <div class="form-group"> -->
									<label for="">Variety / Color:</label>
									<select style="width: 100%" name="variety_color_id" id="" data-plugin-select2 class="form-control"></select>
								<!-- </div> -->
							</div>
							<div class="col-sm-4 form-group">
								<!-- <div class="form-group"> -->
									<label for="">Quantity:</label>
									<input type="text" name="quantity" class="form-control" />
								<!-- </div> -->
							</div>
						</div>
					</form>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary btn-sm" form="form">Guardar</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('extrajs')
<script type="text/javascript">

var DTtable,
	$table,
	action = "insert",
	inventory = null;

$table = $("#table");

var openInventory = function(button) {

	action = "update";

	$("#agregarModal").modal("show");

	$("[name='flower_type_id']").attr("disabled", true);
	$("[name='variety_color_id']").attr("disabled", true);

	var data = DTtable.row( $(button).parents('tr') ).data();

	$("[name='flower_type_id']").val(data.flower_type_id).trigger("change");
	$("[name='variety_color_id']").val(data.variety_color_id).trigger("change");
	$("[name='quantity']").val(data.quantity);

	inventory = data;

};

$(document).ready(function() {

	$("[data-plugin-select2]").select2({
		placeholder: "seleccione una opción",
		allowClear: true,
		width: 'resolve'
	});

	$('#agregarModal').on('hidden.bs.modal', function (e) {
		action = "insert";

		$("[name='flower_type_id']").attr("disabled", false);
		$("[name='variety_color_id']").attr("disabled", false);

		$("[name='flower_type_id']").val('').trigger("change");
		$("[name='variety_color_id']").val('').trigger("change");
		$("[name='quantity']").val(0);
	});

	// form submit
	$('#form').submit(function(e) {
		e.preventDefault();

		var data = $(this).serializeObject(), url;

		if(action == "insert") {

			if( !('flower_type_id' in data) || !('variety_color_id' in data) ) {
				swal("Oops...", "Por favor seleccione todos los campos", "error");
				return false
			}

			data.flower_type_id = parseInt(data.flower_type_id);
			data.variety_color_id = parseInt(data.variety_color_id);

			url = 'app/apiv2/inventario';

		}
		else {

			url = 'app/apiv2/inventario/'+inventory.inventory_id;

		}

		var qty = parseInt(data.quantity);
		data.quantity = isNaN(qty) ? 0 : qty;

		simple_loader.fadeIn();

		$.post(url, data, function(response) {

			swal("Inventario", "La flor fue guardada con éxito.", "success");

			// close modal and update table
			$("#agregarModal").modal("hide");
			DTtable.ajax.reload( null, false );

		}, 'json')
		.fail(function(error) {

			swal("Oops...", error.responseJSON || "Por favor intente después", "error");

		})
		.always(function() {

			simple_loader.fadeOut();

		});

	});

	// init DataTable.js
	simple_loader.fadeIn();

	DTtable = $table.DataTable({
		responsive: true,
		stateSave: false,
		bStateSave: false,
		columnDefs: [{
			targets: [-1, 0],
			orderable: false
		}],
		ajax: {
			url: 'app/apiv2/inventario',
			dataSrc: ''
		},
		aoColumns: [
			{ mData: 'flower_name' },
			{ mData: 'variety_color_name' },
			{ mData: 'quantity' },
			{
				sClass: 'text-right',
				mRender: function(d, t, r) {

					var output = "";

					output=
					"<button type='button' onClick='openInventory(this)' class='btn btn-sm btn-primary'>"+
					"<i class='fa fa-edit'></i>"+
					"</button>";

					return output;
				}
			},
		],
		initComplete: function(settings, json) {
			$('div.dataTables_filter input').addClass('form-control');
			$('div.dataTables_filter input').attr("placeholder", "Buscar en inventario..");
			simple_loader.fadeOut();
		},
		oLanguage: {
			sSearch: ""
		}
	});

	// get flower types
	$.get('app/apiv2/inventario/flower_types', function(response) {
		
		for(var i in response) {

			var option = response[i];

			$('[name="flower_type_id"]').append($('<option>', {
				value: parseInt(option.id),
				text: option.name+" ["+option.code+"]"
			}));

			$("[name='flower_type_id']").val('').trigger("change");
		}

	}, 'json');

	$.get('app/apiv2/inventario/variety_colors', function(response) {
		
		for(var i in response) {

			var option = response[i];

			$('[name="variety_color_id"]').append($('<option>', {
				value: parseInt(option.id),
				text: option.name+" ["+option.code+"]"
			}));

			$("[name='variety_color_id']").val('').trigger("change");
		}

	}, 'json');

});
	
</script>
@endsection

