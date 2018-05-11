<div id="explosionv2" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Explosión de Flores[v2]</h4>
			</div>

			<div class="modal-body">
				

				<div class="container-fluid">
					<div class="row">
						<section>
							<div class="wizard">
								<div class="wizard-inner">
									<div class="connecting-line"></div>
									<ul class="nav nav-tabs" role="tablist" data-explosion-nav></ul>
								</div>

								<form role="form">
									<div class="tab-content">


										<div class="tab-pane active" role="tabpanel" id="step1">
											<h3>Inventario | Fijas</h3>
											<div>
												<table class="table table-striped table-bordered table-condensed" id="inventario_fijas">
													<thead>
														<tr>
															<th>Skunumber</th>
															<th>Description</th>
															<th>Inventario</th>
															<th>Req. Fijas</th>
															<th>Restante</th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
											<ul class="list-inline pull-right">
												<li><button type="button" class="btn btn-primary" onclick="Explosion.nextBtn(this);">next</button></li>
											</ul>
										</div>


										<div class="tab-pane" role="tabpanel" id="step2">
											<h3>Inventario | Abiertas</h3>
											<div>
												<div id="gestion_abiertas"></div>
											</div>
											<ul class="list-inline pull-right">
												<li><button type="button" class="btn btn-default" onclick="Explosion.prevBtn(this);">Previous</button></li>
												<li><button type="button" class="btn btn-primary" onclick="Explosion.nextBtn(this);">next</button></li>
											</ul>
										</div>


										<div class="tab-pane" role="tabpanel" id="step3">
											<h3>Fechas</h3>

											<div class="row">
												<div class="col-sm-12">
													<table class="table table-bordered table-hover table-condensed" id="step3Table">
														<thead>
															<tr>
																<th># Orden</th>
																<th>Cliente</th>
																<th>Tallos</th>
																<th>Load Date</th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
												</div>
											</div>

											<ul class="list-inline pull-right">
												<li><button type="button" class="btn btn-default" onclick="Explosion.prevBtn(this);">Previous</button></li>
												<li><button type="button" class="btn btn-default" onclick="Explosion.nextBtn(this);">next</button></li>
											</ul>
										</div>
										<div class="tab-pane" role="tabpanel" id="step4">
											<h3>Plan</h3>
											
											<div class="row">
												<div class="col-sm-12 text-right">
													<span data-pbuttons></span>
													<button 
														class="btn btn-sm btn-primary" 
														style="margin: 5px 0;" 
														type="button" 
														onclick="Explosion.dd();">
														<i class="fa fa-download"></i>&nbsp;Descargar Master
													</button>
												</div>
											</div>

											<div class="table-responsive">
												<table
												  class="table table-striped table-bordered table-hover table-condensed"
												  id="step4Table">
													<thead></thead>
													<tbody></tbody>
												</table>
											</div>

											<ul class="list-inline pull-right">
												<li><button type="button" class="btn btn-default" onclick="Explosion.prevBtn(this);">Previous</button></li>
												<li><button type="button" class="btn btn-primary btn-info-full" onclick="Explosion.nextBtn(this);">Submit</button></li>
											</ul>
										</div>
										<div class="clearfix"></div>
									</div>
								</form>
							</div>
						</section>
					</div>
				</div>


			</div>

			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
			</div> -->

		</div>

	</div>
</div>

<!--  -->

<div id="editRecipesOpen" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Edit Recipes with flower type: <span id="type_flower_title"></span></h4>
			</div>

			<div class="modal-body">
				<form action="">
					<div class="row">
						<div class="col-sm-12">
							<div>
								<div id="flowerTypeToEditTable"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<dic class="col-sm-12">
							<button type="button" class="btn btn-xs btn-primary" onclick="Explosion.prevRecipe();">
								<i class="fa fa-chevron-left"></i>
							</button>
							<label>
								<span id="recipeActual"></span> de <span id="recipesTotalEdit"></span>
							</label>
							<button type="button" class="btn btn-xs btn-primary" onclick="Explosion.nextRecipe();">
								<i class="fa fa-chevron-right"></i>
							</button>
						</dic>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Client</label>
									<input type="text" class="form-control" name="client" readonly />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Sun Valley order</label>
									<input type="text" class="form-control" name="sun_valley_order" readonly />
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>Description</label>
									<input type="text" class="form-control" name="description" readonly />
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>Box type</label>
									<input type="text" class="form-control" name="box_type" readonly />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="col-sm-2">
								<div class="form-group">
									<label>Stems</label>
									<input type="text" class="form-control" name="stems" readonly />
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Bunches per box</label>
									<input type="text" class="form-control" name="bunches_per_box" readonly />
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label># of cases</label>
									<input type="text" class="form-control" name="number_of_cases" readonly />
								</div>
							</div>
							<div class="col-sm-2">
								<div class="oddBox label label-primary">
									<span id="originalBunchQty">
										<small>Requerido</small>
										<div></div>
									</span>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="oddBox label label-primary">
									<span id="customBunchQty">
										<small>Acomulado</small>
										<div></div>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<!-- <div class="col-sm-12"> -->
								<div class="table-responsive">
									<table class="table table-hover table-striped table-condensed table-bordered">
										<thead>
											<tr>
												<th>Skunumber</th>
												<th>Flower type</th>
												<th>Variety / Color</th>
												<th>Grade</th>
												<th>Bunch Qty</th>
												<th>Steam</th>
												<th>Total Steam</th>
												<th>Controls</th>
											</tr>
										</thead>
										<tbody id="recipeFlowers"></tbody>
									</table>
								</div>
							<!-- </div> -->
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="col-sm-6 text-left">
								<button type="button" class="btn btn-sm btn-success" onclick="Explosion.addFlower();">
									<i class="fa fa-plus"></i>&nbsp;Agregar
								</button>
							</div>
							<div class="col-sm-6 text-right">
								<button type="button" class="btn btn-sm btn-primary" 
								data-btn-submit onclick="Explosion.saveRecipeEdit();">
									<i class="fa fa-save"></i>&nbsp;Guardar
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
			</div>

		</div>

	</div>
</div>




