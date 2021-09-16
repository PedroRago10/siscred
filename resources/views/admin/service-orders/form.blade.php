@extends('adminlte::page')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css" rel="stylesheet" />
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

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $subtitle }}</h3>
        </div>
        <form role="form" method="POST" action="{{ isset($serviceOrder) ? route('ordens-de-servico.update', $serviceOrder->id) : route('ordens-de-servico.store')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {{ isset($serviceOrder) ? method_field('PATCH') :  method_field('POST') }}

            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                            <label for="inputName">Ordem de Serviço</label>
                            <input type="text" name="code" class="form-control" id="inputName" placeholder="Ordem de Serviço" value="{{ isset($serviceOrder) ? $serviceOrder->code : old('code') }}">
                            @if ($errors->has('code'))
                                <span class="help-block">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                            {{Form::label('client_id', 'Cliente')}}
                            {{Form::select('client_id', $clients, isset($serviceOrder->client_id) ? $serviceOrder->client_id : null, array('class' => 'form-control select2','name'=>'client_id'))}}
                            @if ($errors->has('client_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('client_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                            {{Form::label('city_id', 'Cidade')}}
                            {{Form::select('city_id', $cities, isset($serviceOrder->city_id) ? $serviceOrder->city_id : null, array('class' => 'form-control select2','name'=>'city_id'))}}
                            @if ($errors->has('city_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('city_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('service_id') ? 'has-error' : '' }}">
                            {{Form::label('service_id', 'Serviço')}}
                            {{Form::select('service_id', $services, isset($serviceOrder->service_id) ? $serviceOrder->service_id : null, array('class' => 'form-control select2','name'=>'service_id'))}}
                            @if ($errors->has('service_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('service_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                            <label for="inputName">Valor</label>
                            <input type="number" required min="0" step=".01" name="amount" class="form-control" id="inputName" placeholder="0,00" value="{{ isset($serviceOrder) ? $serviceOrder->amount : old('amount') }}">
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
                        <div class="form-group {{ $errors->has('displacement') ? 'has-error' : '' }}">
                            <label for="inputName">Deslocamento</label>
                            <input type="number" required min="0" step=".01" name="displacement" class="form-control" id="inputName" placeholder="0,00" value="{{ isset($serviceOrder) ? $serviceOrder->displacement : old('displacement') }}">
                            @if ($errors->has('displacement'))
                                <span class="help-block">
                                <strong>{{ $errors->first('displacement') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('published_at') ? 'has-error' : '' }}">
                            <label for="inputName">Emissão</label>
                            <input type="date" required name="published_at" class="form-control " id="inputName" placeholder="Emissão" value="{{ isset($serviceOrder) ? $serviceOrder->published_at : old('published_at') }}">
                            @if ($errors->has('published_at'))
                                <span class="help-block">
                                <strong>{{ $errors->first('published_at') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('deadline') ? 'has-error' : '' }}">
                            <label for="inputName">Prazo</label>
                            <input type="number" required min="1" name="deadline" class="form-control" id="inputName" placeholder="Prazo" value="{{ isset($serviceOrder) ? $serviceOrder->deadline : old('deadline') }}">
                            @if ($errors->has('deadline'))
                                <span class="help-block">
                                <strong>{{ $errors->first('deadline') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('scheduled_at') ? 'has-error' : '' }}">
                            <label for="inputName">Agendado</label>
                            <input type="date" name="scheduled_at" class="form-control " id="inputName" placeholder="Agendado" value="{{ isset($serviceOrder) ? $serviceOrder->scheduled_at : old('scheduled_at') }}">
                            @if ($errors->has('scheduled_at'))
                                <span class="help-block">
                                <strong>{{ $errors->first('scheduled_at') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('inspection') ? 'has-error' : '' }}">
                            {{Form::label('inspection', 'Vistoria')}}
                            {{Form::select('inspection', [0 => "NÃO", 1 => "SIM"], isset($serviceOrder->inspection) ? $serviceOrder->inspection : null, array('class' => 'form-control','name'=>'inspection'))}}
                            @if ($errors->has('inspection'))
                                <span class="help-block">
                                <strong>{{ $errors->first('inspection') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('report') ? 'has-error' : '' }}">
                            {{Form::label('report', 'Laudo')}}
                            {{Form::select('report', [0 => "NÃO", 1 => "SIM"], isset($serviceOrder->report) ? $serviceOrder->report : null, array('class' => 'form-control','name'=>'report'))}}
                            @if ($errors->has('report'))
                                <span class="help-block">
                                <strong>{{ $errors->first('report') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('delivered_at') ? 'has-error' : '' }}">
                            <label for="inputName">Entrega</label>
                            <input type="date" name="delivered_at" class="form-control " id="inputName" placeholder="Entrega" value="{{ isset($serviceOrder) ? $serviceOrder->delivered_at : old('delivered_at') }}">
                            @if ($errors->has('delivered_at'))
                                <span class="help-block">
                                <strong>{{ $errors->first('delivered_at') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('finished') ? 'has-error' : '' }}">
                            {{Form::label('finished', 'Concluído')}}
                            {{Form::select('finished', [0 => "NÃO", 1 => "SIM"], isset($serviceOrder->finished) ? $serviceOrder->finished : null, array('class' => 'form-control','name'=>'finished'))}}
                            @if ($errors->has('finished'))
                                <span class="help-block">
                                <strong>{{ $errors->first('finished') }}</strong>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="/js/main.js?v=0.1"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.select2-container .select2-selection--single').css({'height': 'auto'});
        });
    </script>

@stop
