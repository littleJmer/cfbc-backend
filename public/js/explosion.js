var randomIntFromInterval = function(min,max) {
	return Math.floor(Math.random()*(max-min+1)+min);
}

var Explosion = Explosion ? Explosion : {

	$tableFijas: $("#inventario_fijas"),
	$modal: $("#explosionv2"),

	init: function(data) {

		//
		if( $('[name="keyname_production"]').val() == '' ) {
 			swal("Error", "Por favor define un nombre para el plan", "error");
			return false;
		}
		//

		var self = this;

		this.invValid = true;

		this.data = data;
		this.required = [];

		this.inventory = [];
		this.inventoryGroup = [];

		this.recipesToEdit = [];
		this.recipeActual = 1;

		this.master = {
			fechas: [],
			inventario: []
		};

		this.steps = {
			1: true,
			2: false,
			3: false,
			4: false,
		};

		// get inventory
		$.get('app/apiv2/inventario/para_planificar', function(response) {

			console.log("Inventory from db >>>", response);
			self.inventory = response;
			self.render();

		}, 'json');

		// this.render();
	},

	reinit: function(cb) {

		this.inventory 		= [];
		this.inventoryGroup = [];
		this.required  		= [];

		this.steps = {
			1: true,
			2: false,
			3: false,
			4: false,
		};

		this.$modal.modal("show");

		this.renderNav();

		this.firstStep();

	},

	render: function() {

		this.$modal.modal("show");
		
		this.renderNav();

		this.firstStep();

		this.events();

	},

	events: function() {

		var self = this;

		$('a[data-toggle="tab"]').off('show.bs.tab');
		$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

			var $target = $(e.target);

			if ($target.parent().hasClass('disabled')) {
				return false;
			}

		});

		$('#editRecipesOpen').off('hidden.bs.modal');
		$('#editRecipesOpen').on('hidden.bs.modal', function (e) {
			setTimeout(function() {

				// aply changes
				self.reinit();

				// function() {
				// 	self.$modal.modal("show");
				// }

			}, 500);
		})

		// var temp = $('[data-explosion-nav] li').eq(0)[0];
		// $(temp).find('a[data-toggle="tab"]').click();

	},

	nextBtn: function(e) {
		var $active = $('.wizard .nav-tabs li.active');
		$active.addClass('completed');
		$active.next().removeClass('disabled');
		this.nextTab($active);
	},

	prevBtn: function(e) {
		var $active = $('.wizard .nav-tabs li.active');
		this.prevTab($active);
	},

	nextTab: function(elem) {

		var self = this;
		var step = $(elem).next().index() + 1;

		if( self.steps[step] === false ) return false;

		if(self.invValid == false) {
			swal("Inventario Insuficiente", "Por favor revice las ordenes.", "error");
			return false;
		}

		if(step == 2) {
			// secondStep
			self.secondStep();
		}
		else if(step == 3) {
			// thirdStep
			self.thirdStep();
		}
		else if(step == 4) {
			// fourthStep
			self.fourthStep();
		}

		$(elem).next().find('a[data-toggle="tab"]').click();


	},

	prevTab: function(elem) {
		$(elem).prev().find('a[data-toggle="tab"]').click();
	},

	renderNav: function() {

		var options = [
			{ href: '#step1', title: 'Step 1', icon: 'fa fa-lock', class: 'active'},
			{ href: '#step2', title: 'Step 2', icon: 'fa fa-unlock-alt', class: 'disabled'},
			{ href: '#step3', title: 'Step 3', icon: 'fa fa-calendar', class: 'disabled'},
			{ href: '#step4', title: 'Step 4', icon: 'fa fa-list-ul', class: 'disabled'},
		];

		var html = '';

		options.forEach(function(op) {

			html += `
			<li role="presentation" class="${op.class}">
				<a href="${op.href}" data-toggle="tab" aria-controls="step1" role="tab" title="${op.title}">
					<span class="round-tab">
						<i class="${op.icon}"></i>
					</span>
				</a>
			</li>`;

		});

		$("[data-explosion-nav]").html(html);
	},

	firstStep: function() {

		// ugly fix
		$(".tab-pane.active").removeClass("active");
		$("#step1").addClass("active");

		console.log(">>> Rendering first step: Ordenes Fijas");

		var self = this;

		self.invValid = true;

		// getting the flower required
		this.data.forEach(function(orden) {

			orden.cases.forEach(function(box) {

				box.flowers.forEach(function(flower) {

					// console.log(box.isOpen);
					self.insertFlower(flower, box.isOpen);

				});

			});

		});

		console.log(this.required);

		var html = "", p_total = 0;

		self.inventory.forEach(function(inv) {

			// var quantity_required = 0;

			self.required.forEach(function(req) {

				if( req.location 		=== inv.location && 
					req.flower_type 	=== inv.flower_type && 
					req.variety_color 	=== inv.variety_color ) {

					// quantity_required += req.fijas;
					inv.quantity_locked += req.locked;

				}

			});

			// inv.quantity_after_locked = inv.quantity - quantity_required;
			inv.quantity_after_locked = inv.quantity - inv.quantity_locked;

			// if(quantity_required > 0) {
			if(inv.quantity_locked > 0) {

				var style = "";

				if(inv.quantity_after_locked < 0) {
					style = "style=\"background-color: red;color: #fff;\"";
					self.invValid = false;
				}

				html +=
				`<tr ${style}>
					<td>${inv.skunumber}</td>
					<td>${inv.skudesc}</td>
					<td>${inv.quantity}</td>
					<td>${inv.quantity_locked}</td>
					<td>${inv.quantity_after_locked}</td>
				</tr>`;

			}

		});

		this.$tableFijas.find("tbody").html(html);

		if(html === "") {
			// el inventario quedo intacto
			// puede avanzar al siguiente paso (2)
			this.$tableFijas.find("tbody").html(`
				<tr>
					<td align=center colspan=5>
						<i class="fa fa-smile-o" style="font-size: 70px;"></i>
						<p>No hay ordenes fijas</p>
					</td>
				</tr>
			`);
		}

		// make step 2 available
		this.steps[2] = true;

	},

	secondStep: function() {

		var inventory_ 	= [];
		var self 		= this;

		console.log(">>> Rendering second step. Ordenes Abierta.");

		// se agrupan por flores > color
		self.inventory.forEach(function(item) {

			var typeKey = null;

			// console.log(item);

			// find flower type
			inventory_.forEach(function(item2, index) {

				if( item.location 		=== item2.location && 
					item.flower_type 	=== item2.flower_type ) {
					typeKey = index;
				}

			});

			if(typeKey !== null) {

				inventory_[typeKey].quantity += item.quantity_after_locked;

			}
			else {

				inventory_.push({
					location 		: item.location,
					flower_type 	: item.flower_type,
					flower_text 	: item.flower_text,
					quantity 		: item.quantity_after_locked,
					variety_color 	: [],
				});

				typeKey = inventory_.length - 1;

			}

			inventory_[typeKey].variety_color.push({
				skunumber 		: item.skunumber,
				skudesc 		: item.skudesc,
				variety_color 	: item.variety_color,
				variety_color_text : item.variety_color_text,
				quantity 		: item.quantity_after_locked,
			});

		});

		self.inventoryGroup = inventory_;

		console.log(">>> >>> Inventario agrupado.", self.inventoryGroup);

		self.renderSecondStep();

		// make step 3 avalaible
		this.steps[3] = true;

	},

	renderSecondStep: function() {

		var self = this;
		var html = "";
		var size = self.inventoryGroup.length;

		self.invValid = true;

		console.log(">>> >>> Flores requeridas.", self.required);

		self.inventoryGroup.forEach(function(inv, invIndex) {

			var total_required = 0;

			// search required
			self.required.forEach(function(req) {
				if(req.flower_type === inv.flower_type) {
					total_required += req.opened;
				}
			});

			// if total required is equal to 0, skip it
			if( total_required === 0 ) return;

			html+=
			`<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>Flower type</th>
						<th>Requerido</th>
						<th>Inventario</th>
						<th>Porcentaje</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>${inv.flower_text}</td>
						<td>${total_required}</td>
						<td>${inv.quantity}</td>
						<td>100%</td>
					</tr>`;

			inv.variety_color.forEach(function(vc, vcIndex) {

				var requeridas 	= 0;
				var styles 		= "";

				self.required.forEach(function(req, reqIndex) {
					if(
						req.flower_type === inv.flower_type && 
						req.variety_color === vc.variety_color
					  ) {
						requeridas += req.opened;
					}
				});

				if(requeridas > vc.quantity) {
					styles = "style=\"background-color: red;color: #fff;\"";
					self.invValid = false;
				}

				var rowPorcent = (parseInt(vc.quantity) * 100) / parseInt(inv.quantity);
				rowPorcent = Math.ceil(rowPorcent);

				html+=
				`<tr ${styles}>
					<td>Color: ${vc.variety_color_text}</td>
					<td>${requeridas}</td>
					<td>${vc.quantity}</td>
					<td>${rowPorcent}%</td>
				</tr>`;

			});

			html+=
			`
			<tr>
				<td>
					<!-- a href="javascript:void(0);" onclick="Explosion.editRecipesOpen('${inv.flower_type}');" -->
					<a href="javascript:void(0);" onclick="Explosion.editRecipesOpen('${invIndex}');">
						<i class="fa fa-edit"></i>&nbsp;Edit
					</a>
				</td>
			</tr>
			`;

			html+= `</tbody></table>`

			$("#gestion_abiertas").html(html);

		});


		// self.inventoryGroup.forEach(function(item, index) {

		// 	// console.log(item, index);

		// 	var requerido_total = 0;

		// 	self.required.forEach(function(item3, index3) {
		// 		if(item.flower_type === item3.flower_type) {
		// 			requerido_total += item3.abiertas;
		// 		}
		// 	});

		// 	if(requerido_total === 0) { 
		// 		return; 
		// 	}

		// 	html+=`
		// 	<table class="table table-striped table-bordered table-condensed">
		// 		<thead>
		// 			<tr>
		// 				<th>Flower type</th>
		// 				<th>Requerido</th>
		// 				<th>Inventario</th>
		// 				<th>Porcentaje</th>
		// 			</tr>
		// 		</thead>
		// 		<tbody>`;

		// 	//
		// 	html += `
		// 		<tr>
		// 			<td>${item.flower_type}</td>
		// 			<td>${requerido_total} <span data-diff="${index}" class="text-warning"></span></td>
		// 			<td>${item.quantity}</td>
		// 			<td>100%</td>
		// 		</tr>
		// 	`;

		// 	item.variety_color.forEach(function(item2, indexColor) {

		// 		// console.log(item2, index2);

		// 		var requerido = 0;

		// 		self.required.forEach(function(item3, index3) {

		// 			// console.log(item2, item3);

		// 			if( item.flower_type === item3.flower_type && 
		// 				item2.variety_color === item3.variety_color ) {
		// 				requerido += item3.abiertas;
		// 			}
		// 		});

		// 		var porciento = (requerido * 100) / requerido_total;

		// 		// ugly fix
		// 		item.requerido_original = requerido_total;

		// 		html += `
		// 			<tr>
		// 				<td>Color: ${item2.variety_color}</td>
		// 				<td>
		// 					<input 
		// 					  type="number"
		// 					  value=${requerido}
		// 					  min=0
		// 					  onfocusout="Explosion.updateReqOpen(this, ${index}, ${indexColor});" />
		// 				</td>
		// 				<td>${item2.quantity}</td>
		// 				<td><span data-porcent="${index}-${indexColor}">${porciento}%</span></td>
		// 			</tr>
		// 		`;

		// 	});

		// 	//

		// 	html+=`</tbody>
		// 	</table>
		// 	`;

		// });

		// $("#gestion_abiertas").html(html);

	},

	thirdStep: function() {

		var self = this;
		var html = "";

		this.data.forEach(function(orden, index) {

			// console.log(orden);

			var readonly 	= "";
			var master_date = orden.master_date || "";

			// console.log(master_date);

			// html +=
			// `<tr>
			// 	<td>
			// 		<span class="text-danger">Sunvalley Order:</span> <small>${orden.sun_valley_order}</small> <br>
			//                  <span class="text-danger">Ship Date:</span> <small>${orden.ship_date}</small> <br>
			//                  <span class="text-danger">Load Date:</span> <small>${orden.load_date}</small> <br>
			//                  <span class="text-danger">Destination Via:</span> <small>${orden.destination_via}</small> <br>
			//                  <span class="text-danger">Client:</span> <small>${orden.client}</small> <br>
			// 	</td>
			// 	<td>
			// 		<input 
			//                   type="date" 
			//                   value="${master_date}" 
			//                   ${readonly} 
			//                   onchange="Explosion.setMasterDate(${index}, this.value);" />
			// 	</td>
			// </tr>`;

			html +=
			`
			<tr>
				<td width=20%>${orden.sun_valley_order}</td>
				<td width=30%>${orden.client}</td>
				<td width=20%>${orden.total_stem}</td>
				<td width=30%>
					<span class="label label-primary">${orden.load_date}</span>
					<input type="date" value="${master_date}" onchange="Explosion.setMasterDate(${index}, this.value);" />
				</td>
			</tr>
			`;

		});

		$("#step3Table tbody").html(html);

		this.steps[4] = true;
	},

	fourthStep: function() {

		var self = this;

		self.master.fechas = [];

		self.master.inventario = $.extend(true, [], self.inventory);

		/*
        |
        | get master.fechas
        |
        */
		self.data.forEach(function(orden, index) {

			if( orden.master_date != null && 
				orden.master_date != "" ) { 
				
				if( !(self.master.fechas.indexOf(orden.master_date) > -1) ) {

					self.master.fechas.push(orden.master_date);

				}

			}

		});

		self.ordenarFechas();

		/*
        |
        | ＿φ(◎‿ ◎ )
        |
        */
		for(var i in self.master.fechas) {

			var index = parseInt(i);
			var fecha = self.master.fechas[i];

			for(var j in self.master.inventario) {

				var holder = self.master.inventario[j];

				var start   = ((index+1) - 1) * 5 + 1;
				var finish  = start + 5 - 1;
				var loop    = start;

				while(loop <= finish) {
					holder[loop] = 0;
					loop++;
				}

				if(start === 1) {
					holder[1] = holder.quantity;
				}

				///
				for(var k in self.data) {

					var orden = this.data[k];

					for(var l in orden.cases) {

						var cases = orden.cases[l];

						for( var m in cases.flowers ) {

							var flower = cases.flowers[m];

							// holder.flower_type
							// holder.variety_color
							// holder.grade

							// flower.flower_type
							// flower.variety_color
							// flower.grade
							// flower.stem_count
							// flower.bunch_qty

							if( holder.flower_type == flower.flower_type && 
								holder.variety_color == flower.variety_color
							) {

								holder[start+1] += flower.stem_count * flower.bunch_qty;
							}

						}

					}

				}
				////

			}

		}

        // for(var i in self.master.fechas) {

        //     var index = parseInt(i);
        //     var fecha = self.master.fechas[i];

        //     for(var index_inventario in self.master.inventario) {

        //         var   = this.master.inventario[index_inventario];

        //         console.log(holder);

        //         var start   = ((index+1) - 1) * 5 + 1;
        //         var finish  = start + 5 - 1;
        //         var loop    = start;

        //         while(loop <= finish)
        //         {
        //             holder[loop] = 0;
        //             loop++;
        //         }

        //         if(start === 1)
        //         {
        //             holder[1] = holder.quantity;
        //         }

        //         for(var k in this.ordenes)
        //         {
        //             var ordenOriginal = this.ordenes[k];

        //             if(ordenOriginal.master_date === fecha)
        //             {
        //                 if(master_florid in ordenOriginal.flowers)
        //                 {
        //                     holder[start+1] += ordenOriginal.flowers[master_florid].qty;
        //                 }
        //             }
        //         }
        //     }
        // }

		/*
        |
        | Render
        |
        */
		this.renderMaster();

	},

	renderMasterHeader: function() {
        var html = ``;
        var self = this;

        if(self.master.fechas.length === 0) {
            html=
            `<th colspan="5" class="text-center">Por favor planifique almenos 1 orden</th>`;
        }
        else {
            for(var i in self.master.fechas) {

                var x = self.master.fechas[i];

                html += `<th colspan="5" class="text-center">${x}</th>`;
            }
        }

        return html;
    },

    renderMasterSubHeader: function() {
        var html = ``;

        if(this.master.fechas.length === 0) {
            html=
            `<th>Inventario</th>`;
        }
        else {
            for(var i in this.master.fechas) {
                html+=
                `<th>Inv.</th>
                <th>Req.</th>
                <th style="background: #80cbc4; color: #fff;">Corte</th>
                <th style="background: #3097d199; color: #fff;">Desecho</th>
                <th>Inv. Final</th>`;
            }
        }

        return html;
    },

    renderMasterBody: function()
    {
        var html = ``;
        var next, index, inv_final, o;

        // var readonly = this.edit? "" : "readonly";
        var readonly = "";

        for(var i in this.master.inventario)
        {
            o       = this.master.inventario[i];
            next    = true;
            index   = 1;

            // html +=
            // `<tr>
            //     <td>${o.location}${o.flower_type}${o.variety_color}</td>`;

            html +=
            `<tr>
                <td>${o.skudesc}</td>`;

            while(next)
            {
                if(index in o)
                {

                    if( index%5 === 0 )
                    {
                        inv         = parseInt(o[index-4]);
                        req         = parseInt(o[index-3]);
                        corte       = parseInt(o[index-2]);
                        desecho     = parseInt(o[index-1]);

                        inv_final = (inv+corte)-(req+desecho);

                        o[index] = inv_final;

                        if( (index+1) in o)
                        {
                            o[index+1] = inv_final;
                        }

                        var bck_color = (inv_final < 0) ? "red" : "";

                        html+=
                        `<td style="background: ${bck_color}">
                            <input style="width: 80px;" type="text" value="${inv_final}" readonly/>
                        </td>`;
                    }
                    else if( (index%5) === 4 )
                    {
                        html+=
                        `<td style="background: #3097d199;">
                            <input 
                             style="width: 80px;" type="number" 
                             min=0 
                             value="${o[index]}" 
                             ${readonly} 
                             onchange="Explosion.updateMat(${i}, ${index}, this.value, 'desecho')" />
                        </td>`;
                    }
                    else if( (index%5) === 3 )
                    {
                        html+=
                        `<td style="background: #80cbc4;">
                            <input 
                             style="width: 80px;" 
                             type="number" 
                             min=0 
                             value="${o[index]}" 
                             ${readonly} 
                             onchange="Explosion.updateMat(${i}, ${index}, this.value, 'corte')" />
                        </td>`;
                    }
                    else if( (index%5) === 2 )
                    {
                        html+=
                        `<td>
                            <input style="width: 80px;" type="text" value="${o[index]}" readonly/>
                        </td>`;
                    }
                    else
                    {
                        html+=
                        `<td>
                            <input style="width: 80px;" type="text" value="${o[index]}" readonly/>
                        </td>`;
                    }

                    index++;
                }
                else
                {
                    next = false;
                }
            }

            html += `</tr>`;
        }

        return html;
    },

    updateMat: function(flowerId, column, value, type) {

        value = parseInt(value);
        value = isNaN(value) ? 0 : value;

        this.master.inventario[flowerId][column] = value;

        this.renderMaster();
    },

    renderMaster: function() {

    	var html=
    	`<thead>
    		<tr>
    			<th class="text-center">&nbsp;</th>
    			${this.renderMasterHeader()}
    		</tr>
    	</thead>`;

    	html += 
    	`<tbody>
    		<tr>
    			<th>Flor</th>
    			${this.renderMasterSubHeader()}
    		</tr>
    		${this.renderMasterBody()}
    	</tbody>`;

    	$("#step4Table").html(html);

    },

    dd: function() {

        var self = this;

        loader.mensaje("Generando Master... :')");
        loader.show();
        $.post('app/planner/dd', self.master, function(response) {
            var link        = document.createElement('a');
                link.href   = "/storage/master/"+response.data.file;
                link.click();
        }, 'json')
        .always(function() {
            loader.hide();
        });
    },

	insertFlower: function(flower, isOpen) {

		var key = null;

		// console.log(flower);

		this.required.forEach(function(item, index) {

			if( item.location 		=== flower.location && 
				item.flower_type 	=== flower.flower_type && 
				item.variety_color 	=== flower.variety_color ) {

				key = index;

			}

		});

		if(key !== null) {

			if(isOpen == 1)
				this.required[key].opened 	+= flower.stem_count * flower.bunch_qty;
			else
				this.required[key].locked 	+= flower.stem_count * flower.bunch_qty;

		}
		else {

			// temporal
			// this.inventory.push({
			// 	...flower,
			// 	quantity: 1000,
			// 	quantity_locked: 0,
			// 	quantity_opened: 0,
			// 	quantity_after_locked: 0,
			// });
			///

			flower.opened 	= 0;
			flower.locked 	= 0;

			if(isOpen == 1)
				flower.opened 	+= flower.stem_count * flower.bunch_qty;
			else
				flower.locked 	+= flower.stem_count * flower.bunch_qty;

			this.required.push(flower);

		}

	},

	editRecipesOpen: function(invIndex) {

		var self = this;

		self.recipeActual = 1;
		self.recipesToEdit = [];

		// hide explosion modal
		self.$modal.modal("hide");

		this.inventoryGroupIndex = invIndex;

		// get the inv
		var invFlower = self.inventoryGroup[invIndex];

		// find orders with the flower type
		self.data.forEach(function(order, orderIndex) {

			order.cases.forEach(function(recipe, recipeIndex) {
				if(recipe.isOpen) {

					for(var index in recipe.flowers) {

						var flower = recipe.flowers[index];

						if(flower.flower_type === invFlower.flower_type) {

							recipeClon = $.extend(true, {}, recipe);

							recipeClon.order = {
								ordenid 			: order.ordenid,
								client 				: order.client,
								sun_valley_order 	: order.sun_valley_order,
							};

							recipeClon.originalBunchQty = 0;

							self.recipesToEdit.push(recipeClon);
							break;

						}

					}

				}
			});

		});

		////////////////////////////////////////////////////////////////////
		var html = "", total_required = 0;

		self.required.forEach(function(req) {
			if(req.flower_type === invFlower.flower_type) {
				total_required += req.opened;
			}
		});

		html+=
			`<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>Flower type</th>
						<th>Requerido</th>
						<th>Inventario</th>
						<th>Porcentaje</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>${invFlower.flower_text}</td>
						<td>${total_required}</td>
						<td>${invFlower.quantity}</td>
						<td>100%</td>
					</tr>`;

		invFlower.variety_color.forEach(function(vc, vcIndex) {

			var requeridas 	= 0;
			var styles 		= "";

			self.required.forEach(function(req, reqIndex) {
				if(
					req.flower_type === invFlower.flower_type && 
					req.variety_color === vc.variety_color
				  ) {
					requeridas += req.opened;
				}
			});

			if(requeridas > vc.quantity) {
				styles = "style=\"background-color: red;color: #fff;\"";
			}

			var rowPorcent = Math.ceil((parseInt(vc.quantity) * 100) / parseInt(invFlower.quantity));

			html+=
			`<tr ${styles}>
				<td>Color: ${vc.variety_color_text}</td>
				<td>${requeridas}</td>
				<td>${vc.quantity}</td>
				<td>${rowPorcent}%</td>
			</tr>`;

		});

		html+= `</tbody></table>`

		$("#flowerTypeToEditTable").html(html);
		///////////////////////////////////////////////////////////////////

		$("#type_flower_title").html(invFlower.flower_type);
		$("#recipeActual").html(self.recipeActual);
		$("#recipesTotalEdit").html(self.recipesToEdit.length);

		self.fillRecipeToEdit();

		setTimeout(function() { $("#editRecipesOpen").modal("show"); }, 500);
	},

	editRecipesOpenOld: function(flower_type) {

		var self = this;
		
		self.recipesToEdit 	= [];
		self.recipeActual 	= 1;

		// hide explosion modal
		self.$modal.modal("hide");

		// find on orders (data)
		// recipes with this flower type
		self.data.forEach(function(order, orderIndex) {

			order.cases.forEach(function(recipe, recipeIndex) {

				if(recipe.isOpen) {

					// console.log(">> recipe: ", recipe);

					// recipe.flowers.forEach(function(flower) {
					for(var index in recipe.flowers) {

						var flower = recipe.flowers[index];

						if(flower.flower_type === flower_type) {

							// console.log(">> recipe type: ", recipe);

							recipeClon = $.extend(true, {}, recipe);

							recipeClon.order = {
								ordenid 			: order.ordenid,
								client 				: order.client,
								sun_valley_order 	: order.sun_valley_order,
							};

							recipeClon.originalBunchQty = 0;

							self.recipesToEdit.push(recipeClon);
							break;
							// return;

						}
					}
					// });

				}

				// if(recipe.flower_type === flower_type && recipe.isOpen) {
					// console.log(order);
					// recipe.order = {
					// 	ordenid 			: order.ordenid,
					// 	client 				: order.client,
					// 	sun_valley_order 	: order.sun_valley_order,
					// };
					// self.recipesToEdit.push(recipe);
				// }

			});

		});

		// show recipes open
		$("#type_flower_title").html(flower_type);
		$("#recipeActual").html(self.recipeActual);
		$("#recipesTotalEdit").html(self.recipesToEdit.length);

		self.fillRecipeToEdit();

		setTimeout(function() { $("#editRecipesOpen").modal("show"); }, 500);

	},

	nextRecipe: function() {
		var self = this;
		self.recipeActual++;

		if(self.recipeActual > self.recipesToEdit.length)
			self.recipeActual = 1;

		self.fillRecipeToEdit();

		$("#recipeActual").html(self.recipeActual);
	},

	prevRecipe: function() {
		var self = this;
		self.recipeActual--;

		if(self.recipeActual < 1)
			self.recipeActual = self.recipesToEdit.length;

		self.fillRecipeToEdit();

		$("#recipeActual").html(self.recipeActual);
	},

	fillRecipeToEdit: function() {

		var self 	= this;
		var recipe 	= self.recipesToEdit[self.recipeActual-1];
		var html 	= "";

		var originalBunchQty = 0;

		// console.log(recipe);

		$("[name='client']").val(recipe.order.client);
		$("[name='sun_valley_order']").val(recipe.order.sun_valley_order);
		$("[name='box_type']").val(recipe.box_type);
		$("[name='description']").val(recipe.description);
		$("[name='bunches_per_box']").val(recipe.bunches_per_box);
		$("[name='number_of_cases']").val(recipe.number_of_cases);
		$("[name='stems']").val(recipe.stem_per_bunches);

		recipe.flowers.forEach(function(flower, flowerIndex) {

			var total_stem = parseInt(flower.bunch_qty) * parseInt(flower.stem_count);

			html+=
			`
			<tr>
				<td>
					<span data-skunumber=${flowerIndex} >${flower.skunumber}</span><br/>
				</td>>
				<td>
					<input value="${flower.flower_type}" onkeyup="Explosion.selectFlower(this, ${flowerIndex});" />
				</td>
				<td>
					<input value="${flower.variety_color}" onkeyup="Explosion.selectVarietyColor(this, ${flowerIndex});" />
				</td>
				<td>
					<input value="${flower.grade}" onkeyup="Explosion.selectGrade(this, ${flowerIndex});" />
				</td>
				<td>
					<div class="input-group">
						<span class="input-group-addon" style="cursor: pointer;" onclick="Explosion.updateTotalStem(0, ${flowerIndex}, '[data-bunch-qty]');">
							<span class="glyphicon glyphicon-minus"></span>
						</span>
						<input 
							data-bunch-qty
							data-flower-index=${flowerIndex}
							class="form-control"
							style="background-color: #fff; width: 75px;"
							type="number"
							value="${flower.bunch_qty}" 
							readonly />
						<span class="input-group-addon" style="cursor: pointer;" onclick="Explosion.updateTotalStem(1, ${flowerIndex}, '[data-bunch-qty]');">
							<span class="glyphicon glyphicon-plus"></span>
						</span>
					</div>
				</td>
				<td>
					<div class="input-group">
						<span class="input-group-addon" style="cursor: pointer;" onclick="Explosion.updateTotalStem(0, ${flowerIndex}, '[data-stem-count]');">
							<span class="glyphicon glyphicon-minus"></span>
						</span>
						<input 
							data-stem-count
							data-flower-index=${flowerIndex}
							class="form-control"
							style="background-color: #fff; width: 75px;"
							type="number"
							value="${flower.stem_count}" 
							readonly />
						<span class="input-group-addon" style="cursor: pointer;" onclick="Explosion.updateTotalStem(1, ${flowerIndex}, '[data-stem-count]');">
							<span class="glyphicon glyphicon-plus"></span>
						</span>
					</div>
				</td>
				<td>
					<input style="width: 50px;" value=${total_stem} readonly data-total-stem data-flower-index=${flowerIndex} />
				</td>
				<td align=center>
					<button type="button" class="btn btn-xs btn-danger" onclick="Explosion.removeFlower(${flowerIndex});">
						<i class="fa fa-trash-o"></i>
					</button>
				</td>
			</tr>
			`;

			originalBunchQty += parseInt(flower.bunch_qty);
		});

		if(recipe.originalBunchQty == 0) {
			$("#originalBunchQty div").html(originalBunchQty);
			recipe.originalBunchQty = originalBunchQty;
		} 
		else {
			$("#originalBunchQty div").html(recipe.originalBunchQty);
		}

		$("#customBunchQty div").html(originalBunchQty);

		$("#recipeFlowers").html(html);

		this.updateBunchDashboard();
	},

	addFlower: function() {

		var self = this;

		var recipe 		= self.recipesToEdit[self.recipeActual-1];
		var stem_count 	= parseInt(recipe.stem_per_bunches);

		var stem_count_t = stem_count >= 10 ? stem_count : "0"+stem_count;

		// console.log(recipe);

		recipe.flowers.push({
			boxFlower_id 	: null,
			bunch_qty 		: 0,
			flower_type 	: "",
			grade 			: "",
			location 		: "M",
			skudesc 		: "",
			skunumber 		: "M_______"+stem_count_t,
			stem_count 		: stem_count,
			variety_color 	: ""
		});

		self.fillRecipeToEdit();

	},

	removeFlower: function(index) {

		var self = this;

		var recipe 	= self.recipesToEdit[self.recipeActual-1];

		// console.log(recipe.flowers);

		recipe.flowers.splice(index, 1);

		// console.log(recipe.flowers);

		self.fillRecipeToEdit();
	},

	selectFlower: function(ele, index) {

		var self 	= this;
		var recipe 	= self.recipesToEdit[self.recipeActual-1];

		var flowerType = ele.value;

		if(flowerType.length > 3) {
			ele.value = flowerType.slice(0, -1);
		}

		recipe.flowers[index].flower_type = ele.value;

		self.buildSkunumber(index);

	},

	selectVarietyColor: function(ele, index) {

		var self 	= this;
		var recipe 	= self.recipesToEdit[self.recipeActual-1];

		var flowerType = ele.value;

		if(flowerType.length > 3) {
			ele.value = flowerType.slice(0, -1);
		}

		recipe.flowers[index].variety_color = ele.value;

		self.buildSkunumber(index);

	},

	selectGrade: function(ele, index) {

		var self 	= this;
		var recipe 	= self.recipesToEdit[self.recipeActual-1];

		var flowerType = ele.value;

		if(flowerType.length > 1) {
			ele.value = flowerType.slice(0, -1);
		}

		recipe.flowers[index].grade = ele.value;

		self.buildSkunumber(index);

	},

	buildSkunumber: function(index) {

		var self 	= this;
		var recipe 	= self.recipesToEdit[self.recipeActual-1];

		var skunumber 		= recipe.flowers[index].skunumber;

		var flower_type 	= recipe.flowers[index].flower_type;
		var variety_color 	= recipe.flowers[index].variety_color;
		var grade 			= recipe.flowers[index].grade;
		var location 		= recipe.flowers[index].location;
		var stem_count 		= recipe.flowers[index].stem_count+"";


		// console.log(stem_count);

		flower_type 	= (flower_type + "___").substring(0, 3);
		variety_color 	= (variety_color + "___").substring(0, 3);
		grade 			= (grade + "_").substring(0, 1);
		stem_count 		= ("00" + stem_count).slice(-2);

		skunumber = location+flower_type+variety_color+grade+stem_count;

		// console.log(recipe.flowers[index]);
		recipe.flowers[index].skunumber = skunumber;
		recipe.flowers[index].skudesc 	= 'PENDING';

		// self.fillRecipeToEdit();
		$('[data-skunumber="'+index+'"]').html(skunumber);
	},

	updateTotalStem: function(UpDown, index, dataSelector) {

		var self = this;

		var value = $(dataSelector+'[data-flower-index="'+index+'"]').val();
		value = parseInt(value);

		if(UpDown == 0 && value >= 1) {
			value--;
		}
		
		if(UpDown == 1){
			value++;
		}

		$(dataSelector+'[data-flower-index="'+index+'"]').val( value );

		///
		var bunch_qty = parseInt($('[data-bunch-qty][data-flower-index="'+index+'"]').val());
		var stem_count = parseInt($('[data-stem-count][data-flower-index="'+index+'"]').val());

		var total_stem = bunch_qty*stem_count;

		$('[data-total-stem][data-flower-index="'+index+'"]').val( total_stem );

		var recipe = self.recipesToEdit[self.recipeActual-1];

		recipe.flowers[index].bunch_qty = bunch_qty;
		recipe.flowers[index].stem_count = stem_count;

		this.updateBunchDashboard();
		///

	},

	updateBunchQty: function(element, index) {

		var self = this;

		var recipe 	= self.recipesToEdit[self.recipeActual-1];
		var qty = element.value;

		qty = parseInt(qty);
		
		if(isNaN(qty)) {
			element.focus();
			return false;
		}

		recipe.flowers[index].bunch_qty = qty;

		// $('[data-btn-submit]').prop('disabled', true);
		this.updateBunchDashboard();

	},

	updateBunchDashboard: function(flag = false) {

		$('[data-btn-submit]').prop('disabled', true);

		var acumulado = 0;
		var requerido = parseInt($("#originalBunchQty div").html());

		$('[data-bunch-qty]').each(function(key, item) {

			acumulado += parseInt(item.value);

		});

		$("#customBunchQty div").html(acumulado);

		if(acumulado < requerido) {
			$("#customBunchQty").parent().removeClass("label-primary");
			$("#customBunchQty").parent().removeClass("label-danger");
			$("#customBunchQty").parent().addClass("label-warning");
		}
		else if(acumulado > requerido) {
			$("#customBunchQty").parent().removeClass("label-primary");
			$("#customBunchQty").parent().removeClass("label-warning");
			$("#customBunchQty").parent().addClass("label-danger");
		}
		else {
			$("#customBunchQty").parent().removeClass("label-warning");
			$("#customBunchQty").parent().removeClass("label-danger");
			$("#customBunchQty").parent().addClass("label-primary");
			$('[data-btn-submit]').prop('disabled', false);
		}

	},

	saveRecipeEdit: function() {

		var self 	= this;
		var recipe 	= self.recipesToEdit[self.recipeActual-1];
		var save 	= true;
		var newData = [];

		// console.log(recipe);
		// console.log(recipe.flowers);

		for(var index in recipe.flowers) {

			var flower = recipe.flowers[index];

			if( flower.skunumber.includes("_") ) {
				save = false;
				break;
			}

		}

		if(!save) {
			alert("no es posible guardar");
			return false;
		}

		// save post
		simple_loader.fadeIn();
		$.post('app/apiv2/ordenes/recetas/swap/'+recipe.orderbox_id, {
			flowers: recipe.flowers,
		}, function(response) {

			// 1) update table from another .js
			$table.DataTable().ajax.reload( function() {

				// 2) reload second tap from another .js
				DTtable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

					var row = this.data();

					if( ORDERS_IDS.indexOf(row.ordenid) != -1 ) {
						newData.push(row);
					}

				});

				// 3) upload data
				self.data = newData;

				// console.log(self.data);

				simple_loader.fadeOut();
				swal(":)", "La receta fue editada con éxito.", "success");

			}, false );

		}, 'json');

	},

	makeExcel: function() {

		var self = this;

		$.post('app/apiv2/ordenes/explosion', {data: self.required}, function(response) {

			var link = document.createElement("a");
			link.download = '';
			link.href = `http://localhost:8000/${response}`;
			link.click();

		}, 'json');

	},

	setMasterDate: function(i, date) {

		if(date !== "")
			this.data[i].master_date = date;
		else
			this.data[i].master_date = null;

	},

	updateReqOpen: function(input, indexInventory, indexColor) {

		var self = this;
		var indexRequired;
		var item = self.inventoryGroup[indexInventory];

		var requerido_total 	= item.requerido_original;
		var requerido_acumulado = 0;

		item.variety_color.forEach(function(item2, index2) {
			

			self.required.forEach(function(item3, index3) {

				if( item.flower_type === item3.flower_type && 
					item2.variety_color === item3.variety_color ) {

					if(index2 === indexColor) {
						requerido_acumulado += parseInt(input.value) || 0;
						indexRequired = index3;
					}
					else {
						requerido_acumulado += item3.abiertas;
					}
				}

			});

		});

		if(requerido_acumulado > requerido_total) {

			input.focus();
			input.classList.add("input-error");

			$(`[data-porcent="${indexInventory}-${indexColor}"]`).html("--");
		}
		else {

			self.required[indexRequired].abiertas = parseInt(input.value) || 0;

			input.classList.remove("input-error");
			$(`[data-porcent="${indexInventory}-${indexColor}"]`).html( ( (parseInt(input.value) || 0) * 100 ) / requerido_total +"%" );

			if(requerido_acumulado < requerido_total) {

				var diff = requerido_total - requerido_acumulado;
				$(`[data-diff="${indexInventory}"]`).html(diff);

			}
			else {

				$(`[data-diff="${indexInventory}"]`).html("");

			}

		}

	},

	ordenarFechas() {
		this.master.fechas.sort(function(a, b) {
			var d1 = new Date(a);
			var d2 = new Date(b);

			if(d1 < d2) return -1;
			else if(d1 > d2)return 1;
			else return 0;
		});
	},

};

