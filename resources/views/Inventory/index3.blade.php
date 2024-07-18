@extends('adminlte::page')
@section('content')

<div class="container-fluid">
    <h2 class="text-center display-4">Buscar</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
        <form action="">
            <div class="input-group">
            <input type="search" class="form-control form-control-lg" placeholder="Buscar usuario">
                <div class="input-group-append">
                <button type="submit" class="btn btn-lg btn-default">
                <i class="fa fa-search"></i>
                </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection