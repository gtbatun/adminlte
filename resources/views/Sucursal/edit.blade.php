@extends('adminlte::page')
@section('title','Editar Sucursal')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-12 col-sm-10 col-lg-6 mx-auto ">
			@include('partials.validation-errors')
			<form class="bg-white py-3 px-4 shadow rounded "
			 method="POST"
			 enctype="multipart/form-data"
			 action="{{ route('sucursal.update',$sucursal)}}" >
			<h1 class="display-4">Editar Sucursal</h1>
			<hr>
				@method('PUT')
				@include('Sucursal._form',['btnText' => 'Actualizar'])

			</form>
		</div>
	</div>
</div>
@endsection