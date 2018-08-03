var iTableCounter = 1, productsTables = [];

var numeroDeRecetas = 0, actualReceta = 1, orderShow;

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

var saveColorType = function() {

	var selects = $("select[data-selectsColorType]");
	var data = [];

	simple_loader.fadeIn();

	$.each(selects, function(key, ele) {

		data.push({
			id: ele.name,
			value: ele.value
		});

	});

	$.post('/app/apiv2/ordenes/changeColorType', {data: data}, function(response) {

		simple_loader.fadeOut();
		swal("Orden Editada!", "La orden fue editada con éxito!", "success");

		$("#orderModal").modal("hide");

		// reload the table
		// console.log("ajua");
		Pproduccion.myOrderTable.reload();

	}, 'json');

};

var renderReceta = function() {

	var receta = orderShow.cases[actualReceta-1];

	$("input[name='recetacontrol']").attr("placeholder", `Receta ${actualReceta} de ${numeroDeRecetas}`)

	$.get('/app/api/color-codes', function(response) {

		$("input[name='description']").val(receta.description);
		$("input[name='box_type']").val(receta.box_type);
		$("input[name='stem']").val(receta.stem_per_bunches);
		$("input[name='bunches_per_box']").val(receta.bunches_per_box);
		$("input[name='number_of_cases']").val(receta.number_of_cases);

		$("#tableOrderShow").html("");

		var html = "";

		for(var i in receta.flowers) {

			var flor = receta.flowers[i];

			var options = "";

			for(var j in response) {

				var selected = flor.variety_color == response[j].code ? " selected " : "";

				options += `<option value="${response[j].code}" ${selected}>${response[j].name}</option>`;

			}


			html+=`
			<tr>
				<td>${flor.skunumber}</td>
				<td>${flor.skudesc}</td>
				<td>${flor.flower_text}</td>
				<td>
					<select class="form-control" name="${flor.boxFlower_id}" data-selectsColorType>
						${options}
					</select>
				</td>
				<td>${flor.grade}</td>
				<td>${flor.stem_count}</td>
				<td>${flor.bunch_qty}</td>
			</tr>`;
		}

		$("#tableOrderShow").html(html);


	}, 'json');

};

var nextReceta = function() {
	actualReceta++;

	if(actualReceta > numeroDeRecetas) {
		actualReceta = 1;
	}

	renderReceta();
};

var prevReceta = function() {
	actualReceta--;

	if(actualReceta < 1 ) {
		actualReceta = numeroDeRecetas;
	}

	renderReceta();
};

var showOrder = function(orderid) {

	simple_loader.fadeIn();

	var url = "/app/apiv2/ordenes?id="+orderid;

	$("#orderModal").modal("show");


	$.get(url, function(response, text, status) {

		orderShow = response[0];

		// sun_valley_order
		// client
		// ship_date
		// load_date
		
		$("input[name='sun_valley_order']").val(orderShow.sun_valley_order);
		$("input[name='client']").val(orderShow.client);
		$("input[name='ship_date']").val(orderShow.ship_date);
		$("input[name='load_date']").val(orderShow.load_date);

		numeroDeRecetas = orderShow.cases.length;
		actualReceta = 1;
		renderReceta();


	}, 'json') .always(function() {
		simple_loader.fadeOut();
	});

}

