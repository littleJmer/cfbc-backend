@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-cfbc">

                <div class="panel-heading">Materiales</div>

                <div class="panel-body">

                    <div class="row" style="margin-bottom: 10px;">
                      <div class="col-sm-12 text-right">
                        <button
                         type="button"
                         class="btn btn-sm btn-primary"
                         data-toggle="modal"
                         data-target="#items_modal" 
                         onclick="create = true;">
                          <i class="fa fa-plus" aria-hidden="true"></i>
                          &nbsp;Agregar
                        </button>
                      </div>
                    </div>

                    <div>
                      <table id="items_tabla" class="table">
                        <thead>
                          <tr>
                          	<th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Price per unity</th>
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
<div id="items_modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        		<h4 class="modal-title">Administración de Materiales</h4>
			</div>

			<div class="modal-body">
				<form id="items_form">
					<input type="hidden" name="id" value="0" />
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Name</label>
								<input 
								 type="text" 
								 class="form-control" 
								 name="name" 
								 required />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Description</label>
								<input 
								 type="text" 
								 class="form-control" 
								 name="description" 
								 required />
							</div>
						</div>
					</div>
					<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="">Size</label>
									<input 
									 type="text" 
									 class="form-control" 
									 name="size" 
									 required />
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="">Quantity</label>
									<input 
									 type="number" 
									 class="form-control" 
									 name="quantity" 
									 value=0
									 min=0
									 required />
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="">Price per unity</label>
									<input 
									 type="text" 
									 class="form-control" 
									 name="price_per_unity" 
									 required />
								</div>
							</div>
						</div>
				</form>
			</div>

			<div class="modal-footer">
				<button 
				 type="button"
				 class="btn btn-danger btn-sm"
				 data-dismiss="modal">Cerrar</button>
				<button type="submit" form="items_form" class="btn btn-sm btn-primary">
					<i class="fa fa-save"></i>&nbsp;Guardar
				</button>
			</div>

		</div>
	</div>
</div>
@endsection

@section('extrajs')
<script type="text/javascript">

var $table 	= $("#items_tabla");
var $modal 	= $("#items_modal");
var $form 	= $("#items_form");
var DT 		= null;
var create 	= false;

var limpiarModal = function()
{
	$("[name='id']").val(0);
	$("[name='name']").val('');
	$("[name='description']").val('');
	$("[name='size']").val('');
	$("[name='quantity']").val(0);
	$("[name='price_per_unity']").val(0);
};

$(document).ready(function()
{
	DT = $table.DataTable({
		columnDefs:
        [
            {
                targets: [-1],
                orderable: false
            }
        ],
        ajax:
        {
            url: 'app/api/items',
            dataSrc: 'data'
        },
        aoColumns:
        [
        	{ mData: 'id' },
            { mData: 'name' },
            { mData: 'description' },
            { mData: 'size' },
            { mData: 'quantity' },
            { mData: 'price_per_unity' },
            {
                sClass: 'text-right',
                mRender: function(d, t, r)
                {
                    var output=
                    "<button onclick='create = false;' class='btn btn-sm btn-primary' data-itemid='"+r.id+"' data-toggle='modal' data-target='#items_modal' >"+
                        "<i class='fa fa-pencil-square-o'></i>"+
                    "</button>";

                    return output;
                }
            },
        ]
	});

	$modal.on("show.bs.modal", function(evt)
    {
    	var itemid = evt.relatedTarget.dataset.itemid;

    	limpiarModal();

    	if(create) {
        	return true;
    	}

    	$.get('app/api/items/'+itemid, function(response)
    	{
    		$("[name='id']").val(response.id);
    		$("[name='name']").val(response.name);
			$("[name='description']").val(response.description);
			$("[name='size']").val(response.size);
			$("[name='quantity']").val(response.quantity);
			$("[name='price_per_unity']").val(response.price_per_unity);
    	}, 'json');
   	});

   	$form.submit(function(e)
   	{
   		e.preventDefault();
   		var data = $(this).serializeObject();

   		$.post('app/api/items', data, function(response)
   		{
   			// ugly...
   			DT.ajax.reload( null, false );

   			alert("Material guardado con éxito.");

   			$modal.modal("hide");
   		}, 'json');
   	});
});

</script>
@endsection