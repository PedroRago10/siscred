@php
    $finished = 0;
    $finishedToDo = 0;
    $reported = 0;
    $reportedToDo = 0;
    $urgentsToDo = 0;
    $urgentsFinished = 0;
    $inspectioned = 0;
    $inspectionToDo = 0;
    $totalIncome = 0.0;
    $receivedIncome = 0.0;
    $totalIncomes = 0;
    $teste = 0;

    foreach ($serviceOrders as $serviceOrder) {
        $totalIncome += $serviceOrder->amount;
        $totalIncome += $serviceOrder->displacement;
        $totalIncomes += 1;

        if ($serviceOrder->finished) {
            $finished += 1;
            $receivedIncome += $serviceOrder->amount;
            $receivedIncome += $serviceOrder->displacement;
        } else {
            $finishedToDo +=1;
        };

        $serviceOrder->limitDate = getWorkingDays($serviceOrder->published_at, $serviceOrder->deadline);
        $serviceOrder->urgent = false;

        
        if (is_null($serviceOrder->delivered_at)) {
            $date = date_create_from_format('d/m/Y', $serviceOrder->limitDate);

            if ($serviceOrder->finished == 0) {
                $start = now();
                $diff = $start->diff($date);
                $daysInHours = ($diff->y * 365 * 24) + ($diff->m * 30 * 24) + ($diff->d * 24) + $diff->h;

                if($daysInHours < 24) {
                    $serviceOrder->urgent = true;

                    if ($serviceOrder->finished == 0) {
                        $urgentsToDo += 1;
                    } else {
                        $urgentsFinished += 1;
                    }
                }
            }
        };



        if ($serviceOrder->report == 1) {
            $reported +=1;
        } else if ($serviceOrder->report == 0) {
            $reportedToDo += 1;
        };

        if ($serviceOrder->inspection == 1) {
            $inspectioned += 1;
        } else if ($serviceOrder->inspection == 0) {
            $inspectionToDo += 1;
        };
    }

    $months = [
        '0'  => 'Todos',
        '1' => 'Janeiro',
        '2' => 'Fevereiro',
        '3' => 'Março',
        '4' => 'Abril',
        '5' => 'Maio',
        '6' => 'Junho',
        '7' => 'Julho',
        '8' => 'Agosto',
        '9' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro'
    ];

    function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }

    function getWorkingDays($startDate, $days){
        $currentDate = $startDate;
        $passedDays = 0;


        while ($passedDays < $days) {
            $currentDate = date('Y-m-d', strtotime($currentDate. ' + 1 days'));
            if(!isWeekend($currentDate)) {
                $passedDays += 1;
            }
        }

        $dataLimit = date('d/m/Y', strtotime($currentDate));

        $dataLimitConvert = implode("-", array_reverse(explode("/", $dataLimit)));
       
        $dataLimit = strtotime($dataLimitConvert . '+1 day');

        return date('d/m/Y', $dataLimit);
    }


@endphp

@extends('adminlte::page')

@section('title', $title)

@section('content_header')
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 15px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0px;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 15px;
    width: 15px;
    left: -1px;
    bottom: 0px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/datatables.min.css"/>

    <h1>{{$title}}</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fa fa-address-card"></i> Home</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col mx-1 rounded-top text-white bg-primary" style="border-radius: .80rem !important;">
                <h5 class="card-title m-3">Vistoriar <span style="font-size: 30px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</span></h5>
                <h1 class="card-text m-3">{{ $inspectionToDo }}</h1>
                <div class="row align-items-center justify-content-between px-3 py-2" style="background-color:#d2cfcf; border-radius: 25px 25px 10px 10px !important;">
                    <h6 class="col font-weight-bold text-success"><small>Realizado: </small>{{ $inspectioned }} ({{ $inspectioned ? intval($inspectioned/($inspectioned+$inspectionToDo)*100) : 0}}%)</h6>
                    <h6 class="col-auto font-weight-bold text-secondary">Descrição</h6>
                </div>
            </div>
            <div class="col mx-1 text-white bg-secondary" style="border-radius: .80rem !important;">
                <h5 class="card-title m-3">Laudos <span style="font-size: 30px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</span></h5>
                <h1 class="card-text m-3">{{ $reportedToDo }}</h1>
                <div class="row align-items-center justify-content-between px-3 py-2" style="background-color:#d2cfcf; border-radius: 25px 25px 10px 10px !important;">
                    <h6 class="col font-weight-bold text-success"><small>Realizado:</small> {{ $reported }} ({{ $reported ? intval($reported/($reported+$reportedToDo)*100) : 0}}%)</h6>
                    <h6 class="col-auto font-weight-bold text-secondary">Descrição</h6>
                </div>
            </div>
            <div class="col mx-1 bg-danger" style="color: #fff !important; border-radius: .80rem !important;">
                <h5 class="card-title m-3">Urgentes <span style="font-size: 30px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</span></h5>
                <h1 class="card-text m-3">{{ $urgentsToDo+$urgentsFinished }}</h1>
                <div class="row align-items-center justify-content-between px-3 py-2" style="background-color:#d2cfcf; border-radius: 25px 25px 10px 10px !important;">
                    <h6 class="col font-weight-bold text-success"> </h6>
                    <h6 class="col-auto font-weight-bold text-secondary"> </h6>
                </div>
            </div>
            <div class="col mx-1 text-white bg-success" style="border-radius: .80rem !important;">
                <h5 class="card-title m-3">Concluir <span style="font-size: 30px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</span></h5>
                <h1 class="card-text m-3">{{ $finishedToDo }}</h1>
                <div class="row align-items-center justify-content-between px-3 py-2" style="background-color:#d2cfcf; border-radius: 25px 25px 10px 10px !important;">
                    <h6 class="col font-weight-bold text-success"><small>Realizado:</small> {{ $finished }} ({{ $totalIncomes ? intval(100-($finishedToDo/$totalIncomes*100)) : 0}}%)</h6>
                    <h6 class="col-auto font-weight-bold text-secondary">Descrição</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="col-4">
            <div class="box-header" style="margin-bottom: 10px;">
                <a href="{{ route('ordens-de-servico.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Adicionar</a>

                <a href="{{ route('ordens-de-servico.import') }}" class="btn btn-primary"><i class="fa fa-arrow-up"></i> Importar O.S</a>
            </div>
        </div>
        @if(session()->has('success'))
            <div class="box-body">
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            </div>
        @endif
        <div class="box-body">
            <div class="col-12">
                @if (isset($years) && (count($years) > 0))
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                {{Form::label('selectYear', 'Selecione o ano')}}
                                {{Form::select('selectYear', $years, isset($year) ? $year : '', array('class' => 'form-control selectYear', 'name'=>'year', 'id' => 'selectYear'))}}
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                {{Form::label('selectMonth', 'Selecione o mês')}}
                                {{Form::select('selectMonth', $months, isset($month) ? $month : '', array('class' => 'form-control selectMonth', 'name'=>'month', 'id' => 'selectMonth'))}}
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                {{Form::label('selectClient', 'Selecione o cliente')}}
                                {{Form::select('selectClient', ['Todos']+$clients, isset($client_id) ? $client_id : '', array('class' => 'form-control selectClient', 'name'=>'client', 'id' => 'selectClient'))}}
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                {{Form::label('selectCity', 'Selecione a cidade')}}
                                {{Form::select('selectCity', ['Todas']+$cities, isset($city_id) ? $city_id : '', array('class' => 'form-control selectCity', 'name'=>'city', 'id' => 'selectCity'))}}
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                {{Form::label('selectService', 'Selecione o serviço')}}
                                {{Form::select('selectService', ['Todas']+$services, isset($service_id) ? $service_id : '', array('class' => 'form-control selectService', 'name'=>'service', 'id' => 'selectService'))}}
                            </div>
                        </div>
                        <div class="col-2" style="display: flex; align-items: center;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <button onclick="filter()" class="btn btn-primary" ><i class="fa fa-plus-circle"></i> Filtrar</button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                    </div>
                    <div class="card-body">
                        <table  id="example1" class="table table-striped table-bordered table-sm table-responsive">
                            <thead>
                                <tr>
                                    {{-- <th>Data de criação</th> --}}
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Cidade</th>
                                    <th>Serviço</th>
                                    <th>Valor</th>
                                    <th>Deslocamento</th>
                                    <th>Total</th>
                                    <th>Emissão</th>
                                    <th>Prazo</th>
                                    <th>Limite</th>
                                    <th>Urgente</th>
                                    <th>Agendado</th>
                                    <th>Vistoria</th>
                                    <th>Laudo</th>
                                    <th>Entrega</th>
                                    <th>Concluído</th>
                                    <th>Editar</th>
                                    <th>Deletar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceOrders as $item)
                                    <tr {{ $item->urgent ? 'class=bg-danger': "" }}>
                                        {{-- <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td> --}}
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->client->name }}</td>
                                        <td>{{ $item->city->name }}</td>
                                        <td>{{ $item->service->name }}</td>
                                        <td>{{ \Cknow\Money\Money::BRL(intval($item->amount * 100 )) }}</td>
                                        <td>{{ \Cknow\Money\Money::BRL(intval($item->displacement * 100 )) }}</td>
                                        <td>{{ \Cknow\Money\Money::BRL(intval(($item->amount + $item->displacement) * 100 )) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</td>
                                        <td>{{ $item->deadline." dias" }}</td>
                                        <td>{{ $item->limitDate }}</td>
                                        <td>{{ $item->urgent ? "Sim" : "Não" }}</td>
                                        <td><?php if(!is_null($item->scheduled_at)) { echo "<input type='date' value=".\Carbon\Carbon::parse($item->scheduled_at)->format('Y-m-d')." onchange='changeOs(".$item->id.", \"s\", this.value)'></input>"; } else { echo "<input type='date' value=00/00/0000 onchange='changeOs(".$item->id.", \"s\", this.value)'></input>"; } ?></td>
                                        <td>{{ ($item->inspection == 1) ? "SIM": "NÃO" }} <label class="switch mx-4" style="top: 4px !important;"><input type="checkbox" onclick="changeOs(<?=$item->id?>, 'i', <?=$item->inspection?>)" {{ ($item->inspection == 1) ? "checked": "" }}><span class="slider round"></span></label></td>
                                        <td>{{ ($item->report == 1) ? "SIM": "NÃO" }} <label class="switch mx-4" style="top: 4px !important;"><input type="checkbox" onclick="changeOs(<?=$item->id?>, 'r', <?=$item->report?>)" {{ ($item->report == 1) ? "checked": "" }}><span class="slider round"></span></label></td>
                                        <td><?php if(!is_null($item->delivered_at)) { echo "<input type='date' value=".\Carbon\Carbon::parse($item->delivered_at)->format('Y-m-d')." onchange='changeOs(".$item->id.", \"d\", this.value)'></input>"; } else { echo "<input type='date' value=00/00/0000 onchange='changeOs(".$item->id.", \"d\", this.value)'></input>"; } ?></td>
                                        <td>{{ ($item->finished == 1) ? "SIM": "NÃO" }} <label class="switch mx-4" style="top: 4px !important;"><input type="checkbox" onclick="changeOs(<?=$item->id?>, 'f', <?=$item->finished?>)" {{ ($item->finished == 1) ? "checked": "" }}><span class="slider round"></span></label></td>
                                        <td class="action">
                                            <a href="{{ route('ordens-de-servico.edit', $item->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                        </td>
                                        <td class="action">
                                            <a class="btn btn-sm btn-danger" onclick="deleteOs(<?=$item->id?>)">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            {{-- {!! $serviceOrders->links() !!} --}}

        </div>
        <!-- /.box-body -->
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script>
    function deleteOs(id) {
        dataDel = {'_token': '<?php echo csrf_token() ?>', '_method': 'DELETE'};
        if (window.confirm("Você realmente apagar a OS?")) {
            $.ajax({
               type:'POST',
               url:'ordens-de-servico/'+id,
               data:dataDel,
               success:function(data) {
                   $("#msg").html(data.msg);
               },
               error:function(error) {
                   console.log(error);
               }
           });
           document.location.reload(true);
        }
    }

    function changeOs(id, type, value) {
        dataChange = {'_token': '<?php echo csrf_token() ?>', '_method': 'PATCH', 'type': type, 'value': value};
        $.ajax({
            type:'PATCH',
            url:'ordens-de-servico/updateOS/'+id+'/'+type+'/'+value,
            data:dataChange,
            success:function(data) {
                $("#msg").html(data.msg);
                document.location.reload(true);
            },
            error:function(error) {
                alert('Ocorreu um erro.')
                console.log(error);
            }
        });
    }
</script>

<!-- DataTables  & Plugins -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="/js/main.js?v=0.1"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/datatables.min.js"></script>
<script>
    let dataTable = $("#example1").DataTable({
        "order": [[ 7, "desc" ]],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "responsive": true, "lengthChange": true, "autoWidth": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "language": {
            "decimal":        "",
            "emptyTable":     "No data available in table",
            "info":           "Mostrando _START_ à _END_ de _TOTAL_ registros",
            "infoEmpty":      "Mostrando 0 à 0 de 0 registros",
            "infoFiltered":   "(filtrado de _MAX_ registros)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Mostrar _MENU_ registros",
            "loadingRecords": "Carregando...",
            "processing":     "Processando...",
            "search":         "Pesquisar:",
            "zeroRecords":    "No matching records found",
            "paginate": {
                "first":      "Primeira",
                "last":       "Última",
                "next":       "Próxima",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

</script>

<script>
    function filter () {
        var year = $('#selectYear').val();
        var month = $('#selectMonth').val();
        var client = $('#selectClient').val();
        var city = $('#selectCity').val();
        var service = $('#selectService').val();

        var url = '/ordens-de-servico?';
        url += ('&year='+year);

        if (month !== '0') {
            url += ('&month='+month);
        }

        if (client !== '0') {
            url += ('&client_id='+client);
        }

        if (city !== '0') {
            url += ('&city_id='+city);
        }

        if (service !== '0') {
            url += ('&service_id='+service);
        }

        window.location = url; // redirect

        return false;
    };
</script>
@endsection
