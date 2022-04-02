<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AWS S3</title>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css" />

    <style>
        html, body{
            background-color: #ccc;
        }
    </style>
</head>
<body>

<div class="container mt-3">
    <div class="row">
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>
        @endif

        <div class="col-lg-12">
            <div id="myModal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Crear Post</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{route('posts.store')}}" enctype="multipart/form-data">
                        @csrf
                       <div class="form-group">
                           <label>Nombre del Post</label>
                           <input class="form-control" autocomplete="off" type="text" name="name">


                           <div class="invalid-feedback d-block">
                            @foreach ($errors->get('name') as $error)
                                 {{ $error }}
                             @endforeach
                         </div>
                       </div>

                        <div class="form-group">
                           <label>Descripción</label>
                           <input class="form-control" autocomplete="off"  type="text" name="description">

                           <div class="invalid-feedback d-block">
                            @foreach ($errors->get('description') as $error)
                                 {{ $error }}
                             @endforeach
                         </div>
                       </div>

                       <div class="form-group">
                        <label>Imagen</label>
                        <input class="form-control"  type="file" name="image">

                        <div class="invalid-feedback d-block">
                            @foreach ($errors->get('image') as $error)
                                 {{ $error }}
                             @endforeach
                         </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Guardar</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>

                </form>
                  </div>
                </div>
              </div>


            <button id="btn-post" type="button" class="btn btn-primary">Nuevo Post</button>

        </div>

        @foreach ($posts as $post)
            <div>
                
                <div class="card" style="width: 18rem;">
                    <img src="{{Storage::disk('s3')->url($post->image_path)}}" class="card-img-top" alt="...">
                    
                    <div class="card-body">
                      <h5 class="card-title">{{$post->name}}</h5>
                      <p class="card-text">{{$post->description}}</p>

                      <form method="post" action="{{route('posts.destroy', $post->id)}}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                        
                    </form>

                    </div>

                    <div class="card-footer">
                        {{$post->created_at}}
                      </div>

                </div>


            </div>
        @endforeach


    </div>
</div>

    
      <!-- Bootstrap JS, jquery y popper compilado -->
      <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>

      <script type="text/javascript">
        //Mantener modal abierto si hay errores de validación.
        @if (count($errors) > 0)
            $('#myModal').modal('show');
        @endif
        </script>

      <script>
          $(document).ready(function() {
             //Evitar que el modal se bloquee.
            $("#myModal").prependTo("body");


            $("#btn-post").click(function(){
                
                $('#myModal').modal('show')


            })
          });
      </script>
</body>
</html>