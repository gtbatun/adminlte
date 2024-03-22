@extends('adminlte::page')
@section('title','Crear Area')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-12 col-sm-10 col-lg-6 mx-auto ">
	@include('partials.validation-errors')

	<form class="bg-white py-3 px-4 shadow rounded "
		method="post"
		enctype="multipart/form-data"
		action="{{ route('area.store')}}" >
		<h1 class="display-4">Nuevo Area</h1>
		<hr>
		@include('Area._form',['btnText' => 'Guardar'])

	</form>

		</div>
	</div>
</div>
@endsection