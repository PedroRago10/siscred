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
            <li class="breadcrumb-item"><a href="{{ route('despesas.index') }}"><i class="fa fa-address-card"></i> {{ $title }}</a></li>
            <li class="breadcrumb-item active">{{ $subtitle }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $subtitle }}</h3>
        </div>
        <form role="form" method="POST" action="{{ isset($expense) ? route('despesas.update', $expense->id) : route('despesas.store')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {{ isset($expense) ? method_field('PATCH') :  method_field('POST') }}

            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label for="inputDate">Data</label>
                            <input type="date" name="date" class="form-control" id="inputDate" placeholder="Emissão" value="{{ isset($expense) ? $expense->date : old('date') }}">
                            @if ($errors->has('date'))
                                <span class="help-block">
                                <strong>{{ $errors->first('date') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                            <label for="inputName">Valor</label>
                            <input type="text" name="amount" class="form-control" id="inputName" placeholder="Valor" value="{{ isset($expense) ? $expense->amount : old('amount') }}">
                            @if ($errors->has('amount'))
                                <span class="help-block">
                                <strong>{{ $errors->first('amount') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="inputDesc">Descrição</label>
                            <textarea name="description" class="form-control" id="inputDesc" placeholder="Descrição da despesa" rows="4">{{ isset($expense) ? $expense->description : old('description') }}</textarea>
                            @if ($errors->has("description"))
                                <span class="alert alert-danger">
                                    <strong>{{ $errors->first("description") }}</strong>
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
<script src="/js/main.js?v=0.1"></script>
@stop