@extends('adminlte::page')
@section('content')
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
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


<div class="container">
    @if(Session::get('success'))
            <div class="alert alert-success mt-2">
            <strong>{{Session::get('success')}} </strong><br>
            </div>
    @endif
</div>   
<!-- End Page Title -->
    <section class="section profile">
      <div class="container">
      @include('partials.validation-errors')
      <div class="row">
      
        <div class="col-xl-4">


          <div class="card responsive">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              @if($user->image != NULL)
                <!-- <img src="{{asset('storage/images/user/'. $user->image)}}" alt="Profile" class="rounded-circle sm img-fluid" > -->
                <img src="{{ route('archivo', ['archivo' => 'images/user/' . $user->image]) }}" class="rounded-circle sm img-thumbnail" alt="Profile">
                
              @endif              
              <h2>{{$user->name}}</h2>
              @if($user->department_id != NULL)
                <h3>{{$user->department->name}}</h3>              
              @endif              
            </div>
          </div>
          @if(!isset(auth()->user()->department_id))
          <x-adminlte-alert theme="danger" title="Alerta">
          Complete su perfil
          </x-adminlte-alert>
          @endif

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
                          <input name="image" type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror" >
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nombre completo</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="{{$user->name}}">
                      </div>
                    </div>
                    <!-- Agregar seccion de sucursal -->
                    @if($user->sucursal_id == '' || auth()->user()->is_admin == 10)
                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Sucursal</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="sucursal_id" id="sucursal" class="form-control border-0 bg-light shadow-sm @error('department_id') is-invalid @enderror">
                          <option value="">-- Sucursal --</option>
                          @foreach($sucursal as  $id => $name)
                          <option value="{{$id}}"
                          @if($id == old('sucursal_id' , $user->sucursal_id)) selected @endif  >{{$name}}</option>
                          @endforeach                    
                          </select>
                      </div> 
                    </div>
                    @endif
                    <!-- fin de seccion de agregar sucursal -->

                    @if($user->department_id == '' || auth()->user()->is_admin == 10)
                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Departamento</label>
                      <div class="col-md-8 col-lg-9">
                        <select id="department" name="department_id" class="form-control border-0 bg-light shadow-sm @error('department_id') is-invalid @enderror">
                        <option value="">Seleccione un departamento</option>                 
                          </select>
                      </div>
                    </div>
                    @endif 
                    @if(auth()->user()->is_admin == 10)
                    <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label" for="sucursal">Solo ver tickets de:</label>
                    <div class="col-md-8 col-lg-9">
                    <select name="ver_ticket[]" class="form-control" multiple required>                      
                          @foreach($departments as $id => $name)
                                <option value="{{ $id }}" @if(in_array($id, $userDepartments)) selected @endif>{{ $name }}</option>
                          @endforeach
                    </select>
                    </div>
                    </div> 
                    @endif 
                    <div class="row mb-3">
                      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Extension</label>
                      <div class="col-md-8 col-lg-2">
                        <input name="extension" type="text" class="form-control @error('extension') is-invalid @enderror" id="Phone" value="{{$user->extension}}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="{{$user->email}}">
                      </div>
                    </div>
                    
                    @if(auth()->user()->is_admin == 10)
                    <div class="row mb-3">
                    <label for="Role" class="col-md-4 col-lg-3 col-form-label">Role</label>
                    <div class="col-md-8 col-lg-9">
                        <select name="is_admin" class="form-control" id="Role">
                            <option title="Ver tickets creados y asignados al Dep" value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>Usuario estándar</option>
                            <option title="Ver todos los ticket creados" value="5" value="5" {{ $user->is_admin == 5 ? 'selected' : '' }}>Supervisor</option>
                            <option title="Ver todas la opciones y configuraciones" value="10" {{ $user->is_admin == 10 ? 'selected' : '' }}>Administrador</option>
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
@section('js')
<script>
  $(document).ready(function() {
     // Inicializar el select de departamentos si ya hay una sucursal seleccionada
     var initialSucursalID = $('#sucursal').val();

     if (initialSucursalID) {
      updateDepartments(initialSucursalID, {{$user->department_id}});
      }    

    $('#sucursal').on('change', function() {
        var sucursalID = $(this).val();
        updateDepartments(sucursalID, null);
    });

    function updateDepartments(sucursalID, selectedDepartmentID) {
        if (sucursalID) {
            $.ajax({
                url: '/department/data/' + sucursalID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#department').empty();
                    $('#department').append('<option value="">Seleccione un departamento</option>'); 
                    $.each(data, function(key, value) {
                        $('#department').append('<option value="'+ value.id +'"'+ (selectedDepartmentID == value.id ? ' selected' : '') +'>'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('#department').empty();
            $('#department').append('<option value="">Seleccione un departamento</option>');
        }
    }
  });
</script>
@endsection
