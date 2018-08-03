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

						<div class="col-sm-4">
							<div class="input-group" id="event_period">
								<div class="input-group-addon">del</div>
								<input type="text" id="start_date" class="actual_range form-control">
								<div class="input-group-addon">al</div>
								<input type="text" id="end_date" class="actual_range form-control">
							</div>
						</div>

						<div class="col-sm-5" style="padding: 0">
							<button type="button" class="btn btn-sm btn-primary" id="calcular">
								<i class="fa fa-table" aria-hidden="true"></i>
								&nbsp;Calcular Master
							</button>
							<button type="button" class="btn btn-sm btn-primary" id="descargar" disabled>
								<i class="fa fa-file-excel-o" aria-hidden="true"></i>
								&nbsp;Descargar Master
							</button>
							<button type="button" class="btn btn-sm btn-primary" id="pproduccion" disabled>
								<i class="fa fa-bolt" aria-hidden="true"></i>
								&nbsp;Plan de Producción
							</button>
						</div>

						<div class="col-sm-5 text-right">

							<!-- <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#importar">
								<i class="fa fa-upload" aria-hidden="true"></i>
								&nbsp;Importar
							</button> -->

							<!-- <button type="button" class="btn btn-sm btn-primary" id="explosion">
								<i class="fa fa-download" aria-hidden="true"></i>
								&nbsp;Descargar
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
				<div class="panel-body" id="mainContent">
					<!--  -->
					<div style="overflow-x: scroll;width: 100%;">
						<table id="tableProduccion" class="table table-bordered table-hover"></table>
					</div>
					<!--  -->
					<div class="table-responsive" id="wrappTable">
						<div class="row">
							<div class="col-md-12 text-center">
								<h4>Ordenes del día <span id="datespan"></span></h4>
							</div>
						</div>
						<table id="table" class="table">
							<thead>
								<tr>
									<th></th>
									<!-- <th>
										<input type="checkbox" name="select_all" value="1" id="select_all" />
									</th> -->
									<th>Sunvalley Order</th>
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

<div id="orderModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Split de Orden</h4>
			</div>
			<div class="modal-body">
				<div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Sun Valley Order</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="sun_valley_order"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Client</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="client"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Ship Date</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="ship_date"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Load Date</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="load_date"
									placeholder="__" 
									readonly>
							</div>
						</div>
					</div>
					<!--  -->
					<div class="row">
						<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" onclick="prevReceta();">
											<span 
												class="glyphicon glyphicon-circle-arrow-left" 
												aria-hidden="true">
											</span>
										</button>
									</span>
									<input
										type="text"
										class="form-control"
										placeholder="Receta 1 de 1"
										readonly=true
										name="recetacontrol"
									/>
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" onclick="nextReceta();">
											<span 
												class="glyphicon glyphicon-circle-arrow-right" 
												aria-hidden="true">
											</span>
										</button>
									</span>
								</div>
						</div>
					</div>
					<!--  -->
					<div class="row">
						<br />
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Description</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="description"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Box type</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="box_type"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">Stem</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="stem"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="">B. per Box</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="bunches_per_box"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for=""># of Cases</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="number_of_cases"
									placeholder="__" 
									readonly>
							</div>
						</div>
					</div>
					<!--  -->
					<div class="row">
						<div class="col-sm-12">
							<div>
								<table class="table">
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
									<tbody id="tableOrderShow"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="saveColorType();">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>

<div id="orderModalBox" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Split de Orden por Caja</h4>
			</div>
			<div class="modal-body">
				<div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Sun Valley Order</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="sun_valley_order"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Client</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="client"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Ship Date</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="ship_date"
									placeholder="__" 
									readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Load Date</label>
								<input
									type="text"
									class="form-control"
									id=""
									name="load_date"
									placeholder="__" 
									readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="">Cambiar Load Date</label>
							<input
								type="date"
								class="form-control"
								id=""
								name="load_date_new"
								placeholder="__">
						</div>
						<div class="col-md-1">
							<label for="">&nbsp;</label>
							<button class="btn btn-sm btn-primary">Guardar</button>
						</div>
					</div>
					<!--  -->
					<!--  -->
					<div class="row">
						<div class="col-md-12">
							<div>
								<table class="table">
									<thead>
										<tr>
											<th>Box type</th>
											<th>Description</th>
											<th># of Cases</th>
											<th>Move # of Cases</th>
										</tr>
									</thead>
									<tbody id="changeBoxTable"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<!-- <button type="button" class="btn btn-primary" onclick="saveChangeBox();">Guardar Cambios</button> -->
			</div>
		</div>
	</div>
</div>

<div id="planProduccionModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
	
			<div class="modal-header">
				Plan de Producción
			</div>

			<div class="modal-body">
				<form>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Nombre de Producción</label>
								<input type="text" name="nombre" class="form-control" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Número de Empleado</label>
								<input type="text" name="nu_empleados" class="form-control" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Horas por Empleado</label>
								<input type="text" name="ho_empleados" class="form-control" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Número de Cajas</label>
								<input type="text" name="nu_cajas" class="form-control" readonly disabled />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Número de Bonches</label>
								<input type="text" name="nu_bonches" class="form-control" readonly disabled />
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" 
				onClick="Pproduccion._planProduccionPrint();">
					<i class="fa fa-print"></i>&nbsp;Imprimir
				</button>
			</div>

		</div>
	</div>
</div>

@endsection

@section('extrajs')
<script type="text/javascript">

var start_date, end_date;

var getToday = function() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; // January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
	    dd = '0'+dd
	} 

	if(mm<10) {
	    mm = '0'+mm
	} 

	today = mm + '/' + dd + '/' + yyyy;
	return today;
};

var btnCalcular = $("#calcular");
var btnDescargar = $("#descargar");
var btnProduccion = $("#pproduccion");

var wrappTable = $("#wrappTable");
// var orderTable = $("#table");

var productionTable = $("#tableProduccion");

var Pproduccion = {

	date: null,
	dom: $("#tableProduccion"),

	init: function(dates, required, inventory, corte = {}) {

		//
		this.dates = dates;
		this.required = required;
		this.inventory = inventory;
		this.cloneInventory = null;
		this.corte = corte;

		//
		wrappTable.hide();
		if(this.myOrderTable)
			this.myOrderTable.destroy();

		//
		if( Object.keys(this.required).length > 0 ) {
			btnDescargar.prop('disabled', false);
			btnProduccion.prop('disabled', false);
		}
		else {
			btnDescargar.prop('disabled', true);
			btnProduccion.prop('disabled', true);
		}

		this.render();

	},

	render: function() {

		var html = "";

		html += this.renderHeader();
		html += this.renderBody();

		this.dom.html(html);

	},

	renderHeader: function() {

		var colspan = this.dates.length;

		var html=
		`
		<thead>
			<tr>
				<td style="width:200px;"></td>
				<td colspan=${colspan} align=center>Día</td>
			</tr>
			<tr>
				<td>Flor</td>`;

		for(var i in this.dates) {

			var d = this.dates[i];

			var urlPrint = `/ordenesv2/imprimir/0?date=${d}`;

			html +=
			`<td align=center width=250px>
				<a onClick="Pproduccion._showOrdenByDate('${d}');" title="ver ordenes" href="javascript:void(0);" style="color: blue;" target="_self">
					${d}
				</a><br/>
				<a href="${urlPrint}" target="_blank" title="Imprimir Ordenes">
					<i class="fa fa-print" aria-hidden="true"></i>
				</a>
			</td>`;

		}

		html+=
		`
			</tr>
		</thead>
		`;

		return html;
	},

	renderBody: function() {

		var html = `<body>`;

		var columnIndex = 1, rowIndex = 1;

		this.cloneInventory = $.extend(true, {}, this.inventory);

		for(var i in this.cloneInventory) {

			var inv = this.cloneInventory[i];

			// ???
			i = i+"";

			html+=
			`<tr>
				<td rowspan=5 valign="center">
					<label style="width: 200px;">${inv.desc}</label>
				</td>
			</tr>`;

			// inv.code
			// inv.qty
			// inv.desc
			// inv.variety_color

			var tdInv = "";
			var tdCorte = "";
			var tdRequerido = "";
			var tdInvFinal = "";
			var firstDate = true;

			for (var j in this.dates) {

				var date = this.dates[j];
				var flowers_required = this.required[date].flowers;

				var invinicial = inv.qty;
				var corte = 0;
				var requerido = 0;
				var invfinal = 0;
				var className = "label-primary";
				
				// obtener la cantidad requerida de la flor
				for(var k in flowers_required) {

					var flower = flowers_required[k];

					if(flower.code === inv.code) {
						requerido = flower.qty;

						// if(typeof flower.corte == 'undefined') {
						// 	flower.corte = 0;
						// } else {
						// 	corte = flower.corte;
						// }

						break;
					}


				} 

				// obtener la cantidad de corte de esta flor en este dia..
				if(this.corte[inv.code] && this.corte[inv.code][date]) {
					// console.log("corte: ", inv.code, date, 1);
					corte = this.corte[inv.code][date];
				}
				else {
					// console.log("corte: ", inv.code, date, 0);
					corte = 0;
				}
				

				// update inv.qty
				invfinal = inv.qty = (invinicial + corte) - requerido;

				if(invfinal < 0) 
					className = "label-danger";


				var readonly = "readonly";
				if(firstDate) {

					readonly = "";

					firstDate = false;
				}

				tdInv+=
				`<td>
					Inv. Inicial:
					<input
						type="text"
						data-yx="${rowIndex},${columnIndex}"
						data-name="invinicial"
						value=${invinicial}
						placeholder="0"
						class="form-control"
						onChange="Pproduccion._changeInventoryQuantity(this, '${i}')"
						${readonly}
						style="width: 125px;"
					/>
				</td>`;

				tdCorte+=
				`<td>
					Corte:
					<input
						type="text"
						data-yx="${rowIndex},${columnIndex}"
						data-name="corte"
						placeholder="0"
						value=${corte}
						onChange="Pproduccion._changeCorteQuantity(this, '${i}', '${date}')"
						class="form-control"
					/>
				</td>`;

				tdRequerido+=
				`<td>
					Requerido:
					<input
						type="number"
						data-yx="${rowIndex},${columnIndex}" data-name="requerido"
						value=${requerido}
						class="form-control"
						readonly
					/>
				</td>`;

				tdInvFinal+=
				`<td>
					Inv. Final: <br />
					<span
						data-yx="${rowIndex},${columnIndex}"
						data-name="invfinal"
						class="label ${className}">${invfinal}</span>
				</td>`;

				columnIndex++;

			} // end dates

			html+=
			`
			<tr>${tdInv}</tr>
			<tr>${tdCorte}</tr>
			<tr>${tdRequerido}</tr>
			<tr>${tdInvFinal}</tr>
			`;

			rowIndex++;
			columnIndex = 1;

		} // end inventory

		html+=`</body>`;

		return html;
	},

	_changeInventoryQuantity: function(input, index) {

		var value = parseInt(input.value);

		value = isNaN(value) ? 0 : value;

		input.value = value;

		this.inventory[index].qty = value;

		this.render();

	},

	_changeCorteQuantity: function(input, index, date) {

		var value = parseInt(input.value);

		value = isNaN(value) ? 0 : value;

		input.value = value;

		if(!this.corte[index]) {

			this.corte[index] = {[date]: 0}

		}
		else if(!this.corte[index][date]) {

			this.corte[index] = {...this.corte[index], [date]: 0}

		}
		
		this.corte[index][date] = value;

		this.render();

	},

	_ddcsv: function() {

		simple_loader.fadeIn();

		var url = '/plan-de-produccion/descarga/csv';

		var params = {
			inventory: this.inventory,
			corte: this.corte,
			startDate: start_date,
			endDate: end_date,
		};

		// console.log(params);

		$.post(url, params, function(response, status, request) {

			// var contentDisposition = request.getResponseHeader('Content-Disposition');
			// var contentType = request.getResponseHeader('Content-Type');
			// var filename = "";

			// var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
			// var matches = filenameRegex.exec(contentDisposition);

			// if (matches != null && matches[1]) 
			// 	filename = matches[1].replace(/['"]/g, '');

			// console.log(filename);
			// console.log(contentType);
			// console.log(contentDisposition);

			var a = document.createElement("a");
			a.href = response.file; 
			a.download = response.name;
			document.body.appendChild(a);
			a.click();
			a.remove();

		}).always(function() { simple_loader.fadeOut(); });

	},

	_showOrdenByDate: function(date) {
		
		btnDescargar.prop('disabled', true);
		btnProduccion.prop('disabled', true);

		// assign date
		this.date = date;
		$("#datespan").html(date);

		// show loader
		simple_loader.fadeIn();

		// destroy matriz
		productionTable.html("");

		// init orderTable
		wrappTable.show();
		this.myOrderTable = new OrderTable("#table", "app/apiv2/ordenes?date="+this.date, function() {
			simple_loader.fadeOut();
		});

	},

	_planProduccion() {

		$("#planProduccionModal").modal("show");

		var total_stem = 0;
		var total_box = 0;
		var total_bunch = 0;
		var ordersid = [];

		for(var i in this.required) {

			var day =  this.required[i];

			total_stem += day.stem;
			total_box += day.box;
			total_bunch += day.bunch;

			ordersid = ordersid.concat(day.ordersid);

		}

		console.log('total stem', total_stem);
		console.log('total box', total_box);
		console.log('total bunch', total_bunch);
		console.log('ordersid', ordersid);

		$("input[name='nu_cajas']").val(total_box);
		$("input[name='nu_bonches']").val(total_bunch);

	},

	_planProduccionPrint() {

		simple_loader.fadeIn();

		var ordersid = [];

		for(var i in this.required) {

			var day =  this.required[i];

			ordersid = ordersid.concat(day.ordersid);

		}

		var url = '/plan-de-produccion/descarga/plan';

		$.ajax({
			url: url,
			type: 'POST',
			data: JSON.stringify(ordersid),
			headers: {
				"Content-Type": "application/json; charset=UTF-8"
			},
			// xhrFields is what did the trick to read the blob to pdf
			xhrFields: {
				responseType: 'blob'
			},
			success: function (response, status, xhr) {

				var type = xhr.getResponseHeader("Content-Type");

				var url = window.URL.createObjectURL(new Blob([response], {type: type}));
				var link = document.createElement('a');

				link.href = url;

				link.setAttribute('download', 'oioipp.pdf');
				document.body.appendChild(link);
				link.click();

				simple_loader.fadeOut();
			},

		});

		// $.post(url, params, function(response, status, request) {


		// 	var type = request.getResponseHeader("Content-Type");

		// 	var url = window.URL.createObjectURL(new Blob([response], {type: type}));
		// 	var link = document.createElement('a');

		// 	link.href = url;

		// 	link.setAttribute('download', 'test.pdf');
		// 	document.body.appendChild(link);
		// 	link.click();

		// }).always(function() { simple_loader.fadeOut(); });

	}

};

