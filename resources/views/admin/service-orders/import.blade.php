@extends('adminlte::page')

@section('css')
    <style type="text/css">
        .area-upload{
            box-shadow: 0 5px 20px rgba(0,0,0,.2);
            margin: 20px auto;
            padding: 20px;
            box-sizing: border-box;
            
            width: 100%;
            max-width: 700px;
            position: relative;
        }

        #enviar-txt{
            margin: 20px auto;
            padding: 10px;
            width: 100%;
            max-width: 700px;
            position: relative;
        }

        .area-upload label.label-upload{
            border: 2px dashed #0d8acd;
            min-height: 200px;
            text-align: center;
            width: 100%;
            
            display: flex;
            justify-content: center;
            flex-direction: column;
            color: #0d8acd;
            position: relative;
            
            -webkit-transition: .3s all;
            -moz-transition: .3s all;
            -o-transition: .3s all;
            transition: .3s all;
        }

        .area-upload label.label-upload.highlight{
            background-color: #fffdaa;
        }

        .area-upload label.label-upload *{
            pointer-events: none;
        }

        .area-upload input{
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            -webkit-appearance: none;
            opacity: 0;
        }

        .area-upload .lista-uploads .barra{
            background-color: #e6e6e6;
            margin: 10px 0;
            width: 100%;
            position: relative;
        }

        .area-upload .lista-uploads .barra .fill{
            background-color: #a1f7ff;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            min-width: 0;
            -webkit-transition: .2s all;
            -moz-transition: .2s all;
            -o-transition: .2s all;
            transition: .2s all;
        }

        .area-upload .lista-uploads .barra.complete .fill{
            background-color: #bcffdf;
        }

        .area-upload .lista-uploads .barra .text{
            z-index: 10;
            text-align: center;
            padding: 3px 5px;
            box-sizing: border-box;
            position: relative;
            width: 100%;
            color: black;
            font-size: 12px;
        }
        .area-upload .lista-uploads .barra .text a{
            color: black;
            font-weight: bold;
        }

        .area-upload .lista-uploads .barra.error .fill{ 
            background-color: #c02929;
            color: white;
            min-width: 100%;
        }

        .area-upload .lista-uploads .barra.error .text{
            color: white;
        }

    </style>
@stop

@section('title', $subtitle)

@section('content_header')
    <h1>{{$title}}</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fa fa-address-card"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ordens-de-servico.index') }}"><i class="fa fa-address-card"></i> {{ $title }}</a></li>
            <li class="breadcrumb-item active">{{ $subtitle }}</li>
        </ol>
    </nav>
@stop

@if(isset($error))
    <script type="text/javascript">
        alert( '{{$error}}' );
    </script>
@endif

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $subtitle }}</h3>
        </div>

        <form role="form" method="POST" action="{{route('ordens-de-servico.importTxt')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}

            <div class="area-upload">
                <label for="upload-file" class="label-upload">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <div class="texto">Clique ou arraste o arquivo</div>
                </label>
                <input type="file" accept="plain/text" name="txt[]" id="upload-file" multiple/>
                
                <div class="lista-uploads">
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" id="enviar-txt" class="btn-block btn-lg btn-success">Enviar</button>
            </div>
        </form>
    </div>

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="/js/main.js?v=0.1"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.select2-container .select2-selection--single').css({'height': 'auto'});
        });

        let drop_ = document.querySelector('.area-upload #upload-file');
        drop_.addEventListener('dragenter', function(){
            document.querySelector('.area-upload .label-upload').classList.add('highlight');
        });
        drop_.addEventListener('dragleave', function(){
            document.querySelector('.area-upload .label-upload').classList.remove('highlight');
        });
        drop_.addEventListener('drop', function(){
            document.querySelector('.area-upload .label-upload').classList.remove('highlight');
        });

        document.querySelector('#upload-file').addEventListener('change', function() {
            var files = this.files;
            for(var i = 0; i < files.length; i++){
                var info = validarArquivo(files[i]);
                
                //Criar barra
                var barra = document.createElement("div");
                var fill = document.createElement("div");
                var text = document.createElement("div");
                barra.appendChild(fill);
                barra.appendChild(text);
                
                barra.classList.add("barra");
                fill.classList.add("fill");
                text.classList.add("text");
                
                if(info.error == undefined){
                    text.innerHTML = info.success;
                }else{
                    text.innerHTML = info.error;
                    barra.classList.add("error");
                }
                
                //Adicionar barra
                document.querySelector('.lista-uploads').appendChild(barra);
            };
        });

        function validarArquivo(file){

            // Tipos permitidos
            var mime_types = [ 'text/plain' ];
            
            // Validar os tipos
            if(mime_types.indexOf(file.type) == -1) {
                return {"error" : "O arquivo " + file.name + " não permitido"};
            }

            // Apenas 2MB é permitido
            if(file.size > 2*1024*1024) {
                return {"error" : file.name + " ultrapassou limite de 2MB"};
            }

            // Se der tudo certo
            return {"success": file.name};
        }
    </script>
@stop
