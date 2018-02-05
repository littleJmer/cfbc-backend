@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-cfbc">

                <div class="panel-heading">Recetas</div>

                <div class="panel-body">

                    <div class="row" style="margin-bottom: 10px;">
                      <div class="col-sm-12 text-right">
                        <button
                         type="button"
                         class="btn btn-sm btn-primary"
                         data-toggle="modal"
                         data-target="#recetas_modal">
                          <i class="fa fa-plus" aria-hidden="true"></i>
                          &nbsp;Agregar
                        </button>
                      </div>
                    </div>

                    <div>
                      <table id="recetas_tabla" class="table">
                        <thead>
                          <tr>
                          	<th>ID</th>
                            <th>Name</th>
                            <th>Client</th>
                            <th>Cases</th>
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
<!--===========================
=            MODAL            =
============================-->

<div id="recetas_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Administración de Recetas</h4>
      </div>
      <div class="modal-body">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Descripción</a>
          </li>
          <li role="presentation">
            <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Flores</a>
          </li>
          <li role="presentation">
            <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Materiales</a>
          </li>
        </ul>

        <form id="recetas_form">
          <input type="hidden" name="id" value="" />

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
              <div class="row">
                <div class="col-sm-6 form-group">
                  <label for="name">Name:</label>
                  <input type="text" name="name" id="name" class="form-control" required />
                </div>
                <div class="col-sm-6 form-group">
                  <label for="client">Client:</label>
                  <input type="text" name="client" id="client" class="form-control" />
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 form-group">
                  <label for="cases">Cases:</label>
                  <input type="text" name="cases" id="cases" class="form-control" />
                </div>
                <div class="col-sm-6 form-group">
                  <label for="sku">Sku:</label>
                  <input type="text" name="sku" id="sku" class="form-control" />
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
              <div class="row">
                <div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Flower</th>
                        <th>Variedad</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody data-receta-items></tbody>
                  </table>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="messages">
              
            </div>
          </div>
          
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary btn-sm" form="recetas_form">
          <i class="fa fa-save"></i>&nbsp;Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<!--====  End of MODAL  ====-->
@endsection

@section('extrajs')
<script type="text/javascript">

var $formulario = $("#recetas_form");
var $tabla 		= $("#recetas_tabla");
var $modal 		= $("#recetas_modal");
var DTobj;

var limpiarModal = function()
{
	console.log("Modal limpio.");
};

$(document).ready(function()
{
	/**
	 *
	 * Abrir modal
	 *
	 */
	$modal.on("show.bs.modal", function(evt)
    {
   		var recetaid = evt.relatedTarget.dataset.recetaid;

   		limpiarModal();

   		$.get('app/api/recetas/'+recetaid, function(json)
      	{
      		if(!json.success)
      		{
      			return false;
      		}

      		$("[name='id']").val(json.data.id);
      		$("[name='name']").val(json.data.name);
      		$("[name='client']").val(json.data.client);
      		$("[name='cases']").val(json.data.cases);
      		$("[name='sku']").val(json.data.sku);

      		$("[data-receta-items]").html("");
      		var html = "";
      		$.each(json.data.items, function(key, item)
      		{
      			html+=
      			`<tr>
      				<td>${key+1}</td>
      				<td>${item.name}</td>
      				<td>${item.flower}</td>
      				<td>${item.variedad}</td>
      				<td>${item.color}</td>
      				<td>
      					<input type="number" name="" value="${item.quantity}" />
      				</td>
      				<td align="center">
						<button class="btn btn-danger btn-sm">
							<i class="fa fa-trash-o"></i>
						</button>
      				</td>
      			</tr>`;
      		});
      		$("[data-receta-items]").html(html);

      	}, "json");
    });

	/**
	 *
	 * Construccion de la Tabla
	 *
	 */
	DTobj = $tabla.DataTable({
		columnDefs:
        [
            {
                targets: [-1],
                orderable: false
            }
        ],
        ajax:
        {
            url: 'app/api/recetas',
            dataSrc: 'data'
        },
        aoColumns:
        [
        	{ mData: 'id' },
            { mData: 'name' },
            { mData: 'client' },
            { mData: 'cases' },
            {
                sClass: 'text-right',
                mRender: function(d, t, r)
                {
                    var output=
                    " <button class='btn btn-sm btn-primary' data-recetaid='"+r.id+"' data-toggle='modal' data-target='#recetas_modal' >"+
                        "<i class='fa fa-pencil-square-o'></i>"+
                    "</button>";

                    return output;
                }
            },
        ]
	});

	/**
	 *
	 * Submit formulario
	 *
	 * @
	 * @ return
	 */
	 $formulario.submit(function(evt)
	 {
	 	evt.preventDefault();

	 	var data = $(this).serializeObject();

	 	$.post('app/api/recetas/'+data.id, data, function(json)
	 	{
	 		if(!json.success)
	 		{
	 			return false;
	 		}

	 		alert("La receta fue editada con éxito.");
	 		DTobj.ajax.reload(null, false);

	 	}, 'json');
	 });
	
});

</script>
@endsection