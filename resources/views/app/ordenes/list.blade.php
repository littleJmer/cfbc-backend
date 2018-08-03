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

						<div class="col-sm-2">
							<select name="ver_ordenes" id="ver_ordenes" class="form-control">
								<option value="activas" selected>Activas</option>
								<option value="planificadas">Planificadas</option>
								<option value="liberadas">Liberadas</option>
							</select>
						</div>

						<div class="col-sm-10 text-right">

							<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#importar">
								<i class="fa fa-upload" aria-hidden="true"></i>
								&nbsp;Importar
							</button>

							<!-- <button type="button" class="btn btn-sm btn-primary" id="explosion">
								<i class="fa fa-bolt" aria-hidden="true"></i>
								&nbsp;Explosión
							</button> -->

							<!-- <button type="button" class="btn btn-sm btn-success" id="empezar">
								<i class="fa fa-calendar" aria-hidden="true"></i>
								&nbsp;Planificar
							</button> -->

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
									<th></th>
									<!-- <th><input type="checkbox" name="select_all" value="1" id="select_all" /></th> -->
									<th>Sunvalley Order</th>
									<th>Production date</th>
									<th>Ship date</th>
									<th>Load date</th>
									<th>Destination via</th>
									<th>Client</th>
									<th>Flor</th>
									<th># Box</th>
									<th>Box Type</th>
									<th>Steam</th>
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

<div id="importar" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<!-- modal header -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Importacion de Ordenes</h4>
			</div>
			<!-- modal body -->
			<div class="modal-body">
				<p class="text-center">
					<button
					 type="button"
					 class="btn btn-sm btn-default"
					 name="button"
					 onClick="document.getElementById('file').click();"
					 data-file-upload>
						<i class="fa fa-file-excel-o text-primary" aria-hidden="true"></i>
						&nbsp;Da click para seleccionar el arcivo
					</button>
					<button
					 type="submit"
					 class="btn btn-sm btn-primary"
					 name="submit"
					 form="form"
					 data-file-save>
						<i class="fa fa-upload" aria-hidden="true"></i>
						&nbsp;Importar
					</button>
					<span data-file-save>- O -</span>
					<button
					 type="button" 
					 class="btn btn-sm btn-warning" 
					 data-file-save 
					 onClick="document.getElementById('file').click();" >
						<i class="fa fa-retweet" aria-hidden="true"></i>
						&nbsp;Otro .CSV
					</button>
					<form id="form" name="form" class="" enctype="multipart/form-data" method="post">
						<input type="file" id="file" name="file" size="10" class="hidden" />
						<p data-file-info class="">
							<b>Nombre: </b><span data-file-name></span><br>
							<!-- <b>Tipo: </b><span data-file-type></span><br> -->
							<b>Tamaño: </b><span data-file-size></span><br>
						</p>
					</form>
				</p>
			</div>
			<!-- modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('extrajs')
<script type="text/javascript">

var DTtable, $table, iTableCounter = 1, productsTables = [];

$table = $("#table");

var fnFormatProducts = function(table_id, orden_id) {
	var sOut = 
	`<table id="productsTable_${table_id}" class="caseTable">
		<thead>
			<tr>
				<th></th>
				<th>Box type</th>
				<th>Description</th>
				<th>Stems</th>
				<th>Bunches per Box</th>
				<th># of Cases</th>
				<!-- th>
					<label class="switch">
					  <input type="checkbox" data-ac-parent="${orden_id}">
					  <span class="slider round"></span>
					</label>
				</th -->
			</tr>
		</thead>
		<tbody></tbody>
	</table>`;
	return sOut;
}

var fnFormatFlowers = function(table_id) {
	var sOut = 
	`<table id="productsTable_${table_id}" class="flowersTAble">
		<thead>
			<tr>
				<th>Skunumber</th>
				<th>Description</th>
				<th>Type</th>
				<th>Variety / Color</th>
				<th>Grade</th>
				<th>Stem</th>
				<th>Bunch Qty</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>`;
	return sOut;
}

var getSelectedOrders = function() {
	var data = DTtable.$('input[type="checkbox"]:checked');
	var ordersIds = [];

	$.each(data, function(key, item) {
		id = parseInt(item.value);
		ordersIds.push(id);
	});

	return ordersIds;
};

