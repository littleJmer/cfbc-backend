<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Centro Floricultor de BC</title>

        <!-- Fonts -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>

            html, body {
                background-color: #fff;
                color: #fff;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .content-login {
              padding: 4rem;
              margin: 0;
              min-height: 100vh;
            }

            .content-login:before {
              content: "";
              position: absolute;
              background: #2962ff;
              width: 100%;
              top: 0;
              left: 0;
              height: 40%;
            }

            label {
              color: #000;
            }

            form {
              padding: 1.2rem 0;
            }

            p {
              color: #000;
            }

            header {
              margin-bottom: 3rem;
            }

            h3 {
              color: #000;
            }

        </style>
    </head>
    <body>

      <div class="content-login">
        <div class="container">
          <div class="row">
            <div class="col-md-offset-4 col-md-4">
              <div class="panel panel-default panel-cfbc">
                <form method="POST" action="{{ route('login') }}">
                  {{ csrf_field() }}

                  <header>
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-12">
                          <h3>Centro Floricultor de BC</h3>
                          <p>Por favor, introduzca correo electrónico y contraseña.</p>
                        </div>
                      </div>
                    </div>
                  </header>
                  
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="">Correo electrónico:</label>
                          <input type="email" id="email" name="email" class="form-control" autofocus required>
                        </div>
                        <div class="form-group">
                          <label for="">Contraseña:</label>
                          <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                          Entrar
                        </button>
                      </div>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

        <!-- <div class="flex-center position-ref full-height">

            <div class="content">

              <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                  {{ csrf_field() }}

                  <div class="form-group">
                      <label for="email" class="col-md-12">E-Mail Address</label>

                      <div class="col-md-12">
                          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="password" class="col-md-12">Password</label>

                      <div class="col-md-12">
                          <input id="password" type="password" class="form-control" name="password" required>
                      </div>
                  </div>

                  <div class="form-group">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

                      <div class="col-md-12 text-right">
                          <button type="submit" class="btn btn-primary">
                              Login
                          </button>
                      </div>
                  </div>
              </form>

            </div>

        </div> -->

    </body>
</html>
