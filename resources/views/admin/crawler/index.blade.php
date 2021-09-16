@extends('adminlte::page')

@section('css')
    <link href="/css/crawler.css?v=1.7" rel="stylesheet" />
@stop

@section('title', 'Crawler')

@section('content_header')

    <h1>Crawler</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fa fa-address-card"></i> Home</a></li>
            <li class="breadcrumb-item active">Crawler</li>
        </ol>
    </nav>

@stop

@section('content')

    <div class='content-crawler'>
        <?php
            foreach($olx[0] as $item) {
                echo $item;
            }
        ?>
        <div class='olx-content'>
            <?php
            foreach($olx[1] as $item) {
                echo $item;
            }
            ?>
        </div>
    </div>

@stop
@section('js')
<script src="/js/crawler.js?v=0.3"></script>
@stop