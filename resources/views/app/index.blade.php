@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-cfbc">

                <div class="panel-heading">Ordenes</div>

                <div class="panel-body">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row" style="margin-bottom: 10px;">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4">
                            <label>Ver:</label>
                            <select name="ver_ordenes" id="ver_ordenes">
                              <option value="activas" selected>Activas</option>
                              <option value="liberadas">Liberadas</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 text-right">
                        <button
                         type="button"
                         class="btn btn-sm btn-default" 
                         id="btnMaster">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Master
                        </button>&nbsp;
                        <button
                         type="button"
                         class="btn btn-sm btn-default" 
                         id="btnTerminar">
                            <i class="fa fa-pagelines" aria-hidden="true"></i>&nbsp;Liberar
                        </button>&nbsp;
                        <button
                         type="button"
                         class="btn btn-sm btn-primary"
                         data-toggle="modal"
                         data-target="#importar_modal">
                          <i class="fa fa-upload" aria-hidden="true"></i>
                          &nbsp;Importar
                        </button>
                      </div>
                    </div>

                    <div>
                      <table id="ordenes_tabla" class="table">
                        <thead>
                          <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="example-select-all" /></th>
                            <th>Sun Valley Order</th>
                            <th>Customer Name / Acct</th>
                            <th>Order Ship Date</th>
                            <th>Customer Ship To</th>
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
<div id="importar_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Importacion de Ordenes</h4>
      </div>
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
          <!--  -->
          <button
           type="submit"
           class="btn btn-sm btn-primary"
           name="submit"
           form="importar_form"
           data-file-save>
            <i class="fa fa-upload" aria-hidden="true"></i>
            &nbsp;Da click para importar el archivo
          </button>
          <form id="importar_form" name="importar_form" class="" enctype="multipart/form-data" method="post">
            <input type="file" id="file" name="file" size="10" class="hidden" />
            <p data-file-info class="">
              <b>Nombre: </b><span data-file-name></span><br>
              <b>Tipo: </b><span data-file-type></span><br>
              <b>Tamaño: </b><span data-file-size></span><br>
            </p>
          </form>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!--  -->
<!--  -->
<!--  -->
<div id="orden_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Ver Orden</h4>
      </div>

      <div class="modal-body">
        <div class="container-fluid">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Orden</a>
            </li>
            <li role="presentation">
              <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Recetas</a>
            </li>
            <li role="presentation">
              <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Historial</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
              <table class="table table-bordered">
                <tr>
                  <th>Customer Name / Acct</th>
                  <td data-o-customer-name-acct></td>
                </tr>
                <tr>
                  <th>Customer PO Number</th>
                  <td data-o-customer-po-number></td>
                </tr>
                <tr>
                  <th>Salesman Name / Number</th>
                  <td data-o-sales-rep-name></td>
                </tr>
                <tr>
                  <th>Sun Valley Order #</th>
                  <td data-o-sun-valley-order></td>
                </tr>
                <tr>
                  <th>Oxnard Ship Via</th>
                  <td></td>
                </tr>
                <tr>
                  <th>Oxnard Ship Date</th>
                  <td></td>
                </tr>
                <tr>
                  <th>Farm Ship Date</th>
                  <td></td>
                </tr>
              </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
              <div data-o-recipes></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="messages">
              <ul data-log></ul>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
      </div>

    </div>

  </div>
</div>
@endsection

@section('extrajs')
<script type="text/javascript">

  $("[data-file-upload]").show();
  $("[data-file-save]").hide();
  $("[data-file-info]").hide();
  $("[data-file-name]").html('');
  $("[data-file-type]").html('');
  $("[data-file-size]").html('');

  var $tabla = $("#ordenes_tabla");
  var DTobj = null;
  var $orden_modal = $("#orden_modal");

  var $btnTerminar  = $("#btnTerminar");
  var $btnMaster    = $("#btnMaster");

  var ordenesIds = new Array();

  $(document).ready(function()
  {

    // Handle click on "Select all" control
    $('#example-select-all').on('click', function()
    {
        // Get all rows with search applied
        var rows = DTobj.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $btnMaster.click(function(e)
    {
        var data = DTobj.$('input[type="checkbox"]:checked');

        ordenesIds.length = 0;
        $.each(data, function(key, item)
        {
           id = parseInt(item.value);
           ordenesIds.push(id);
        });

        if(ordenesIds.length > 0)
        {
            loader.mensaje("Generando Master...");
            loader.show();
            $.post('app/master', {ordenes: ordenesIds}, function(response)
            {
                //
                if(!response.success)
                {
                    alert('Fallo la creación del Master.');
                    return false;
                }

                var link    = document.createElement('a');
                link.href   = "/storage/master/"+response.data.file;
                link.click();

            }, "json")
            .always(function() {
                loader.hide();
            });
        }
        else
        {
            alert("Por favor selecione al menos 1 orden.");
        }

    });

    $btnTerminar.click(function(e)
    {
        var data = DTobj.$('input[type="checkbox"]:checked');

        ordenesIds.length = 0;
        $.each(data, function(key, item)
        {
           id = parseInt(item.value);
           ordenesIds.push(id);
        });

        if(ordenesIds.length > 0)
        {
            $.post('app/liberar/ordenes', {ordenes: ordenesIds}, function(response)
            {
                if(!response.success)
                {
                    return false;
                }

                alert("Las ordenes fueron liberadas con éxito. Da click en [OK] para continuar.");
                DTobj.ajax.reload( null, false )

            }, "json");
        }

    });

    $("#ver_ordenes").change(function(e)
    {
      var value = $(this).val();
      // update table
      DTobj.ajax.url('app/api/ordenes/?estatus='+value).load();
    });

    $orden_modal.on("show.bs.modal", function(evt)
    {
      var ordenid = evt.relatedTarget.dataset.ordenid;

      $.get('app/api/orden/'+ordenid, function(json)
      {

        if(!json.success)
        {
          return false;
        }

        // orden
        $("[data-o-customer-name-acct]").html(json.data.orden.customer_name_acct);
        $("[data-o-customer-po-number]").html(json.data.orden.customer_po_number);
        $("[data-o-sales-rep-name]").html(json.data.orden.sales_rep_name);
        $("[data-o-sun-valley-order]").html(json.data.orden.sun_valley_order);

        // log
        $("data-log").html("");
        var html = "";
        $.each(json.data.log, function(key, item)
        {
          html+=
          "<li>"+item.text+"</li>";
        });
        $("[data-log]").html(html);

        // recipes/recetas
        $("[data-o-recipes]").html("");
        html = "";
        $.each(json.data.products, function(key, item)
        {
          html+=
          `<table class="table table-bordered">
            <tr>
              <th>Product Description</th>
              <th>Stem / Bunch</th>
              <th># of Cases</th>
              <th>Bunches per Box</th>
              <th>Box Type</th>
              <th>Box Code / SKU</th>
            </tr>
            <tr>
              <td>${item.description}</td>
              <td>${item.stem_bunch}</td>
              <td>${item.no_cases}</td>
              <td>${item.bunches_per_box}</td>
              <td>${item.box_type}</td>
              <td>${item.box_code_sku}</td>
            </tr>
            <tr>
              <th>UPC Type</th>
              <th colspan=2>Sleeve Name & Size</th>
              <th>Insert</th>
              <th colspan=2>Flower Food</th>
            </tr>
            <tr>
              <td>${item.upc_type}</td>
              <td colspan=2></td>
              <td></td>
              <td colspan=2></td>
            </tr>
            <tr>
              <th>UPC # (Include Check Digital)</th>
              <th colspan=2>Description on Label</th>
              <th>Date Code</th>
              <th colspan=2>Retail Price</th>
            </tr>
            <tr>
              <td>${item.upc_no}</td>
              <td colspan=2></td>
              <td></td>
              <td colspan=2></td>
            </tr>
          </table>`;


        });
        $("[data-o-recipes]").html(html);

      }, 'json');
    });

    DTobj = $tabla.DataTable({
        responsive: true,
        stateSave: false,
        columnDefs:
        [
            {
                targets: [-1, 0],
                orderable: false
            }
        ],
        ajax:
        {
            url: 'app/api/ordenes',
            dataSrc: 'data'
        },
        aoColumns:
        [
            {
                bSortable: false,
                asSorting: false,
                className: 'dt-body-center',
                mRender: function(d, t, r)
                {
                    return '<input type="checkbox" name="id[]" value="' + r.id + '">';
                }
            },
            { mData: 'sun_valley_order' },
            { mData: 'customer_name_acct' },
            { mData: 'order_ship_date' },
            { mData: 'customer_ship_to' },
            {
                sClass: 'text-right',
                mRender: function(d, t, r)
                {
                    var output=
                    "<a href='/app/imprimir/orden/"+r.id+"' target='_blank' class='btn btn-sm btn-default'>"+
                        "<i class='fa fa-print'></i>"+
                    "</a>";

                    // output+=
                    // " <a href='/app/imprimir_master/orden/"+r.id+"' target='_blank' class='btn btn-sm btn-default'>"+
                    //     "<i class='fa fa-table' aria-hidden='true'></i>"+
                    // "</a>";

                    output+=
                    " <button class='btn btn-sm btn-primary' data-ordenid='"+r.id+"' data-toggle='modal' data-target='#orden_modal' >"+
                        "<i class='fa fa-pencil-square-o'></i>"+
                    "</button>";

                    return output;
                }
            },
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex )
        {
            var color = "#2962ff82"

            if ( aData.status == "1" )
            {
                color = "#4caf5094";
            }

            $(nRow).css({
                "background": color,
            });

        },
    });

    $(':file').on('change', function()
    {
        $("[data-file-upload]").hide();
        $("[data-file-save]").hide();
        $("[data-file-info]").hide();
        $("[data-file-name]").html('');
        $("[data-file-type]").html('');
        $("[data-file-size]").html('');

        var fr    = new FileReader();
        var file  = this.files[0];

        fr.onload = function(evt)
        {
            $("[data-file-save]").show();
            $("[data-file-info]").show();
            $("[data-file-name]").html(file.name);
            $("[data-file-type]").html(file.type);
            $("[data-file-size]").html(file.size);
        };

        fr.readAsDataURL(this.files[0]);
    });

    $('#importar_modal').on('show.bs.modal', function (e)
    {
      $("[data-file-upload]").show();
      $("[data-file-save]").hide();
      $("[data-file-info]").hide();
      $("[data-file-name]").html('');
      $("[data-file-type]").html('');
      $("[data-file-size]").html('');
    });

    $('#importar_form').submit(function(e)
    {
        e.preventDefault();

        $.ajax(
        {
            url: 'app/importar/ordenes',
            method: 'POST',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function()
            {

            },
            complete: function()
            {

            },
            success: function(response)
            {
                if(!response.success)
                {
                    alert(response.msg);
                    return false;
                }

                alert("Las ordenes fueron importadas con éxito. Da click en [OK] para continuar.");
                setTimeout(function()
                {
                  location.reload();
                }, 1000);
            },
            error: function()
            {

            }
        });

    });

  });
</script>
@endsection