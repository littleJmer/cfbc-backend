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
									<th>Arcata</th>
									<th>Oxnard</th>
									<th>Grade</th>
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
								<label for="">Arcata</label>
								<input type="number" min=0 name="arcata" value="" class="form-control" placeholder="___" required/>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Oxnard</label>
								<input type="text" name="oxnard" value="" class="form-control" placeholder="___" required/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Grade</label>
								<input type="text" name="grade" value="" class="form-control" placeholder="___" required/>
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
			url: '/app/api/grade-key',
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
        	{ mData: 'arcata' },
        	{ mData: 'oxnard' },
        	{ mData: 'grade' },
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

			$("[name='arcata']").val(data.arcata);
			$("[name='oxnard']").val(data.oxnard);
			$("[name='grade']").val(data.grade);
		}
	});

	$modal.on('hide.bs.modal', function (e) {
		action = 'insert';
		id = null;
		$("[name='arcata']").val('');
		$("[name='oxnard']").val('');
		$("[name='grade']").val('');
	});

	$form.submit(function(evt) {
		evt.preventDefault();

		var data = $(this).serializeObject();

		if(action === 'insert')
			var url = "/app/api/grade-key";
		else
			var url = "/app/api/grade-key/"+id;

		$.post(url, data, function(response) {

			swal("Color", "The color code has been saved.", "success")
			$modal.modal('hide');
			table.ajax.reload();

		}, 'json');
	});

});

</script>
@endsection