$(document).ready(function() {

	wrappTable.hide();


	$('#event_period').datepicker({
		inputs: $('.actual_range'),
		todayHighlight: true,
		// daysOfWeekDisabled: [0,6],
		autoclose: true,
	});

	// master
	btnDescargar.click(function() { Pproduccion._ddcsv(); });

	// plan de produccion
	btnProduccion.click(function() { Pproduccion._planProduccion(); });

	btnCalcular.click(function() {

		//
		btnDescargar.prop('disabled', true);
		btnProduccion.prop('disabled', true);

		// get values
		start_date = $("#start_date").val();
		end_date = $("#end_date").val();

		if( start_date == "" || end_date == "") {
			swal("Fechas de producción", "Por favor defina las fechas para la producción.", "error");
			return false;
		}

		// today
		var today = getToday();

		// data object
		date1 = new Date(today);
		date2 = new Date(start_date);
		date3 = new Date(end_date);

		// compare
		// if(date2 < date1) {
		// 	swal("Fechas de producción", "Fecha de inicio fuera de rango.", "error");
		// 	return false;
		// }

		// if(date3 < date2) {
		// 	swal("Fechas de producción", "Fecha final fuera de rango.", "error");
		// 	return false;
		// }

		simple_loader.fadeIn();
		// do request
		$.get(`app/apiv2/produccion?start_date=${start_date}&end_date=${end_date}`, function(response) {

			Pproduccion.init(
				response.dates,
				response.required,
				response.inventory,
				{},
			);

			simple_loader.fadeOut();

		}, 'json');

	});

});

</script>
@endsection

