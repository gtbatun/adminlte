@extends('adminlte::page')
@section('title', 'Soporte')
@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <p>Welcome to this beautiful admin panel.</p>
    <p>Bienvenido {{auth()->user()->name ?? auth()->user()->username}}, estas authentificado</p>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop