<div id="plannerv2" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Planificador [v2] <span id="pname"></span></h4>
      </div>

      <div class="modal-body">
        <div class="container-fluid">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#one" aria-controls="one" role="tab" data-toggle="tab">Recetas</a>
                </li>
                <li role="presentation">
                    <a href="#two" aria-controls="two" role="tab" data-toggle="tab">Explosión de Flor</a>
                </li>
                <li role="presentation">
                    <a href="#three" aria-controls="two" role="tab" data-toggle="tab">Cronograma</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="one">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover table-condensed" id="recipesTable">
                                <thead>
                                    <tr>
                                        <th>Sunvalley Order</th>
                                        <th>Client</th>
                                        <th>Controles</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="two">
                	
	                <div class="row">
	                	<div class="col-sm-12 text-right p-15">
	                		<span data-pbuttons></span>
	                		<button 
	                			class="btn btn-sm btn-primary" 
	                			style="margin: 5px 0;" 
	                			onclick="Planificador.dd();">
	                			<i class="fa fa-download"></i>&nbsp;Descargar Master
	                		</button>
	                	</div>
	                </div>

	            </div>

                <div role="tabpanel" class="tab-pane" id="three">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-condensed" id="masterTable"></table>
                            </div>
                        </div>
                    </div>
                </div>


          </div>
          <!-- end Tab panes -->

            
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary btn-sm">
        	<i class="fa fa-save">&nbsp;</i>Guardar
        </button>
      </div>

    </div>

  </div>
</div>
<!--  -->
<!--  -->
<!--  -->
<div id="modalchange_flower" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Cambiar Flor en estas Recetas[In progress]</h3>
      </div>

      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <table id="table_to_change" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Receta</th>
                                    <th>Tipo</th>
                                    <th>Inmutable</th>
                                    <th>Caja</th>
                                    <th>Controles</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
        	Cerrar
        </button>
        <button type="button" class="btn btn-primary btn-sm">
        	<i class="fa fa-save">&nbsp;</i>Guardar
        </button>
      </div>

    </div>

  </div>
</div>