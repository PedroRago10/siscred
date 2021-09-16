@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div style="display: flex; justify-content: flex-start; margin-bottom: 1em; margin-top: 1em;">
        <div style="width: 300px; height: 120px; border: 1px solid grey; padding: 15px;">
            <div class="form-group">
                {{Form::label('selectYear', 'Selecione o ano')}}
                {{Form::select('selectYear', $years, isset($year) ? $year : '', array('class' => 'form-control selectYear', 'name'=>'year', 'id' => 'selectYear'))}}
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
          <tr >
            <th class="bg-secondary" scope="col">Serviço</th>
            <th class="bg-secondary" scope="col">JAN</th>
            <th class="bg-secondary" scope="col">FEV</th>
            <th class="bg-secondary" scope="col">MAR</th>
            <th class="bg-secondary" scope="col">ABR</th>
            <th class="bg-secondary" scope="col">MAI</th>
            <th class="bg-secondary" scope="col">JUN</th>
            <th class="bg-secondary" scope="col">JUL</th>
            <th class="bg-secondary" scope="col">AGO</th>
            <th class="bg-secondary" scope="col">SET</th>
            <th class="bg-secondary" scope="col">OUT</th>
            <th class="bg-secondary" scope="col">NOV</th>
            <th class="bg-secondary" scope="col">DEZ</th>
            <th class="bg-secondary" scope="col">TOTAL NO ANO</th>
            <th class="bg-secondary" scope="col">%</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($incomes as $key => $income)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['01']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['02']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['03']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['04']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['05']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['06']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['07']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['08']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['09']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['10']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['11']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['12']) * 100) }}</td>
                <td>{{ \Cknow\Money\Money::BRL(intval($income['total']) * 100) }}</td>
                <td>{{ number_format((float)(($income['total'] / $totalIncomes) * 100), 2, '.', '') }}%</td>
            </tr>
          @endforeach
        </tbody>
      </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="/js/main.js?v=0.1"></script>
    <script>
        $(function(){
            // bind change event to select
            $('#selectYear').on('change', function () {
                var val = $(this).val();

                var url = '/relatorios/servicos?year='+val; // get selected value
                if (url) { // require a URL
                    window.location = url; // redirect
                }

                return false;
            });
        });
    </script>
@endsection
