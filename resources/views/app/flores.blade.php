@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-cfbc">

                <div class="panel-heading">Flores</div>

                <div class="panel-body">

                    <div class="row" style="margin-bottom: 10px;">
                      <div class="col-sm-12 text-right">
                        <button
                         type="button"
                         class="btn btn-sm btn-primary"
                         data-toggle="modal"
                         data-target="#flores_modal" 
                         onclick="create = true;">
                          <i class="fa fa-plus" aria-hidden="true"></i>
                          &nbsp;Agregar
                        </button>
                      </div>
                    </div>

                    <div>
                      <table id="flores_tabla" class="table">
                        <thead>
                          <tr>
                          	<th>Id</th>
                            <th>Name</th>
                            <th>Name campo</th>
                            <th>Especie</th>
                            <th>Variedad</th>
                            <th>Color</th>
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
<div id="flores_modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        		<h4 class="modal-title">Administración de Flores</h4>
			</div>

			<div class="modal-body">
				<form id="flores_form">
					<input type="hidden" name="id" value="0" />
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Code</label>
								<input 
                               type="text" 
                               class="form-control" 
                               name="name_posco" 
                               required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Name campo</label>
                                <input 
                                type="text" 
                                class="form-control" 
                                name="name_campo" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Especie</label>
                                <select name="especie" id="" class="form-control">
                                    <option value="Australian pine">Australian pine</option>
                                    <option value="Crisanthemos">Crisanthemos</option>
                                    <option value="Dianthus">Dianthus</option>
                                    <option value="GREEN BABY">GREEN BABY</option>
                                    <option value="Helycrisum">Helycrisum</option>
                                    <option value="Limonium">Limonium</option>
                                    <option value="Matricaria">Matricaria</option>
                                    <option value="MATSUMOTOS">MATSUMOTOS</option>
                                    <option value="Mollucela">Mollucela</option>
                                    <option value="Romero">Romero</option>
                                    <option value="SNAPDRAGON">SNAPDRAGON</option>
                                    <option value="Snapdragon 5st Pk.15">Snapdragon 5st Pk.15</option>
                                    <option value="Snapdragon 5st Pk.8">Snapdragon 5st Pk.8</option>
                                    <option value="Statice">Statice</option>
                                    <option value="Stock">Stock</option>
                                    <option value="STOCK ASSORTED">STOCK ASSORTED</option>
                                    <option value="Sunflower">Sunflower</option>
                                    <option value="Veronica 8st 15 pk">Veronica 8st 15 pk</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Variedad</label>
                                <input type="text" name="variedad" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Color</label>
                                <select name="color" id="" class="form-control">
                                    <option value="">No Aplica</option>
                                    <option value="apple_blossom">apple blossom</option>
                                    <option value="bicolor">bicolor</option>
                                    <option value="black_eyes">black eyes</option>
                                    <option value="burgandy">burgandy</option>
                                    <option value="Green">Green</option>
                                    <option value="Hot_pink">Hot pink</option>
                                    <option value="lavander">lavander</option>
                                    <option value="light_pink">light pink</option>
                                    <option value="orange">orange</option>
                                    <option value="peach">peach</option>
                                    <option value="pink">pink</option>
                                    <option value="plum_blossom">plum blossom</option>
                                    <option value="purple">purple</option>
                                    <option value="red">red</option>
                                    <option value="white">white</option>
                                    <option value="white_daysi">white daysi</option>
                                    <option value="yellow">yellow</option>
                                </select>
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
				<button type="submit" form="flores_form" class="btn btn-sm btn-primary">
					<i class="fa fa-save"></i>&nbsp;Guardar
				</button>
			</div>

		</div>
	</div>
</div>
@endsection

@section('extrajs')
<script type="text/javascript">

var $table 	= $("#flores_tabla");
var $modal 	= $("#flores_modal");
var $form 	= $("#flores_form");
var DT 		= null;
var create 	= false;

var limpiarModal = function()
{
	$("[name='id']").val(0);
    $("[name='name_posco']").val('');
    $("[name='name_campo']").val('');
    $("[name='variedad']").val('');
    $("[name='color']").val('');
	//$("[name='especie']").val('');

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
            url: 'app/api/flores',
            dataSrc: 'data'
        },
        aoColumns:
        [
        	{ mData: 'id' },
          { mData: 'name_posco' },
          { mData: 'name_campo' },
          { mData: 'especie' },
          { mData: 'variedad' },
          { mData: 'color' },
          {
              sClass: 'text-right',
              mRender: function(d, t, r)
              {
                  var output=
                  "<button onclick='create = false;' class='btn btn-sm btn-primary' data-id='"+r.id+"' data-toggle='modal' data-target='#flores_modal' >"+
                      "<i class='fa fa-pencil-square-o'></i>"+
                  "</button>";

                  return output;
              }
          },
        ]
	});

	$modal.on("show.bs.modal", function(evt)
    {
    	var id = evt.relatedTarget.dataset.id;

    	limpiarModal();

    	if(create) {
        	return true;
    	}

    	$.get('app/api/flores/'+id, function(response)
    	{
    		$("[name='id']").val(response.id);
            $("[name='name_posco']").val(response.name_posco);
            $("[name='name_campo']").val(response.name_campo);
            $("[name='especie']").val(response.especie);
            $("[name='variedad']").val(response.variedad);
    		$("[name='color']").val(response.color.replace(" ", "_"));
    	}, 'json');
   	});

   	$form.submit(function(e)
   	{
   		e.preventDefault();
   		var data = $(this).serializeObject();

   		$.post('app/api/flores', data, function(response)
   		{
   			// ugly...
   			DT.ajax.reload( null, false );

   			alert("Flor guardado con éxito.");

   			$modal.modal("hide");
   		}, 'json');
   	});
});

</script>
@endsection