var showOrderBox = function(orderid) {

	simple_loader.fadeIn();

	var url = "/app/apiv2/ordenes?id="+orderid;

	$("#orderModalBox").modal("show");


	$.get(url, function(response, text, status) {

		orderShow = response[0];

		// sun_valley_order
		// client
		// ship_date
		// load_date
		
		$("input[name='sun_valley_order']").val(orderShow.sun_valley_order);
		$("input[name='client']").val(orderShow.client);
		$("input[name='ship_date']").val(orderShow.ship_date);
		$("input[name='load_date']").val(orderShow.load_date);

		$("input[name='load_date_new']").val(orderShow.load_date);

		$("#changeBoxTable").html("");
		var html = "";

		for(var i in orderShow.cases) {

			var caja = orderShow.cases[i];
			var options = "";
			var x = parseInt(caja.number_of_cases);

			console.log(caja);

			for (var i = 0; i <= x; i++) {
				options += `<option value=${i}>${i}</option>`;
			}

			html +=
			`
			<tr>
				<td>${caja.box_type}</td>
				<td>${caja.description}</td>
				<td align=center>${caja.number_of_cases}</td>
				<td>
					<select class="form-control" name="move_cases" data-orderboxid=${caja.orderbox_id}>
						${options}
					</select>
				</td>
			</tr>
			`;

		}

		$("#changeBoxTable").html(html);


	}, 'json') .always(function() {
		simple_loader.fadeOut();
	});

}

function OrderTable (dom, url, cb = null) {
	this.init(dom, url, cb);
}

OrderTable.prototype = {


	init: function(dom, url, cb) {

		this.dom = $(dom);
		this.url = url;
		this.cb = cb;
		this.obj = null;

		this.render();

	},


	render: function() {

		var self = this;

		this.obj = this.dom.DataTable({
			responsive: true,
			stateSave: false,
			bStateSave: false,
			columnDefs: [{
				targets: [-1, 0],
				orderable: false
			}],
			ajax: {
				url: this.url,
				dataSrc: ''
			},
			aoColumns: [
			{
				className: 'details-order',
				bSortable: false,
				"data": null,
				"defaultContent": ''

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
			{ mData: 'ship_date', width: '100px' },
			{ mData: 'load_date', width: '100px' },
			{ mData: 'destination_via' },
			{
				mRender: function(d,t,r) {
					var output=
					`${r.client}<br><small>${r.acc}</small>`;
					return output;
				},
				width: '100px'
			},
			{ mData: 'array_flowers' },
			{ mData: 'total_cases' },
			{ mData: 'array_cases', width: '100px' },
			{ mData: 'total_stem' },
			{
				sClass: 'text-right',
				width: '100px',
				mRender: function(d, t, r)
				{
					var output = "";

					output+=
					"<a href='/ordenesv2/imprimir/"+r.ordenid+"' target='_blank' class='btn btn-sm btn-default'>"+
					"<i class='fa fa-print'></i>"+
					"</a>";

					output+=
					" <a onclick='showOrder("+r.ordenid+");' href='javascript:void(0);' target='_blank' class='btn btn-sm btn-default'>"+
					"<i class='fa fa-edit'></i>"+
					"</a>";

					output+=
					" <a onclick='showOrderBox("+r.ordenid+");' href='javascript:void(0);' target='_blank' class='btn btn-sm btn-default'>"+
					"<i class='fa fa-exchange'></i>"+
					"</a>";

					return output;
				}
			},
			],
			fnRowCallback: function( nRow, aData, iDisplayIndex ) {

				var color = "";

				if ( aData.guess === 1 ) {
					color = "#cfd8dc";
				}

				// if ( aData.status == "2" ) {
				// 	color = "#2196f3";
				// }

				$(nRow).css({
					"background": color,
				});

			},
			initComplete: function(settings, json) {
				$('div.dataTables_filter input').addClass('form-control');
				$('div.dataTables_filter input').attr("placeholder", "Buscar en ordenes..");
				
				if(typeof self.cb === 'function') 
					self.cb();

				self.events();

			},
			oLanguage: {
				sSearch: ""
			}
		});

	},

	events: function() {

		var self = this;

		// Add event listener for opening and closing products
		$('table tbody').off('click', 'td.details-order');
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
				var row = self.obj.row( tr );
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


	},


}

// reload table
OrderTable.prototype.reload = function() {
	this.obj.ajax.reload(null, false);
	// console.log("desde el objeto");
};

// desroy table
OrderTable.prototype.destroy = function() {
	this.obj.destroy();
}