var checkScore = function() {

	var ordersIds = getSelectedOrders();

	var totalBox = 0, totalBunches = 0;

	if(ordersIds.length > 0) {
		$("#manageWindow").css({'z-index': '9999','left': '0%'});


		DTtable.rows().every( function ( rowIdx, tableLoop, rowLoop )
		{
			var row = this.data();

			if( ordersIds.indexOf(row.ordenid) != -1 )
			{
				// console.log(row);
				totalBox+=row.total_cases;
				totalBunches+=row.total_bunches;
			}

		});

		$("input[name='keynumero_de_cajas']").val(totalBox);
		$("input[name='keynumero_de_bonches']").val(totalBunches);

		var empleados = $("input[name='keynumero_de_empleados']").val();
		var horas = $("input[name='keyhoras_de_empleados']").val();

		empleados = parseInt(empleados);
		empleados = (isNaN(empleados)) ? 8 : empleados;

		horas = parseInt(horas);
		horas = (isNaN(horas)) ? 8 : horas;

		var minutos = 1.5;

		var horas_produccion = (minutos * totalBunches) / 60;

		var horas_hombres = (empleados * horas);


		var si_no = (horas_produccion <= horas_hombres) ? 
			"Si puede cumplir con la Producción." : 
			"No puede cumplir con la Producción.";

		$("#keylabel_horas_prod").html(horas_produccion+" Horas de Producción.");
		$("#keylabel_horas_hombre").html(horas_hombres+" Horas Hombres.");
		$("#keylabel_si_no").html(si_no);

	}
	else {
		$("#manageWindow").css({'z-index': '-1','left': '100%'});
	}
};

var minZero = function(e, val) {

	val = parseInt(val);
	val = isNaN(val) ? 8 : val;

	e.value = val;
}

var recipeOpenOrClose = function(ids, open, orden_id) {
	// console.log(ids, open);
	$.post('app/apiv2/ordenes/recetas/toggle', {data: ids, open: open}, function(response) { }, 'json');

	// find to update dataTable
	DTtable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var row = this.data();

		if(row.ordenid === orden_id) {
			var cases = row.cases;

			cases.forEach(function(box, key) {
				var id = box.orderbox_id;

				if( ids.includes(id) ) {
					box.isOpen = open;
				}

			});

			$table.dataTable().fnUpdate(row,rowIdx, undefined, false);
		}
	});

};

$(document).on('click', '[data-ac-parent]', function(evt) {

	var parent 	= $(this).data('acParent');
	var ids 	= [];

	// GUI
	$('input[type="checkbox"][data-ac-child="'+parent+'"]').prop('checked', this.checked);

	//BD
	$('input[type="checkbox"][data-ac-child="'+parent+'"]').each(function(index, element) {
		ids.push($(this).data('ac'));
	});

	recipeOpenOrClose(ids, this.checked, parent);

});

$(document).on('click', '[data-ac-child]', function(evt) {

	var parent 	= $(this).data('acChild');
	var id 		= $(this).data('ac');
	var ids 	= [];

	ids.push(id);

	recipeOpenOrClose(ids, this.checked, parent);

});



