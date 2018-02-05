<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
    <title>CFBC</title>
    
    <style>#loading-overlay{width:100%;height:100%;overflow:hidden;display:flex;justify-content: center;
    flex-direction: column;position:fixed;z-index:99999;background:#2962ff;top:0;left:0;bottom:0;right:0}.sk-cube-grid{width:44px;height:44px;margin:0 auto;}.sk-cube-grid .sk-cube{width:33%;height:33%;background-color:#fff;float:left;-webkit-animation:sk-cubeGridScaleDelay 1.3s infinite ease-in-out;animation:sk-cubeGridScaleDelay 1.3s infinite ease-in-out}.sk-cube-grid .sk-cube1{-webkit-animation-delay:.2s;animation-delay:.2s}.sk-cube-grid .sk-cube2{-webkit-animation-delay:.3s;animation-delay:.3s}.sk-cube-grid .sk-cube3{-webkit-animation-delay:.4s;animation-delay:.4s}.sk-cube-grid .sk-cube4{-webkit-animation-delay:.1s;animation-delay:.1s}.sk-cube-grid .sk-cube5{-webkit-animation-delay:.2s;animation-delay:.2s}.sk-cube-grid .sk-cube6{-webkit-animation-delay:.3s;animation-delay:.3s}.sk-cube-grid .sk-cube7{-webkit-animation-delay:0;animation-delay:0}.sk-cube-grid .sk-cube8{-webkit-animation-delay:.1s;animation-delay:.1s}.sk-cube-grid .sk-cube9{-webkit-animation-delay:.2s;animation-delay:.2s}@-webkit-keyframes sk-cubeGridScaleDelay{0%,100%,70%{-webkit-transform:scale3D(1,1,1);transform:scale3D(1,1,1)}35%{-webkit-transform:scale3D(0,0,1);transform:scale3D(0,0,1)}}@keyframes  sk-cubeGridScaleDelay{0%,100%,70%{-webkit-transform:scale3D(1,1,1);transform:scale3D(1,1,1)}35%{-webkit-transform:scale3D(0,0,1);transform:scale3D(0,0,1)}}
    </style>
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,600,700" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
</head>
<body>
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
        <div class="side-content">
            <nav class="site-nav ">
                <header class="nav-head">
                    <a href="#/">
                        <strong class="h4 text-uppercase m-t-0">CFBC</strong>
                    </a>
                </header>
                <div class="scrollarea nav-list-container">
                    <div class="scrollarea-content" tabindex="1" style="margin-top: 0px; margin-left: 0px;">
                        <ul class="list-unstyled nav-list clearfix">
                            <li>
                                <div class="nav-list-title">MENU</div>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/ordenes">
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                    <span class="name">Ordenes</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/recetas">
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    <span class="name">Recetas</span>
                                </a>
                            </li>
                            <li>
                                <a class="active" aria-current="true" href="/">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                    <span class="name">Ir al Almacen</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="scrollbar-container   vertical">
                        <div class="scrollbar" style="width: 4px; margin-left: 10px; height: 263.513px; margin-top: 0px;"></div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="main-content">
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
    <script src="https://use.fontawesome.com/e2786d4774.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        (function($){
            $.fn.serializeObject = function(){

                var self = this,
                    json = {},
                    push_counters = {},
                    patterns = {
                        "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                        "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                        "push":     /^$/,
                        "fixed":    /^\d+$/,
                        "named":    /^[a-zA-Z0-9_]+$/
                    };


                this.build = function(base, key, value){
                    base[key] = value;
                    return base;
                };

                this.push_counter = function(key){
                    if(push_counters[key] === undefined){
                        push_counters[key] = 0;
                    }
                    return push_counters[key]++;
                };

                $.each($(this).serializeArray(), function(){

                    // skip invalid keys
                    if(!patterns.validate.test(this.name)){
                        return;
                    }

                    var k,
                        keys = this.name.match(patterns.key),
                        merge = this.value,
                        reverse_key = this.name;

                    while((k = keys.pop()) !== undefined){

                        // adjust reverse_key
                        reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                        // push
                        if(k.match(patterns.push)){
                            merge = self.build([], self.push_counter(reverse_key), merge);
                        }

                        // fixed
                        else if(k.match(patterns.fixed)){
                            merge = self.build([], k, merge);
                        }

                        // named
                        else if(k.match(patterns.named)){
                            merge = self.build({}, k, merge);
                        }
                    }

                    json = $.extend(true, json, merge);
                });

                return json;
            };
        })(jQuery);

        window.onload=function(){document.getElementById("loading-overlay").style.display="none"}

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

    </script>
    @yield('extrajs')
</body>
</html>
