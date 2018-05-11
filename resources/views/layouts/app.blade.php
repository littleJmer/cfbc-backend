<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="32yy5ghPTjEHWQR-qRtRB_HXoSfst3X2eO_3QBhRA6Q" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CFBC</title>

    <style>#loading-overlay{width:100%;height:100%;overflow:hidden;display:flex;justify-content: center;
    flex-direction: column;position:fixed;z-index:99999;background:#2962ff;top:0;left:0;bottom:0;right:0}.sk-cube-grid{width:44px;height:44px;margin:0 auto;}.sk-cube-grid .sk-cube{width:33%;height:33%;background-color:#fff;float:left;-webkit-animation:sk-cubeGridScaleDelay 1.3s infinite ease-in-out;animation:sk-cubeGridScaleDelay 1.3s infinite ease-in-out}.sk-cube-grid .sk-cube1{-webkit-animation-delay:.2s;animation-delay:.2s}.sk-cube-grid .sk-cube2{-webkit-animation-delay:.3s;animation-delay:.3s}.sk-cube-grid .sk-cube3{-webkit-animation-delay:.4s;animation-delay:.4s}.sk-cube-grid .sk-cube4{-webkit-animation-delay:.1s;animation-delay:.1s}.sk-cube-grid .sk-cube5{-webkit-animation-delay:.2s;animation-delay:.2s}.sk-cube-grid .sk-cube6{-webkit-animation-delay:.3s;animation-delay:.3s}.sk-cube-grid .sk-cube7{-webkit-animation-delay:0;animation-delay:0}.sk-cube-grid .sk-cube8{-webkit-animation-delay:.1s;animation-delay:.1s}.sk-cube-grid .sk-cube9{-webkit-animation-delay:.2s;animation-delay:.2s}@-webkit-keyframes sk-cubeGridScaleDelay{0%,100%,70%{-webkit-transform:scale3D(1,1,1);transform:scale3D(1,1,1)}35%{-webkit-transform:scale3D(0,0,1);transform:scale3D(0,0,1)}}@keyframes  sk-cubeGridScaleDelay{0%,100%,70%{-webkit-transform:scale3D(1,1,1);transform:scale3D(1,1,1)}35%{-webkit-transform:scale3D(0,0,1);transform:scale3D(0,0,1)}}
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-42274201-3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-42274201-3');
    </script>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,600,700" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

</head>
<body>

    <div id="simple_loader"><figure><img src="{{ asset('img/loader.gif') }}" alt="loading..."></figure></div>
    
    <div id="loading-overlay">
        <div class="sk-cube-grid">
            <div class="sk-cube sk-cube1"></div>
            <div class="sk-cube sk-cube2"></div>
            <div class="sk-cube sk-cube3"></div>
            <div class="sk-cube sk-cube4"></div>
            <div class="sk-cube sk-cube5"></div>
            <div class="sk-cube sk-cube6"></div>
            <div class="sk-cube sk-cube7"></div>
            <div class="sk-cube sk-cube8"></div>
            <div class="sk-cube sk-cube9"></div>
        </div>
        <h2 style="text-align: center; color: #fff" id="mensaje"></h2>
    </div>


    <div class="main-wrap">

        <!-- manageWindow -->
        <div id="manageWindow">
            <div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Nombre de producción</label>
                            <input type="text" name="keyname_production" class="form-control" placeholder="____" value="" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Número de Empleados</label>
                            <input type="number" min=1 onchange="checkScore();" onfocusout="minZero(this, this.value)" class="form-control" name="keynumero_de_empleados" value=8 />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Horas por Empleado</label>
                            <input type="number" min=1 onchange="checkScore();" onfocusout="minZero(this, this.value)" class="form-control" name="keyhoras_de_empleados" value=8 />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Número de Cajas</label>
                            <input type="number" class="form-control" name="keynumero_de_cajas" readonly />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Número de Bonches</label>
                            <input type="number" class="form-control" name="keynumero_de_bonches" readonly />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="keylabel_horas_prod"></label>
                            <label id="keylabel_horas_hombre"></label>
                            <label id="keylabel_si_no"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->

        <div id="toggleNav" class="side-content">
            <nav class="site-nav ">
                <header class="nav-head">
                    <a href="#/">
                        <strong class="text-uppercase m-t-0" style="font-size: 30px;">CFBC</strong>
                    </a>
                </header>
                <div class="scrollarea nav-list-container">
                    <div class="scrollarea-content" tabindex="1" style="margin-top: 0px; margin-left: 0px;">
                        <ul class="list-unstyled nav-list clearfix">
                            <li>
                                <div class="nav-list-title">MENU</div>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/ordenesv2">
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                    <span class="name">Ordenesv2</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/ordenes">
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                    <span class="name">Ordenes</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/planes">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <span class="name">Plan</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/inventariov2">
                                    <i class="fa fa-archive" aria-hidden="true"></i>
                                    <span class="name">Inventario</span>
                                </a>
                            </li>
                            <!-- <li>
                                <a class="active" aria-current="true" href="/flores">
                                    <i class="fa fa-leaf" aria-hidden="true"></i>
                                    <span class="name">Flores</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/recetas">
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    <span class="name">Recetas</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/clientes">
                                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                                    <span class="name">Clientes</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/materiales">
                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                    <span class="name">Materiales</span>
                                </a>
                            </li> -->
                            <li class="submenu">
                                <a class="active" aria-current="true" href="javascript:void(0);" data-submenu>
                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                    <span class="name">Configs</span>
                                    <div class="pull-right submenu-indicator">
                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                    </div>
                                </a>
                                <ul>
                                    <li><a href="/config/flower-type">Flower Type</a></li>
                                    <li><a href="/config/color-codes">Color Codes</a></li>
                                    <li><a href="/config/grade-key">Grade Key</a></li>
                                </ul>
                            </li>
                            <!-- <li>
                                <a class="active" aria-current="true" href="/">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                    <span class="name">Ir al Almacen</span>
                                </a>
                            </li> -->
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    <span class="name">Salir</span>
                                </a>
                                <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="scrollbar-container   vertical">
                        <div class="scrollbar" style="width: 4px; margin-left: 10px; height: 263.513px; margin-top: 0px;"></div>
                    </div>
                </div>
            </nav>
        </div>

        <div id="toggleFull" class="main-content">
             @include('eract.header')
            <div class="view-header">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <header class="text-white">
                                <h1 class="h5 title text-uppercase">Importación de Ordenes 2.0</h1>
                            </header>
                        </div>
                    </div>
                </div>
            </div>
            <div class="view-content">
                @yield('content')
            </div>
        </div>
        @yield('modales')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/planificador.js?_=') }}<?=time()?>"></script>
    <script src="{{ asset('js/explosion.js?_=') }}<?=time()?>"></script>
    <script src="https://use.fontawesome.com/e2786d4774.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script src="https://momentjs.com/downloads/moment.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        window.onload=function() {
            document.getElementById("loading-overlay").style.display="none"
        };

        var loader = {
            show: function() {
                document.getElementById("loading-overlay").style.display="flex";
            },
            hide: function() {
                document.getElementById("loading-overlay").style.display="none";
            },
            mensaje: function(mensaje) {
                mensaje = typeof mensaje === "undefined" ? "" : mensaje;
                $("#mensaje").html(mensaje);
            }
        };

        var toggleNav = function() {
            $("#toggleNav").toggleClass( "mini" );
            $("#toggleFull").toggleClass( "full" );
        };

        var simple_loader = $("#simple_loader");

    </script>
    @yield('extrajs')
</body>
</html>