$(document).ready(function() {

	// Handle click on "Select all" control
	$('#select_all').on('click', function() {
		// Get all rows with search applied
		var rows = DTtable.rows({ 'search': 'applied' }).nodes();
		// Check/uncheck checkboxes for all rows in the table
		$('input[type="checkbox"]', rows).prop('checked', this.checked);
		//checkScore
		checkScore();
	});

	// show modal to import csv
	$('#importar').on('show.bs.modal', function (e) {
		$("[data-file-upload]").show();
		$("[data-file-save]").hide();
		$("[data-file-info]").hide();
		$("[data-file-name]").html('');
		// $("[data-file-type]").html('');
		$("[data-file-size]").html('');
		$("#form").trigger("reset");
	});

	// when change the file input
	$(':file').on('change', function() {
		$("[data-file-upload]").hide();
		$("[data-file-save]").hide();
		$("[data-file-info]").hide();
		$("[data-file-name]").html('');
		// $("[data-file-type]").html('');
		$("[data-file-size]").html('');

		var fr    = new FileReader();
		var file  = this.files[0];

		fr.onload = function(evt) {
			$("[data-file-save]").show();
			$("[data-file-info]").show();
			$("[data-file-name]").html(file.name);
			// $("[data-file-type]").html(file.type);
			$("[data-file-size]").html(file.size);
		};

		fr.readAsDataURL(this.files[0]);
	});

	// form submit upload csv
	$('#form').submit(function(e) {
		e.preventDefault();

		simple_loader.fadeIn();

		$.ajax({
			url: 'ordenesv2/importar',
			method: 'POST',
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function() {

			},
			complete: function() {

			},
			success: function(data, textStatus, xhr) {

				simple_loader.fadeOut();

				if(data.code === 200) {
					swal(":)", data.message, "success");
					// close the modal
					$('#importar').modal('hide');
				}
				else {
					swal(":|", data.message, "info");
				}

			},
			error: function(error) {
				console.error(error);
				simple_loader.fadeOut();
				swal(":(", "Lo sentimos por el momento no es posible importar el archivo.", "error");
			}
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
			url: 'app/apiv2/ordenes',
			dataSrc: ''
		},
		aoColumns: [
		{
			className: 'details-order',
			bSortable: false,
			"data": null,
			"defaultContent": '',
			"width": "5%"

		},
		// {
		// 	bSortable: false,
		// 	asSorting: false,
		// 	className: 'dt-body-center',
		// 	mRender: function(d, t, r) {
		// 		if ( r.guess === 1 ) {
		// 			return "";
		// 		}
		// 		return '<input type="checkbox" onclick="checkScore();" name="id[]" value="' + r.ordenid + '">';
		// 	}
		// },
		{ mData: 'sun_valley_order' },
		{ mData: 'production_date' },
		{ mData: 'ship_date' },
		{ mData: 'load_date' },
		{ mData: 'destination_via' },
		{
			mRender: function(d,t,r) {
				var output=
				`${r.client}<br><small>${r.acc}</small>`;
				return output;
				return "";
			}
		},
		{ mData: 'array_flowers' },
		{ mData: 'total_cases' },
		{ mData: 'array_cases' },
		{ mData: 'total_stem' },
		{
			sClass: 'text-right',
			mRender: function(d, t, r)
			{
				var output = "";

				var output=
				"<a href='/ordenesv2/imprimir/"+r.ordenid+"' target='_blank' class='btn btn-sm btn-default'>"+
				"<i class='fa fa-print'></i>"+
				"</a>";

				return output;
			}
		},
		],
		fnRowCallback: function( nRow, aData, iDisplayIndex ) {

			var bgColor = "";
			var textColor = "";

			// nueva | no atendida
			if ( aData.status == 1 ) {
				bgColor = "red";
				textColor = "white";
			}

			if ( aData.guess === 1 ) {
				bgColor = "#cfd8dc";
				textColor = "black";
			}

			console.log(aData.status);

			$(nRow).css({
				"background": bgColor,
				"color": textColor
			});

		},
		initComplete: function(settings, json) {
			$('div.dataTables_filter input').addClass('form-control');
			$('div.dataTables_filter input').attr("placeholder", "Buscar en ordenes..");
			simple_loader.fadeOut();
		},
		oLanguage: {
			sSearch: ""
		}
	});

	// Add event listener for opening and closing products
	$('table tbody').on('click', 'td.details-order', function ()
	{
		var tr = $(this).closest('tr');

		var productsTablesIndex = $(tr).data('productsTablesIndex');

		if(typeof productsTablesIndex !== 'undefined')
		{
			var row = productsTables[productsTablesIndex].row(tr);
		}
		else
		{
			var row = DTtable.row( tr );
		}

		if ( row.child.isShown() ) {
			row.child.hide();
			tr.removeClass('shown');
		}
		else {

			var data = row.data();

			if(data.hasOwnProperty("cases"))
			{
				var ordenid = data.ordenid;

				row.child(fnFormatProducts(iTableCounter, ordenid)).show();
				tr.addClass('shown');

				productsTables[iTableCounter] = $("#productsTable_" + iTableCounter).DataTable({
					dom: 't',
					bSort: false,
					aaData: data.cases,
					aoColumns: [
					{
						"className": 'details-order',
						"orderable": false,
						"data": null,
						"defaultContent": '',
						"width": "5%"
					},
					{ mData: 'box_type'},
					{ mData: 'description'},
					{ mData: 'stem_per_bunches'},
					{ mData: 'bunches_per_box'},
					{ mData: 'number_of_cases'},
					// {
					// 	mRender: function(d, o, r) {
					// 		var checked = r.isOpen == 1 ? "checked" : "";

					// 		return `
					// 		<!-- Rounded switch -->
					// 		<label class="switch">
					// 			<input type="checkbox" ${checked} data-ac-child="${ordenid}" data-ac="${r.orderbox_id}">
					// 			<span class="slider round"></span>
					// 		</label>
					// 		`;
					// 	}
					// },
					],
					fnRowCallback: function( nRow, aData, iDisplayIndex )
					{
						$(nRow).data('productsTablesIndex', iTableCounter);
					},
				});

			}

			else if(data.hasOwnProperty("flowers"))
			{
				row.child(fnFormatFlowers(iTableCounter)).show();
				tr.addClass('shown');

				$("#productsTable_" + iTableCounter).DataTable({
					dom: 't',
					bSort: false,
					aaData: data.flowers,
					aoColumns: [
						{ mData: 'skunumber'},
						{ mData: 'skudesc'},
						{ mData: 'flower_text'},
						{ mData: 'variety_color_text'},
						{ mData: 'grade'},
						{ mData: 'stem_count'},
						{ mData: 'bunch_qty'},
					]
				});
			}

			iTableCounter++;
		}
	});

	$("#explosion").click(function() {
		var ordersIds 	= getSelectedOrders();
		var data 		= [];

		window.ORDERS_IDS = ordersIds;

		if(ordersIds.length > 0) {
			DTtable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

				var row = this.data();

				if( ordersIds.indexOf(row.ordenid) != -1 ) {
					data.push(row);
				}

			});
			// show the modal
			Explosion.init(data);
		}
	});

	// $("#empezar").click(function() {
	// 	var ordersIds 	= getSelectedOrders();
	// 	var data 		= [];

	// 	if(ordersIds.length > 0) {
	// 		DTtable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

	// 			var row = this.data();

	// 			if( ordersIds.indexOf(row.ordenid) != -1 ) {
	// 				data.push(row);
	// 			}

	// 		});
	// 		// show the modal
	// 		$("#plannerv2").modal("show");
	// 	}
	// });


});
</script>
@endsection

