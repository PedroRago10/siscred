@php
    $monthsIncomes = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
    $monthsExpenses = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
    $totalIncome = 0;
    $totalExpense = 0;
    $topCities = [];
    $valueCities = [];
    $topServices = [];
    $valueServices = [];
    $topClients = [];
    $valueClients = [];
    $qtdOsMonths = [];
    $monthOsMonths = [];
    $countOsMonth = 0;

    foreach ($incomes as $key => $income) {
        $monthsIncomes[intval($key)-1] = $income;
        $totalIncome += $income;
    }

    foreach ($expenses as $key => $expense) {
        $monthsExpenses[intval($key)-1] = $expense;
        $totalExpense += $expense;
    }

    foreach ($cities as $key => $city) {
        $topCities[intval($key)] = $city->name;
        $valueCities[intval($key)] = $city->total;
    }

    foreach ($services as $key => $service) {
        $topServices[intval($key)] = $service->name;
        $valueServices[intval($key)] = $service->total;
    }

    foreach ($clients as $key => $client) {
        $topClients[intval($key)] = $client->name;
        $valueClients[intval($key)] = $client->total;
    }

    $months = [
        'Janeiro',
        'Fevereiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro'
    ];

    foreach ($osMonths as $os) {

        foreach ($months as $key => $m) {
            if ($os->month == $m) {
                $monthOsMonths[intval($key)] = $os->month;
                $qtdOsMonths[intval($key)] = $os->qtd_os;
                if ($month && $month == ($key+1)){
                    $countOsMonth += $os->qtd_os;
                } else if ($month == 0) {
                    $countOsMonth += $os->qtd_os;
                }
            } else {
                $monthOsMonths[intval($key)] = $m;
                if(!isset($qtdOsMonths[intval($key)]))
                $qtdOsMonths[intval($key)] = 0;
            }
        }
    }
    $countOsMonth == 0 ? $countOsMonth = 1 : '';
    $lucro = (intval($totalIncome * 100)-intval($totalExpense * 100))/$countOsMonth;
@endphp

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
@if(session()->has('success'))
        <div class="box-body">
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        </div>
    @endif
