@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-cfbc">

                <div class="panel-heading">Clientes</div>

                <div class="panel-body">

                    <div class="row" style="margin-bottom: 10px;">
                      <div class="col-sm-12 text-right">
                        <button
                         type="button"
                         class="btn btn-sm btn-primary"
                         data-toggle="modal"
                         data-target="#customers_modal" 
                         onclick="create = true;">
                          <i class="fa fa-plus" aria-hidden="true"></i>
                          &nbsp;Agregar
                        </button>
                      </div>
                    </div>

                    <div>
                      <table id="customers_tabla" class="table">
                        <thead>
                          <tr>
                          	<th>ID</th>
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
<div id="customers_modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        		<h4 class="modal-title">Administración de Clientes</h4>
			</div>

			<div class="modal-body">
				<form id="customers_form">
					<input type="hidden" name="id" value="0" />
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Name</label>
								<input 
								 type="text" 
								 class="form-control" 
								 name="name" 
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
				<button type="submit" form="customers_form" class="btn btn-sm btn-primary">
					<i class="fa fa-save"></i>&nbsp;Guardar
				</button>
			</div>

		</div>
	</div>
</div>
@endsection

@section('extrajs')
<script type="text/javascript">

var $table 	= $("#customers_tabla");
var $modal 	= $("#customers_modal");
var $form 	= $("#customers_form");
var DT 		= null;
var create 	= false;

var limpiarModal = function()
{
	$("[name='id']").val(0);
	$("[name='name']").val('');
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
            url: 'app/api/customers',
            dataSrc: 'data'
        },
        aoColumns:
        [
        	{ mData: 'id' },
            { mData: 'name' },
            {
                sClass: 'text-right',
                mRender: function(d, t, r)
                {
                    var output=
                    "<button onclick='create = false;' class='btn btn-sm btn-primary' data-customerid='"+r.id+"' data-toggle='modal' data-target='#customers_modal' >"+
                        "<i class='fa fa-pencil-square-o'></i>"+
                    "</button>";

                    return output;
                }
            },
        ]
	});

	$modal.on("show.bs.modal", function(evt)
    {
    	var customerid = evt.relatedTarget.dataset.customerid;

    	limpiarModal();

    	if(create) {
        	return true;
    	}

    	$.get('app/api/customers/'+customerid, function(response)
    	{
    		$("[name='id']").val(response.id);
    		$("[name='name']").val(response.name);
    	}, 'json');
   	});

   	$form.submit(function(e)
   	{
   		e.preventDefault();
   		var data = $(this).serializeObject();

   		$.post('app/api/customers', data, function(response)
   		{
   			// ugly...
   			DT.ajax.reload( null, false );

   			alert("Cliente guardado con éxito.");

   			$modal.modal("hide");
   		}, 'json');
   	});
});

</script>
@endsection