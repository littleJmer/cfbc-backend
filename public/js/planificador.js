/*
|--------------------------------------------------------------------------
| Planificador
|--------------------------------------------------------------------------
|
| 
*/
var Planificador = Planificador ? Planificador : {

    bug : true,

    inventario  : [],
    ordenes     : [],
    master      : { ordenes:[], inventario:[], fechas:[], name: "" },

    $ordenesT       : $("#recipesTable"),
    $masterT        : $("#masterTable"),
    $fixedColumn    : null,

    edit: true,
    status: 0,
    planner: 0,

    load: function(id)
    {
        var self = this;

        loader.mensaje("Abriendo Planificador... :')");
        loader.show();
        $.get('/app/api/planes/'+id, function(response, status)
        {
            if(status === "success")
            {
                $("#planner_modal").modal("show");

                self.status             = response.data.status;
                self.edit               = false;
                self.ordenes            = response.data.ordenes;
                self.inventario         = response.data.inventario;

                self.master.name        = response.data.name;
                // self.master.fechas      = response.data.fechas;
                // self.master.inventario  = $.extend(true, {}, self.inventario);
                self.planner            = id;

                // self.ordenarFechas();
                // self.render_ordenes();
                // self.renderMaster();
                self.render();
            }

        }, 'json')
        .always(function()
        {
            loader.hide();
        });
    },

    init: function(inventario, ordenes)
    {
        if(this.debug)
            console.log("Planificador inicializado.");

        this.inventario = inventario;
        this.ordenes    = ordenes;

        this.render();
    },

    render: function()
    {
        if(this.debug)
            console.log("Render...");

        this.render_ordenes();
        this.init_master();
    },

    render_ordenes: function()
    {
        var html = "";
        // var readonly = this.edit? "" : "readonly";
        var readonly = "";

        for(var i in this.ordenes)
        {
            var e = this.ordenes[i];

            html+=
            `<tr>
                <td>
                    <span class="text-danger">Sunvalley Order:</span> <small>${e.sun_valley_order}</small> <br>
                    <span class="text-danger">Ship Date:</span> <small>${e.ship_date}</small> <br>
                    <span class="text-danger">Load Date:</span> <small>${e.load_date}</small> <br>
                    <span class="text-danger">Destination Via:</span> <small>${e.destination_via}</small> <br>
                    <span class="text-danger">Client:</span> <small>${e.account}</small> <br>
                </td>
                <td>
                    <input 
                     type="date" 
                     value="${e.master_date}" 
                     ${readonly} 
                     onchange="Planificador.setMasterDate(${i}, this.value);" />
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" onclick="Planificador.removeOrder(${i})">Quitar <i class="fa fa-remove text-danger"></i></a>
                </td>
            </tr>`;
        }
        this.$ordenesT.find("tbody").html(html);
    },

    init_master()
    {
        this.master.fechas = [];
        this.master.ordenes = [];

        for(var i in this.ordenes)
        {
            var o = this.ordenes[i];

            if(o.master_date === null)
                continue;

            /*
            |
            | ﾐ (/‾▿‾)/˚°◦☆
            | 
            | The magic happens here
            |
            */
            this.master.ordenes.push({id: o.id, master_date: o.master_date});

            if( !(this.master.fechas.indexOf(o.master_date) > -1) )
            {
                this.master.fechas.push(o.master_date);
            }
        }

        this.ordenarFechas();

        /*
        |
        | Highest performance for deep copying literal values
        |
        */
        this.master.inventario = $.extend(true, {}, this.inventario);

        /*
        |
        | ＿φ(◎‿ ◎ )
        |
        */
        for(var i in this.master.fechas)
        {
            var index = parseInt(i);
            var fecha = this.master.fechas[i];

            for(var master_florid in this.master.inventario)
            {
                var holder = this.master.inventario[master_florid];

                var start   = ((index+1) - 1) * 5 + 1;
                var finish  = start + 5 - 1;
                var loop    = start;

                while(loop <= finish)
                {
                    holder[loop] = 0;
                    loop++;
                }

                if(start === 1)
                {
                    holder[1] = holder.quantity;
                }

                for(var k in this.ordenes)
                {
                    var ordenOriginal = this.ordenes[k];

                    if(ordenOriginal.master_date === fecha)
                    {
                        if(master_florid in ordenOriginal.flowers)
                        {
                            holder[start+1] += ordenOriginal.flowers[master_florid].qty;
                        }
                    }
                }
            }
        }

        this.renderMaster();
    },

    renderMasterHeader: function()
    {
        var html = ``;
        var self = this;

        $("#pname").html(self.master.name);

        if(this.master.fechas.length === 0)
        {
            html=
            `<th colspan="5" class="text-center">Por favor planifique almenos 1 orden</th>`;
        }
        else
        {
            for(var i in this.master.fechas)
            {
                var x = this.master.fechas[i];

                html+=
                `<th colspan="5" class="text-center">${x}</th>`;
            }
        }

        return html;
    },

    renderMasterSubHeader: function()
    {
        var html = ``;

        if(this.master.fechas.length === 0)
        {
            html=
            `<th>Inventario</th>`;
        }
        else
        {
            for(var i in this.master.fechas)
            {
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

            html +=
            `<tr>
                <td>${o.fullName}</td>`;

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
                            <input style="width: 80px;" type="text" value="${inv_final}" ondblclick="Planificador.changeFlower(${i}, ${index}, this.value);" readonly/>
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
                             onchange="Planificador.updateMat(${i}, ${index}, this.value, 'desecho')" />
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
                             onchange="Planificador.updateMat(${i}, ${index}, this.value, 'corte')" />
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

    setMasterDate: function(i, date)
    {
        if(date !== "")
            this.ordenes[i].master_date = date;
        else
            this.ordenes[i].master_date = null;

        this.init_master();
    },

    updateMat: function(flowerId, column, value, type)
    {
        value = parseInt(value);
        value = isNaN(value) ? 0 : value;

        this.master.inventario[flowerId][column] = value;

        this.renderMaster();
    },

    removeOrder(i)
    {
        var orderId = this.ordenes[i].id;

        $.post("/app/api/resetOrder/"+orderId, function()
        {

        }, "json");

        this.ordenes.splice(i, 1);
        this.render();
    },

    renderMaster()
    {
        var self = this;

        // botonera dependiendo del status
        var btns = "";
        if(self.status == 0)
        {
            btns +=
            `<button 
             class="btn btn-sm btn-success" 
             style="margin: 5px 0;" 
             onclick="Planificador.save();">
                <i class="fa fa-save"></i>&nbsp;Guardar Planeación
            </button>`;
        }
        if(self.status == 1)
        {
            btns +=
            `<button 
             class="btn btn-sm btn-success" 
             style="margin: 5px 0;" 
             onclick="Planificador.shipping();">
                <i class="fa fa-truck"></i>&nbsp;Shipping
            </button>`;
        }
        $("[data-pbuttons]").html(btns);

        var html=
        `<thead>
            <tr>
                <th class="text-center">&nbsp;</th>
                ${this.renderMasterHeader()}
            </tr>
        </thead>`;

        html+=
        `<tbody>
            <tr>
                <th>Flor</th>
                ${this.renderMasterSubHeader()}
            </tr>
            ${this.renderMasterBody()}
        </tbody>`;
        
        this.$masterT.html(html);

    },

    ordenarFechas()
    {
        this.master.fechas.sort(function(a, b)
        {
            var d1 = new Date(a);
            var d2 = new Date(b);

            if(d1 < d2) return -1;
            else if(d1 > d2)return 1;
            else return 0;
        });
    },

    dd: function()
    {
        var self = this;

        loader.mensaje("Generando Master... :')");
        loader.show();
        $.post('app/planner/dd', self.master, function(response)
        {
            var link        = document.createElement('a');
                link.href   = "/storage/master/"+response.data.file;
                link.click();
        }, 'json')
        .always(function()
        {
            loader.hide();
        });
    },

    save: function()
    {
        var self = this;

        var planificador = "PLAN-"+moment().year()+"-"+moment().isoWeek();

        var q = prompt("Por favor define un nombre para esta planeación:", planificador);

        if (q == null || q == "")
        {
            // who care?
            return false;
        } 
        else
        {
            self.master.name = q.trim();

            loader.mensaje("Guardando Planificación..."+self.master.name);
            loader.show();
            $.post('app/planner/save', self.master, function(response)
            {
                alert("Planificación guardada con éxito.");
                $planner_modal.modal("hide");
                DTobj.ajax.reload(null, false);

            }, 'json')
            .always(function()
            {
                loader.hide();
            });

            return false;
        }
    },

    shipping: function()
    {
        var self = this;
        var win = window.open(`http://centrofloricultorbc.com/app/planner/${self.planner}/shipping`, '_blank');
        win.focus()
    },

    changeFlower(flowerId, column, value)
    {
        var recipes_to_change = [];

        // check ordenes con esta florid
        for(var i in this.ordenes)
        {
            var o = this.ordenes[i];

            if(o.master_date === null)
                continue;

            var recetas = o.recipes;

            for(var j in recetas)
            {
                var r = recetas[j];
                var flores = r.flowers;

                if( flowerId in flores )
                {
                    recipes_to_change[r.id] = r;
                }

            }

        }

        if(recipes_to_change.length > 0 )
        {
            //
            console.log(recipes_to_change);

            //
            var html = "";

            for(var index in recipes_to_change)
            {
                var receta = recipes_to_change[index];

                console.log(receta);

                html+=
                `<tr>
                    <td align="center">${receta.id}</td>
                    <td>${receta.recipe}</td>
                    <td>${receta.type}</td>
                    <td>${receta.inmutable}</td>
                    <td>${receta.box_type}</td>
                    <td align="center">
                        <a>Cambiar</a>
                    </td>
                </tr>`;
            }

            $('#table_to_change').find("tbody").html(html);

            // hide modal
            $("#planner_modal").modal("hide");
            setTimeout(function()
            {
                $("#modalchange_flower").modal("show");
            }, 500);

            $('#modalchange_flower').on('hidden.bs.modal', function (e) {
                setTimeout(function()
                {
                    $("#planner_modal").modal("show");
                }, 500);
            });

        }
    },

};
/*-----------------------------------------------------------------------*/