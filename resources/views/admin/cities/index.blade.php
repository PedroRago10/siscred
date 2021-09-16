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

@section('title', $title)

@section('content_header')

    <h1>{{$title}}</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fa fa-address-card"></i> Home</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </nav>

@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ route('cidades.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Adicionar</a>
        </div>
        @if(session()->has('success'))
            <div class="box-body">
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            </div>
        @endif
        <div class="box-body">
            <table class="table table-bordered table-striped data-table">
                <thead>
                <tr>
                    {{-- <th>Data de criação</th> --}}
                    <th>Nome</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cities as $item)
                    <tr>
                        {{-- <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td> --}}
                        <td>{{ $item->name }}</td>
                        <td>{{ $states[$item->state] }}</td>
                        <td class="action" style="display: flex; justify-content: center;">
                            <a href="{{ route('cidades.edit', $item->id) }}" style='margin-right: 1em;' class="btn btn-primary">Editar</a>
                            <form action="{{ route('cidades.destroy', $item->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" style='margin-right: 1em;' type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $cities->links() !!}

        </div>
        <!-- /.box-body -->
    </div>
@stop
@section('js')
<script src="/js/main.js?v=0.1"></script>
@stop