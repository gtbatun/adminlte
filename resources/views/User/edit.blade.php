@extends('adminlte::page')
@section('content')
<!--  -->
@can('update', $user)
<div class="container">
  <div class="pagetitle">
      <!-- <h1>Perfil</h1> -->
      <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
      </ol>
      </nav>
  </div>
</div>
    @if(Session::get('success'))
            <div class="alert alert-success mt-2">
            <strong>{{Session::get('success')}} </strong><br>
            </div>
    @endif
<!-- End Page Title -->
    <section class="section profile">
      <div class="container">
      <div class="row">
      
        <div class="col-xl-4">


          <div class="card responsive">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              @if($user->image != NULL)
                <!-- <img src="{{asset('storage/images/user/'. $user->image)}}" alt="Profile" class="rounded-circle sm img-fluid" > -->
                <img src="{{ route('archivo', ['archivo' => 'images/user/' . $user->image]) }}" class="rounded-circle sm img-fluid" alt="Profile">
                
              @endif              
              <h2>{{$user->name}}</h2>
              @if($user->department_id != NULL)
                <h3>{{$user->department->name}}</h3>              
              @endif              
            </div>
          </div>

        </div>
        <!-- seccion de visiaulizacion de datos a mostrar -->

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Informacion del Usuario</button>
                </li>

                </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show  active profile-overview" id="profile-overview">
                 
                  <!-- Profile Edit Form -->
                  <form action="{{route('user.update',$user)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Perfil</label>
                      <div class="col-md-8 col-lg-9">
                        <!-- <img class="sm" src="{{asset('storage/images/user/'. $user->image)}}" alt="Profile"> -->
                        <div class="pt-2">
                          <input name="image" type="file" accept="image/*" >
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nombre completo</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="{{$user->name}}">
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
                        <input name="extension" type="text" class="form-control" id="Phone" value="{{$user->extension}}">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="{{$user->email}}">
                      </div>
                    </div>
                    @if($user->isAdmin())
                    <div class="row mb-3">
                    <label for="Role" class="col-md-4 col-lg-3 col-form-label">Role</label>
                    <div class="col-md-8 col-lg-9">
                        <select name="is_admin" class="form-control" id="Role">
                            <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>Usuario estándar</option>
                            <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                </div>

                    @endif
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                 </div>
          </div>

        </div>
      </div>
      </div>
    </section>


<!--  -->
@endcan
@endsection
