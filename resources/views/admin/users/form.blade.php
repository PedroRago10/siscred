@extends('adminlte::page')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('title', $subtitle)

@section('content_header')
    <h1>{{$title}}</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fa fa-address-card"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}"><i class="fa fa-address-card"></i> {{ $title }}</a></li>
            <li class="breadcrumb-item active">{{ $subtitle }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $subtitle }}</h3>
        </div>
        <form role="form" method="POST" action="{{ isset($user) ? route('usuarios.update', $user->id) : route('usuarios.store')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {{ isset($user) ? method_field('PATCH') :  method_field('POST') }}

            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="inputName">Nome do usuário</label>
                            <input type="text" name="name" class="form-control" id="inputName" placeholder="Nome do usuário" value="{{ isset($user) ? $user->name : old('name') }}">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="inputEmail">E-mail de login</label>
                            <input type="email" name="email" class="form-control" id="inputEmail" placeholder="teste@teste.com" value="{{ isset($user) ? $user->email : old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label for="inputPassword">Senha</label>
                            <input type="password" name="password" class="form-control" id="inputPassword">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                            <label for="inputPasswordConfirmation">Confirme a senha</label>
                            <input type="password" name="password_confirmation" class="form-control" id="inputPasswordConfirmation">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn-block btn-lg btn-success">Enviar</button>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="/js/main.js?v=0.1"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@stop
