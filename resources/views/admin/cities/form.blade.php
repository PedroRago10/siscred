@php
    $states = [
        'AC'=>'Acre',
        'AL'=>'Alagoas',
        'AP'=>'Amapá',
        'AM'=>'Amazonas',
        'BA'=>'Bahia',
        'CE'=>'Ceará',
        'DF'=>'Distrito Federal',
        'ES'=>'Espírito Santo',
        'GO'=>'Goiás',
        'MA'=>'Maranhão',
        'MT'=>'Mato Grosso',
        'MS'=>'Mato Grosso do Sul',
        'MG'=>'Minas Gerais',
        'PA'=>'Pará',
        'PB'=>'Paraíba',
        'PR'=>'Paraná',
        'PE'=>'Pernambuco',
        'PI'=>'Piauí',
        'RJ'=>'Rio de Janeiro',
        'RN'=>'Rio Grande do Norte',
        'RS'=>'Rio Grande do Sul',
        'RO'=>'Rondônia',
        'RR'=>'Roraima',
        'SC'=>'Santa Catarina',
        'SP'=>'São Paulo',
        'SE'=>'Sergipe',
        'TO'=>'Tocantins'
    ];
@endphp

@extends('adminlte::page')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@stop
@section('js')
<script src="/js/main.js?v=0.1"></script>
@stop
@section('title', $subtitle)

@section('content_header')
    <h1>{{$title}}</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fa fa-address-card"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cidades.index') }}"><i class="fa fa-address-card"></i> {{ $title }}</a></li>
            <li class="breadcrumb-item active">{{ $subtitle }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $subtitle }}</h3>
        </div>
        <form role="form" method="POST" action="{{ isset($city) ? route('cidades.update', $city->id) : route('cidades.store')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {{ isset($city) ? method_field('PATCH') :  method_field('POST') }}

            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="inputName">Nome da cidade</label>
                            <input type="text" name="name" class="form-control" id="inputName" placeholder="Nome da cidade" value="{{ isset($city) ? $city->name : old('name') }}">
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
                        <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                            {{Form::label('state', 'Estado')}}
                            {{Form::select('state', $states, isset($city->state) ? $city->state : null, array('class' => 'form-control','name'=>'state'))}}
                            @if ($errors->has('state'))
                                <span class="help-block">
                                <strong>{{ $errors->first('state') }}</strong>
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
