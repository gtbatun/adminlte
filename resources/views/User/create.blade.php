@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-body pt-3">
        <div class="tab-pane fade show  active profile-overview" id="profile-overview">                 
            <!-- Profile Edit Form -->

            <!-- @include('partials.validation-errors')  -->

            <form action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Perfil</label>
                <div class="col-md-8 col-lg-9">
                <div class="pt-2">
                    <input name="image" type="file" accept="image/*" >
                </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nombre completo</label>
                <div class="col-md-8 col-lg-9">
                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{old ('name')}}" >
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-md-4 col-lg-3 col-form-label ">Contraseña</label>
                <div class="col-md-8 col-lg-9">
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" >
                </div>
            </div>
            <div class="row mb-3">
                <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label ">Repetir Contraseña</label>
                <div class="col-md-8 col-lg-9">
                <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror">
                </div>
            </div>

            <div class="row mb-3">
                <label for="Job" class="col-md-4 col-lg-3 col-form-label">Departamento</label>
                <div class="col-md-8 col-lg-9">
                <!-- <input name="job" type="text" class="form-control" id="Job" value="Web Designer"> -->
                <select name="department_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Departamento --</option>
                    @foreach($department as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('department_id' , $user->department_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div>
            

            <div class="row mb-3">
                <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Extension</label>
                <div class="col-md-8 col-lg-9">
                <input name="extension" type="text" class="form-control" id="Phone" value="{{old ('extension')}}">
                </div>
            </div>

            <div class="row mb-3">
                <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                <div class="col-md-8 col-lg-9">
                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{old ('email')}}" >
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Role</label>
                <div class="col-md-8 col-lg-9">
                <!-- <input name="is_admin" type="text" class="form-control" id="Phone" > -->
                
                <select name="is_admin" id="" class="form-control @error('is_admin') is-invalid @enderror"  >
                <option value="">-- Role --</option>
                <option value="0">Usuario Standar</option>
                <option value="1">Administrador</option>
                <!-- <option value="2">Encargado</option> -->
                </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            </form><!-- End Profile Edit Form -->
        </div>

        </div>
    </div>
</div>
    
@endsection