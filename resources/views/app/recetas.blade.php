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
                         data-target="#recetas_modal" 
                         onclick="create = true;">
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
                            <th>Type</th>
                            <th>Inmutable</th>
                            <th>Case</th>
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
                  <!-- <input type="text" name="client" id="client" class="form-control" /> -->
                  <select name="customer_id" id="customer_id" class="form-control">
                    <option value="0">SELECCIONE UN CLIENTE</option>
                    <?php
                      foreach ($customers as $key => $value)
                      {
                        echo "<option value='".$value->id."'>".$value->name."</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 form-group">
                  <label for="cases">Case:</label>
                  <!-- <input type="text" name="cases" id="cases" class="form-control" /> -->
                  <select name="case_id" id="case_id" class="form-control">
                    <option value="0">SELECCIONE CASE</option>
                    <?php
                      foreach ($cases as $key => $value)
                      {
                        echo "<option value='".$value->id."'>".$value->name."</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-sm-6 form-group">
                  <label for="sku">Sku:</label>
                  <input type="text" name="sku" id="sku" class="form-control" />
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control">
                      <option value="1" selected>Buquet</option>
                      <option value="2">Consumer Solido</option>
                      <option value="3">Consumer Surtido</option>
                      <option value="4">Bulk</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Inmutable</label>
                      <select name="inmutable" class="form-control">
                        <option value=0>No</option>
                        <option value=1>Si</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <!-- flores -->
            <div role="tabpanel" class="tab-pane" id="profile">
              <div class="row">
                <div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Especie</th>
                        <th>Variedad</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody data-receta-flowers></tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-left">
                  <button type="button" class="btn btn-success btn-sm" onclick="addFlower();">
                    <i class="fa fa-plus"></i>&nbsp;Agregar Flor
                  </button>
                </div>
              </div>
            </div>
            <!-- materiales -->
            <div role="tabpanel" class="tab-pane" id="messages">
              <div class="row">
                <div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price per unity</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody data-receta-materiales></tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-left">
                  <button type="button" class="btn btn-success btn-sm" onclick="addMaterial();">
                    <i class="fa fa-plus"></i>&nbsp;Agregar Material
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          
        </form>
      </div>
      <div class="modal-footer">
        <div class="">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary btn-sm" form="recetas_form">
            <i class="fa fa-save"></i>&nbsp;Guardar
          </button>
        </div>
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
var create = false;

var cat_flowers     = [];
var cat_materiales  = [];

/*=====================================
=            FLOWERS LOGIC            =
=====================================*/

var flowers = [];

var renderFlowers = function()
{
  $("[data-receta-flowers]").html("");
  var html = "";
  for(var i in flowers)
  {
    html+=
    `<tr>
      <td>${renderDropDownFlowers(i, flowers[i].id)}</td>
      <td>${flowers[i].especie}</td>
      <td>${flowers[i].variedad}</td>
      <td>${flowers[i].color}</td>
      <td>
        <input type="number" name="" onkeyup="updateFlowerQty(${i}, this.value);" value="${flowers[i].pivot.quantity}" />
      </td>
      <td align="center">
        <button type="button" class="btn btn-danger btn-sm" onClick="removeFlower(${i});">
          <i class="fa fa-trash-o"></i>
        </button>
      </td>
    </tr>`;
  }
  $("[data-receta-flowers]").html(html);
};

var renderDropDownFlowers = function(originalIndex, flowerId)
{
  var dropDown = "";

  dropDown += `<select class="form-control" onchange="updateFlower(${originalIndex}, this.value)">`;
    dropDown += `<option value="na">SELECCIONE UNA FLOR</option>`;
    for(var index in cat_flowers)
    {
      var selected = (flowerId == cat_flowers[index].id) ? 'selected' : '';
      dropDown += `<option ${selected} value="${index}">${cat_flowers[index].name_posco}</option>`;
    }
  dropDown += `</select>`;

  return dropDown;
}

var updateFlower = function(index, cat_index)
{
  if(cat_index == "na")
  {
    flowers[index].id         = 0;
    flowers[index].name_posco = ":p";
    flowers[index].name_campo = ":p";
    flowers[index].especie    = "";
    flowers[index].variedad   = "";
    flowers[index].color      = "";
    flowers[index].pivot.quantity = 0;
  }
  else
  {
    cat_index = parseInt(cat_index);
    flowers[index].id         = cat_flowers[cat_index].id;
    flowers[index].name_posco = ":p";
    flowers[index].name_campo = ":p";
    flowers[index].especie    = cat_flowers[cat_index].especie;
    flowers[index].variedad   = cat_flowers[cat_index].variedad;
    flowers[index].color      = cat_flowers[cat_index].color;
  }
  
  renderFlowers();
};

var updateFlowerQty = function(index, value)
{
  var val = parseInt(value);
  val = (!isNaN(val))? val : 0;
  flowers[index].pivot.quantity = val;
};

var removeFlower = function(index)
{
   flowers.splice(index, 1);
   renderFlowers();
};

var addFlower = function()
{
  flowers.push({
    id: 0,
    name_posco: ':p',
    name_campo: ':p',
    especie: '',
    variedad: '',
    color: '',
    pivot: { quantity: 0 }
  });
  renderFlowers();
};

/*=====  End of FLOWERS LOGIC  ======*/

/*========================================
=            MATERIALES LOGIC            =
========================================*/

var materiales = [];

var renderMateriales = function()
{
  var html = "";
  for(var i in materiales)
  {
    html+=
    `<tr>
      <td>${renderDropDownMateriales(i, materiales[i].id)}</td>
      <td>${materiales[i].description}</td>
      <td>${materiales[i].size}</td>
      <td>${materiales[i].quantity}</td>
      <td>${materiales[i].price_per_unity}</td>
      <td align="center">
        <button type="button" class="btn btn-danger btn-sm" onClick="removeMaterial(${i});">
          <i class="fa fa-trash-o"></i>
        </button>
      </td>
    </tr>`;
  }
  $("[data-receta-materiales]").html(html);
}

var renderDropDownMateriales = function(originalIndex, materialId)
{
  var dropDown = "";

  dropDown += `<select class="form-control" onchange="updateMaterial(${originalIndex}, this.value)">`;
    dropDown += `<option value="na">SELECCIONE UN MATERIAL</option>`;
    for(var index in cat_materiales)
    {
      var selected = (materialId == cat_materiales[index].id) ? 'selected' : '';
      dropDown += `<option ${selected} value="${index}">${cat_materiales[index].name}</option>`;
    }
  dropDown += `</select>`;

  return dropDown;
}

var updateMaterial = function(index, cat_index)
{
  if(cat_index == "na")
  {
    materiales[index] = {
      id: 0,
      name: "",
      sku: "",
      description: "",
      size: "",
      quantity: 0,
      price_per_unity: 0
    }
  }
  else
  {
    cat_index = parseInt(cat_index);
    materiales[index] = cat_materiales[cat_index];
  }
  
  renderMateriales();
};

var removeMaterial = function(index)
{
   materiales.splice(index, 1);
   renderMateriales();
};

var addMaterial = function()
{
  materiales.push({
    id: 0,
    name: "",
    sku: "",
    description: "",
    size: "",
    quantity: 0,
    price_per_unity: 0
  });
  renderMateriales();
};

/*=====  End of MATERIALES LOGIC  ======*/


var limpiarModal = function()
{
  $("[name='id']").val(0);
  $("[name='name']").val('');
  $("[name='customer_id']").val(0);
  $("[name='case_id']").val(0);
  $("[name='sku']").val('');
  $("[name='type']").val(1);

  $("[data-receta-flowers]").html('');
  $("[data-receta-materiales]").html('');

  flowers     = [];
  materiales  = [];
};

$(document).ready(function()
{

  $.get('app/api/catalogos_recetas', function(json)
  {
    if(!json.success)
    {
      return false;
    }

    // catalogos
    cat_flowers     = json.data.cat_flowers;
    cat_materiales  = json.data.cat_materiales;

  }, "json");

	/**
	 *
	 * Abrir modal
	 *
	 */
	$modal.on("show.bs.modal", function(evt)
    {
   		var recetaid = evt.relatedTarget.dataset.recetaid;

   		limpiarModal();

      if(create) {
        return true;
      }

   		$.get('app/api/recetas/'+recetaid, function(json)
      	{
      		if(!json.success)
      		{
      			return false;
      		}

      		$("[name='id']").val(json.data.id);
      		$("[name='name']").val(json.data.name);
      		$("[name='customer_id']").val(json.data.customer_id);
      		$("[name='case_id']").val(json.data.case_id);
      		$("[name='sku']").val(json.data.sku);
          $("[name='type']").val(json.data.type);

          flowers = json.data.flowers;
          renderFlowers();

          materiales = json.data.material;
          renderMateriales();

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
            {
              mRender: function(d, t, r)
              {
                var output=
                `${r.name}<br><small>${r.client}</small>`;
                return output;
              }
            },
            { mData: 'type' },
            // { mData: 'client' },
            {
              sClass: 'text-center',
              mRender: function(d, t, r)
              {
                var output=
                `<span class="label label-${r.inmutable}">${r.inmutable}</span>`;

                return output;
              }
            },
            { mData: 'case' },
            {
                sClass: 'text-right',
                mRender: function(d, t, r)
                {
                    var output=
                    " <button onclick='create = false;' class='btn btn-sm btn-primary' data-recetaid='"+r.id+"' data-toggle='modal' data-target='#recetas_modal' >"+
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

    // un poco de validaciones
    if(flowers.length == 0)
    {
      alert("Por favor, agrega una Flor al menos.");
      return false;
    }
    if(materiales.length == 0)
    {
      alert("Por favor, agrega un Material al menos.");
      return false;
    }

    // mas validaciones
    for(var j in flowers)
    {
      if( flowers[j].id == 0 || flowers[j].pivot.quantity == 0 )
      {
        alert("Por favor, indica la Flor y/o Cantidad.");
        return false;
      }
    }
    for(var j in materiales)
    {
      if( materiales[j].id == 0 || materiales[j].quantity == 0 )
      {
        alert("Por favor, indica el Material.");
        return false;
      }
    }

    data.materiales = materiales;
    data.flowers    = flowers;

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