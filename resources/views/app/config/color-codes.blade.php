@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-cfbc">

				<div class="panel-heading">Colors Code</div>

				<div class="panel-body">

					<div class="row" style="margin-bottom: 10px;">
						<div class="col-sm-12 text-right">
							<button
							type="button"
							class="btn btn-sm btn-primary"
							data-toggle="modal"
							data-target="#modal" 
							onclick="create = true;">
								<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add
							</button>
						</div>
					</div>

					<div>
						<table id="table" class="table">
							<thead>
								<tr>
									<th>Code</th>
									<th>Name</th>
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
<div id="modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Colors Code</h4>
			</div>

			<div class="modal-body">
				<form id="form">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Code</label>
								<input type="text" name="code" value="" class="form-control" placeholder="___" required/>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Name</label>
								<input type="text" name="name" value="" class="form-control" placeholder="___" required/>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button 
				 type="button"
				 class="btn btn-danger btn-sm"
				 data-dismiss="modal">Close</button>
				<button
				 type="submit" 
				 form="form" 
				 class="btn btn-sm btn-primary">
					<i class="fa fa-save"></i>&nbsp;Save
				</button>
			</div>

		</div>
	</div>
</div>
@endsection

@section('extrajs')
<script type="text/javascript">

var $table 	= $('#table');
var $modal 	= $('#modal');
var $form 	= $('#form');
var table 	= null;
var id 		= null;
var action 	= "insert";


var show = function(param = null) {
	id = param
	if(param === null)
		action = "insert";
	else
		action = "update";
};

$(document).ready(function() {


	table = $table.DataTable({
		ajax:
		{
			url: '/app/api/color-codes',
			dataSrc: ''
		},
		columnDefs:
        [
            {
                targets: [-1],
                orderable: false
            }
        ],
		aoColumns:
        [
        	{ mData: 'code' },
        	{ mData: 'name' },
        	{
        		sClass: 'text-right',
        		mRender: function(d, t, r) {
        			var button =
        			`<button class="btn btn-sm btn-primary" 
        			onclick="show(${r.id});" data-toggle="modal" data-target="#modal">
						<i class="fa fa-pencil-square-o"></i>
        			</button>`;

        			return button;
        		}
        	},
        ]
	});

	$modal.on('show.bs.modal', function (e) {
		if(id !== null && action === 'update')
		{
			var data = null

			table.rows().every(function() {
				var row = this.data();

				if(row.id == id) {
					data = row;
				}
			});

			$("[name='code']").val(data.code);
			$("[name='name']").val(data.name);
		}
	});

	$modal.on('hide.bs.modal', function (e) {
		action = 'insert';
		id = null;
		$("[name='code']").val('');
		$("[name='name']").val('');
	});

	$form.submit(function(evt) {
		evt.preventDefault();

		var data = $(this).serializeObject();

		if(action === 'insert')
			var url = "/app/api/color-codes";
		else
			var url = "/app/api/color-codes/"+id;

		$.post(url, data, function(response) {

			swal("Color", "The color code has been saved.", "success")
			$modal.modal('hide');
			table.ajax.reload();

		}, 'json');
	});

});

</script>
@endsection