<div class="row my-3">
    <div class="form-group col-12 col-md-3">
        <label for="">Selecione o ano</label>
        <select class="form-control selectYear" name="month" id="selectYear">
        @foreach ($years as $y)
            <option value="{{$y}}" {{ $y == $year ? 'selected' : ''}}>{{$y}}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group col-12 col-md-3">
        <label for="">Selecione o mês</label>
        <select class="form-control selectMonth" name="month" id="selectMonth">
        <option value="30">Todos</option>
        <?php $numMonth = 1 ?>
        @foreach ($months as $m)
            <option value="{{$numMonth}}" {{ $numMonth == $month ? 'selected' : ''}}>{{$m}}</option>
            <?php $numMonth += 1 ?>
        @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6 mt-3">
        <div class="row mt-3">
            <div class="col mx-1 card bg-light">
                <h5 class="card-title m-3">Faturamento projetado <button class="btn btn-primary px-2 py-0" style="font-size: 20px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</button></h5>
                <h3 class="card-text font-weight-bold m-3">{{ \Cknow\Money\Money::BRL(intval($totalIncome * 100)) }}</h3>
            </div>
            <div class="col mx-1 card bg-light">
            <h5 class="card-title mt-3 ml-3">Realizado no período</h5>
            <ul class="nav nav-pills ml-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active p-1 mt-2" data-toggle="pill" href="#servs">Serviços</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-1 mt-2" data-toggle="pill" href="#deslocs">Deslocamentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-1 mt-2" data-toggle="pill" href="#total">Total</a>
                </li>
            </ul>
                <div class="tab-content">
                    <div id="servs" class="tab-pane active">
                        <h3 class="card-text font-weight-bold m-3">{{ \Cknow\Money\Money::BRL(intval(($serviceTotal) * 100)) }}</h3>
                    </div>
                    <div id="deslocs" class="tab-pane fade">
                        <h3 class="card-text font-weight-bold m-3">{{ \Cknow\Money\Money::BRL(intval(($displacementTotal) * 100)) }}</h3>
                    </div>
                    <div id="total" class="tab-pane fade">
                        <h3 class="card-text font-weight-bold m-3">{{ \Cknow\Money\Money::BRL(intval(($serviceTotal + $displacementTotal) * 100)) }}</h3>
                    </div>
                </div>
                <button class="btn btn-primary px-2 py-0" style="font-size: 20px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</button>
            </div>
        </div>
        <div class="row my-3">
            <div class="col mx-1 card bg-light">
                <h5 class="card-title m-3">Despesas no período <button class="btn btn-primary px-2 py-0" style="font-size: 20px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</button></h5>
                <h3 class="card-text font-weight-bold m-3">{{ \Cknow\Money\Money::BRL(intval($totalExpense * 100)) }}</h3>
            </div>
            <div class="col mx-1 card bg-light">
                <h5 class="card-title m-3">Lucro médio por O.S. <button class="btn btn-primary px-2 py-0" style="font-size: 20px; font-weight: 700; position: absolute; right: 15px; top: 8px;">$</button></h5>
                <h3 class="card-text font-weight-bold m-3">{{ \Cknow\Money\Money::BRL(intval($lucro)) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 text-center px-5">
        <div>
            <canvas id="lineChart" width="400" height="400"></canvas>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 pr-5 mb-5">
        <div style="width: 100%; height: 50%;">
            <canvas id="barChart" width="400" height="100"></canvas>
        </div>
    </div>
</div>
<div class="row justify-content-md-center pb-5">
    <div class="col-12 col-md-2 text-center">
        <canvas id="cityChart" width="500" height="500"></canvas>
        <small><span id="cityLegend"></span></small>
    </div>
    <div class="col-12 col-md-2 text-center">
        <canvas id="servicesChart" width="500" height="200"></canvas>
        <small><span id="servLegend"></span></small>
    </div>
    <div class="col-12 col-md-2 text-center">
        <canvas id="clientsChart" width="500" height="200"></canvas>
        <small><span id="cliLegend"></span></small>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/main.js?v=0.1"></script>

<script>
    $(function() {
        // bind change event to select
        $('#selectYear').on('change', function() {
            var val = $(this).val();

            if (val === "default") {
                window.location = '/relatorios/geral';
            } else {
                var url = '/relatorios/geral?year=' + val; // get selected value
                if (url) { // require a URL
                    window.location = url; // redirect
                }
            }

            return false;
        });

        $('#selectMonth').on('change', function() {
            var val = $(this).val();
            var year = $('#selectYear').val();

            if (val === "default") {
                window.location = '/relatorios/geral';
            } else {
                var url = '/relatorios/geral?month=' + val + "&year=" + year; // get selected value
                if (url) { // require a URL
                    window.location = url; // redirect
                }
            }

            return false;
        });
    });


    var ctx_barChart = document.getElementById('barChart').getContext('2d');

    var inspectioned = new Chart(ctx_barChart, {
        type: 'bar',
        data: {
            labels: [
                'Janeiro',
                'Fevereiro',
                'Março',
                'Abril',
                'Maio',
                'Junho',
                'Julho',
                'Agosto',
                'Setembro',
                'Outubro',
                'Novembro',
                'Dezembro'
            ],
            datasets: [{
                    label: 'Receita',
                    data: {{json_encode($monthsIncomes)}},
                    backgroundColor: [
                        '#3a7fff',
                    ]
                },
                {
                    label: 'Despesas',
                    data: {{json_encode($monthsExpenses)}},
                    backgroundColor: [
                        'gray',
                    ]
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Receitas & Despesas'
                }
            }
        },
    });


    var ctxLine = document.getElementById("lineChart").getContext('2d');
    var monthOsMonths = [];
    <?php foreach ($monthOsMonths as $month) : ?>
        monthOsMonths.push('<?= $month ?>')
    <?php endforeach;  ?>


    var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: monthOsMonths,
            datasets: [{
                data: {{json_encode($qtdOsMonths)}},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(150,150,150,150)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Quantidade de O.S por mês'
                }
            }
        }
    });

    var cidades = [];
    <?php foreach ($topCities as $city) : ?>
        cidades.push('<?= $city ?>')
    <?php endforeach ?>

    var servicos = [];
    <?php foreach ($topServices as $service) : ?>
        servicos.push('<?= $service ?>')
    <?php endforeach ?>

    var clientes = [];
    <?php foreach ($topClients as $client) : ?>
        clientes.push('<?= $client ?>')
    <?php endforeach ?>

    var ctxPieCity = document.getElementById("cityChart").getContext('2d');
    
    dataPieCity = {
        datasets: [{
            data: {{json_encode($valueCities)}},
            backgroundColor: [
                '#ff6384',
                '#36a2eb',
                '#cc65fe',
                '#fd7e14',
                '#ffc107',
            ]
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: cidades
    };
    /* var mapCity = cidades.map((city) => {
        return city + '<br>';
    })
    var newCity = '';
    mapCity.forEach(function (el, i) {
        newCity += i+1 + '- ' + el;
    })
    document.getElementById('cityLegend').innerHTML = newCity; */
    var myPieChart = new Chart(ctxPieCity, {
        type: 'doughnut',
        data: dataPieCity,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    display: false
                },
                title: {
                    display: true,
                    text: 'Top 5 Cidades'
                }
            }
        }
    });
    var ctxPieServices = document.getElementById("servicesChart");
    dataPieServices = {
        datasets: [{
            data: {{json_encode($valueServices)}},
            backgroundColor: [
                '#fd7e14',
                '#ffc107',
                '#28a745',
                '#17a2b8',
                '#6c757d',
            ]
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: servicos
    };
    /* var mapServs = servicos.map((serv) => {
        return serv + '<br>';
    })
    var newServ = '';
    mapServs.forEach(function (el, i) {
        newServ += i+1 + '- ' + el;
    })
    document.getElementById('servLegend').innerHTML = newServ; */
    var myPieChart = new Chart(ctxPieServices, {
        type: 'doughnut',
        data: dataPieServices,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Top 5 Serviços'
                }
            }
        }
    });

    var ctxPieClients = document.getElementById("clientsChart");
    dataPieClients = {
        datasets: [{
            data: {{json_encode($valueClients)}},
            backgroundColor: [
                '#17a2b8',
                '#6c757d',
                '#20c997',
                '#ffc107',
                '#28a745'
            ]
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: clientes
    };
    /* var mapCli = clientes.map((cli) => {
        return cli + '<br>';
    })
    var newCli = '';
    mapCli.forEach(function (el, i) {
        newCli += i+1 + '- ' + el;
    })
    document.getElementById('cliLegend').innerHTML = newCli; */
    var myPieChart = new Chart(ctxPieClients, {
        type: 'doughnut',
        data: dataPieClients,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Top 5 Clientes'
                }
            }
        }
    });
</script>
@stop