@extends('adminlte::page')
@section('content')

<form action="{{route('reportessssss.store')}}" method="post">
    @csrf
    <input name="test" type="text">
    <button>send</button>
</form>

@endsection