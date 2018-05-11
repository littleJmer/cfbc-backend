@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-cfbc">

                <div class="panel-heading">
                  <div class="row row-flex-acenter">
                    <div class="col-sm-2">
                      <select name="ver_ordenes" id="ver_ordenes" class="form-control">
                        <option value="activas" selected>Activas</option>
                        <option value="planificadas">Planificadas</option>
                        <option value="liberadas">Liberadas</option>
                      </select>
                    </div>
                    <div class="col-sm-10 text-right">
                        <button
                         type="button"
                         class="btn btn-sm btn-default" 
                         id="btnActivar">
                            <i class="fa fa-external-link-square" aria-hidden="true"></i>&nbsp;Activar
                        </button>
                        <button
                         type="button"
                         class="btn btn-sm btn-default" 
                         id="btnTerminar">
                            <i class="fa fa-pagelines" aria-hidden="true"></i>&nbsp;Liberar
                        </button>&nbsp;
                        <a
                         href="app/checkRecipes"
                         target="_blank"
                         class="btn btn-sm btn-default">
                            <i class="fa fa-check" aria-hidden="true"></i>&nbsp;Revisar Recetas
                        </a>&nbsp;
                        <!-- <button
                         type="button"
                         class="btn btn-sm btn-default" 
                         id="btnMaster">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Master
                        </button>&nbsp; -->
                        <button
                         type="button"
                         class="btn btn-sm btn-success" 
                         id="btnPlanificar">
                            <i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Planificar
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
                </div>

                <div class="panel-body">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- <div class="row" style="margin-bottom: 10px;">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4">
                            <label>Ver:</label>
                            <select name="ver_ordenes" id="ver_ordenes">
                              <option value="activas" selected>Activas</option>
                              <option value="planificadas">Planificadas</option>
                              <option value="liberadas">Liberadas</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 text-right"></div>
                    </div> -->

                    <div class="table-responsive">
                      <table id="ordenes_tabla" class="table">
                        <thead>
                          <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="example-select-all" /></th>
                            <th></th>
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
<!--  -->
<!--  -->
<!--  -->
@include('planner_modal');

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
  var $orden_modal      = $("#orden_modal");
  var $planner_modal    = $("#planner_modal");

  var $btnPlanificar  = $("#btnPlanificar");
  var $btnTerminar    = $("#btnTerminar");
  var $btnActivar     = $("#btnActivar");
  var $btnMaster      = $("#btnMaster");

  var $masterTable  = $("#masterTable");
  var $recipesTable = $("#recipesTable");

  var ordenesIds = new Array();

  var iTableCounter = 1;
  var productsTables = [];

  var fnFormatProducts = function(table_id) {
    var sOut = 
    `<table id="productsTable_${table_id}" class="productsTable">
      <thead>
        <tr>
          <th></th>
          <th>Description</th>
          <th>Steam Bunch</th>
          <th># Cases</th>
          <th>Bunches per Box</th>
          <th>Box type</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>`;
    return sOut;
  }

  var fnFormatFlowers = function(table_id) {
    var sOut = 
    `<table id="productsTable_${table_id}" class="productsTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Flower</th>
          <th>Variedad</th>
          <th>Color</th>
          <th>Qty recipe</th>
          <th>Qty</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>`;
    return sOut;
  }

  $(document).ready(function()
  {
    $btnActivar.hide();

    // Handle click on "Select all" control
    $('#example-select-all').on('click', function()
    {
        // Get all rows with search applied
        var rows = DTobj.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $btnPlanificar.click(function(e)
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
            loader.mensaje("Espere un momento obteniendo información...");
            loader.show();
            $.post('app/planner/init', {ordenes: ordenesIds}, function(response)
            {

                var inventario  = response.data.inventario;
                var ordenes     = response.data.ordenes;
                var html        = "";

                Planificador.init(inventario, ordenes);

                // launch modal
                $planner_modal.modal("show");

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

    $btnActivar.click(function(e)
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
            $.post('app/activar/ordenes', {ordenes: ordenesIds}, function(response)
            {
                if(!response.success)
                {
                    return false;
                }

                alert("Las ordenes fueron activadas con éxito. Da click en [OK] para continuar.");
                DTobj.ajax.reload( null, false )

            }, "json");
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
                DTobj.ajax.reload( null, false );

            }, "json");
        }

    });

    $("#ver_ordenes").change(function(e)
    {
      var value = $(this).val();
      // update table
      simple_loader.fadeIn();
      DTobj.ajax.url('app/api/ordenes/?estatus='+value).load(function() {
        simple_loader.fadeOut();
      });

      $btnTerminar.hide();
      $btnActivar.hide();

      if(value === 'liberadas') $btnActivar.show();
      if(value === 'activas') $btnTerminar.show();


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

    simple_loader.fadeIn();
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
            {
              "className": 'details-order',
              "orderable": false,
              "data": null,
              "defaultContent": '',
              "width": "5%"

            },
            { mData: 'sunvalley_order' },
            { mData: 'ship_date' },
            { mData: 'load_date' },
            { mData: 'destination' },
            {
              mRender: function(d,t,r)
              {
                var output=
                `${r.client}<br><small>${r.acc}</small>`;
                return output;
              }
            },
            {
              mRender: function(d,t,r)
              {
                var numberOfFlowers = Object.keys(r.flor).length;
                var output = "";

                if(numberOfFlowers == 0)
                  output = "Sin Flores";
                else if(numberOfFlowers == 1)
                {
                  for(var key in r.flor)
                  {
                    output = r.flor[key];
                  }
                }
                else
                  output = "Variadas";

                return output;
              }
            },
            { mData: 'n_box' },
            {
              mRender: function(d,t,r)
              {
                var numberOfBoxes = Object.keys(r.t_box).length;
                var output = "";

                if(numberOfBoxes == 0)
                  output = "Sin Cajas";
                else if(numberOfBoxes == 1)
                {
                  for(var key in r.t_box)
                  {
                    output = r.t_box[key];
                  }
                }
                else
                  output = "Variadas";

                return output;
              }
            },
            { mData: 'steam' },
            {
                sClass: 'text-right',
                mRender: function(d, t, r)
                {
                    var output=
                    "<a href='/app/imprimir/orden/"+r.id+"' target='_blank' class='btn btn-sm btn-default'>"+
                        "<i class='fa fa-print'></i>"+
                    "</a>";

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
            var color = "#43B581" // verde

            if ( aData.status == "1" )
            {
                color = "#AFB9C4"; // gris
            }

            if ( aData.status == "2" )
            {
                color = "#2196f3"; // rojo
            }

            console.log(aData.status);

            $(nRow).css({
                "background": color,
            });

        },
        initComplete: function(settings, json)
        {
          $('div.dataTables_filter input').addClass('form-control');
          $('div.dataTables_filter input').attr("placeholder", "Buscar en ordenes..");
          simple_loader.fadeOut();
        },
        oLanguage:
        {
          sSearch: ""
        }
    });

    // Add event listener for opening and closing products
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
          var row = DTobj.row( tr );
        }
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row

            var data = row.data();

            if(data.hasOwnProperty("products"))
            {

              row.child(fnFormatProducts(iTableCounter)).show();
              tr.addClass('shown');

              productsTables[iTableCounter] = $("#productsTable_" + iTableCounter).DataTable({
                dom: 't',
                bSort: false,
                aaData: data.products,
                aoColumns: [
                  {
                    "className": 'details-order',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '',
                    "width": "5%"

                  },
                  { mData: 'description'},
                  { mData: 'stem_bunch'},
                  { mData: 'no_cases'},
                  { mData: 'bunches_per_box'},
                  { mData: 'box_type'},
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
                  { mData: 'name'},
                  { mData: 'flowers'},
                  { mData: 'variedad'},
                  { mData: 'color'},
                  { mData: 'qty_recipe'},
                  { mData: 'qty'},
                ]
              });
            }

            iTableCounter++;
        }
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

    function checkManageWindow()
    {
      //
      var checkbox  = DTobj.$('input[type="checkbox"]:checked');
      var selected  = [];
      var total_bunches = 0;
      var total_cajas = 0;

      $.each(checkbox, function(key, item)
      {
        id = parseInt(item.value);
        selected.push(id);
      });

      if(selected.length > 0)
      {
        $("#manageWindow").css({
          'z-index': '9999',
          'left': '0%',
        });

        DTobj.rows().every( function ( rowIdx, tableLoop, rowLoop )
        {
            var row = this.data();

            if( selected.indexOf(row.id) != -1 )
            {

              total_cajas += row.n_box;
              if(row.products.length > 0)
              {

                for(var index in row.products)
                {
                  var bunches_per_box = parseInt(row.products[index].bunches_per_box);
                      bunches_per_box = (isNaN(bunches_per_box)) ? 0 : bunches_per_box;

                  total_bunches += bunches_per_box;
                }

              }

            }

        });

        $("input[name='keynumero_de_bonches']").val(total_bunches);
        $("input[name='keynumero_de_cajas']").val(total_cajas);

        var empleados = $("input[name='keynumero_de_empleados']").val();
        var horas = $("input[name='keyhoras_de_empleados']").val();

        empleados = parseInt(empleados);
        empleados = (isNaN(empleados)) ? 8 : empleados;

        horas = parseInt(horas);
        horas = (isNaN(horas)) ? 8 : horas;

        var minutos = 1.5;

        var horas_produccion = (minutos * total_bunches) / 60;

        var horas_hombres = (empleados * horas);


        var si_no = (horas_produccion <= horas_hombres) ? 
        "Si puede cumplir con la Producción." : "No puede cumplir con la Producción.";

        $("#keylabel_horas_prod").html(horas_produccion+" Horas de Producción.");
        $("#keylabel_horas_hombre").html(horas_hombres+" Horas Hombres.");
        $("#keylabel_si_no").html(si_no);

      }
      else
      {
        $("#manageWindow").css({
          'z-index': '-1',
          'left': '100%',
        });
      }
      // 
      
      // recur
      setTimeout(function() {
        checkManageWindow();
      }, 1000);
    };
    checkManageWindow();

  });
</script>
@endsection