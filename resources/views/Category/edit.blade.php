@extends('adminlte::page')
@section('title','Editar Categoria')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-12 col-sm-10 col-lg-6 mx-auto ">
			@include('partials.validation-errors')
			<form class="bg-white py-3 px-4 shadow rounded "
			 method="POST"
			 enctype="multipart/form-data"
			 action="{{ route('category.update',$category)}}" >
			<h1 class="display-4">Editar Area</h1>
			<hr>
				@method('PUT')
				@include('Category._form',['btnText' => 'Actualizar'])

			</form>
		</div>
	</div>
</div>
@endsection