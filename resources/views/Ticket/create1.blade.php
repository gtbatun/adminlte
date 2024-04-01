<!-- optimizar las consultas de las select options, se esta realizando 3 consultas una por cada opcion -->
<style>
        #preview {
            display: flex;
            flex-wrap: wrap;
        }

        .thumbnail {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .thumbnail img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .thumbnail button {
            margin-top: 5px;
            border: none;
            background-color: red;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
    
@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="col-12">
        <div>
            <h2>Crear Ticket</h2>
        </div>
        <div>
            <a href="{{route('ticket.index')}}" class="btn btn-primary">Volver</a>
        </div>
    </div>
    

    @include('partials.validation-errors') 

    <form action="{{route('ticket.store')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" >
        <input type="hidden" name="status_id" class="form-control" value="1" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Ticket:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Titulo" value="{{old ('title')}}" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Descripción:</strong>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="Descripción...">{{old ('description')}}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <!--  -->
                    <select name="area_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Elija un Area --</option>
                    @foreach($areas as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('area_id' , $ticket->area_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <!--  -->
                    <select name="department_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Departamento --</option>
                    @foreach($department as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('department_id' , $ticket->department_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <!--  -->
                    <select name="category_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Categoria --</option>
                    @foreach($category as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('department_id' , $ticket->category_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div>
            <!--  seccion para insertar imagenes y visualizarlos-->
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                    <strong>Adjuntos</strong>
                    <!-- <input type="file" name="image[]" class="form-control border-0 bg-light shadow-sm" id="seleccionArchivos" multiple accept="image/*" >
                    <img id="preview" class="container">  -->
                    <input type="file" class="form-control border-0 bg-light shadow-sm" name="image[]" multiple id="fileInput" accept="image/*">
                    <ul id="preview" class="list-group"></ul>
                                    
                    <script>
                    document.getElementById('fileInput').addEventListener('change', function(event) {
                        const files = event.target.files;
                        const preview = document.getElementById('preview');

                        preview.innerHTML = '';

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];

                            const listItem = document.createElement('li');

                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            img.width = 200;
                            img.height = 200;

                            const removeButton = document.createElement('button');
                            removeButton.textContent = 'Remove';
                            removeButton.addEventListener('click', () => {
                                event.target.value = null;
                                preview.removeChild(listItem);
                            });

                            listItem.appendChild(img);
                            listItem.appendChild(removeButton);
                            preview.appendChild(listItem);
                        }
                    });
                </script>
                </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </div>
    </form>
</div>
@endsection


<script>
                            document.getElementById('fileInput').addEventListener('change', function(event) {
                                const files = event.target.files;
                                const preview = document.getElementById('preview');

                                preview.innerHTML = '';

                                for (let i = 0; i < files.length; i++) {
                                    const file = files[i];

                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const thumbnail = document.createElement('div');
                                        thumbnail.classList.add('thumbnail');
                                        thumbnail.innerHTML = `
                                            <img src="${e.target.result}" alt="${file.name}">
                                            <button type="button" onclick="removeImage(this)">X</button>
                                        `;
                                        preview.appendChild(thumbnail);
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });

                            function removeImage(button) {
                                const thumbnail = button.parentElement;
                                thumbnail.remove();
                                const fileInput = document.getElementById('fileInput');
                                fileInput.value = null;
                            }
                        </script>





<script>
                        let files = [];
                        document.getElementById('fileInput').addEventListener('change', function(event) {
                            files = event.target.files;
                            const preview = document.getElementById('preview');
                            // console.log(preview);
                            // console.log(files);

                            for (let i = 0; i < files.length; i++) {
                                const file = files[i];
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    const thumbnail = document.createElement('div');
                                    thumbnail.classList.add('thumbnail');
                                    thumbnail.innerHTML = `
                                    <img src="${e.target.result}" alt="${file.name}">
                                    <button type="button" onclick="removeImage(this, ${i})">X</button>
                                    `;
                                    preview.appendChild(thumbnail);
                                };

                                reader.readAsDataURL(file);
                                console.log(file);
                            }
                            // console.log(files);
                        });

                        function removeImage(button,index) {
                            const thumbnail = button.parentElement;
                            thumbnail.remove();
                            const fileInput = document.getElementById('fileInput');
                            // fileInput.value = null;
                            fileInput.value = '';
                            // files = ''
                            // files.splice(index, 1);
                            // files[1]=null;
                            console.log(files);
                            console.log(files.length)

                        }
                    </script>



<!--  -->
<script>
                        let files = [];
                        document.getElementById('fileInput').addEventListener('change', function(event) {
                            files = event.target.files;
                            const preview = document.getElementById('preview');
                            // console.log(preview);
                            // console.log(files);

                            for (let i = 0; i < files.length; i++) {
                                const file = files[i];
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    const thumbnail = document.createElement('div');
                                    thumbnail.classList.add('thumbnail');
                                    thumbnail.innerHTML = `
                                    <img src="${e.target.result}" alt="${file.name}">
                                    <button type="button" onclick="removeImage(this, ${i})">X</button>
                                    `;
                                    preview.appendChild(thumbnail);
                                };

                                reader.readAsDataURL(file);
                                console.log(file);
                            }
                            // console.log(files);
                        });

                        function removeImage(button,index) {
                            const thumbnail = button.parentElement;
                            thumbnail.remove();
                            const fileInput = document.getElementById('fileInput');
                            // fileInput.value = null;
                            fileInput.value = null;
                            // files = ''
                            // files.splice(index, 1);
                            // files[1]=null;
                            console.log(files);
                            console.log(files.length)

                        }
                    </script>

<!--  -->

<script>
    window.addEventListener("load",() => {
        const input = document.getElementById("image");
        const filewrapper = document.getElementById("filewrapper");

        input.addEventListener("change",(e) => {
            let fileName =e.target.files[0].name;
            let filetype = e.target.value.split(".").pop();
            console.log(fileName,filetype);
            fileshow(fileName,filetype);
        })
        const fileshow = (fileName,filetype) =>{
            const showfileboxElem = document.createElement("div");
            showfileboxElem.classList.add("showfilebox");
            const leftElem = document.createElement("div");
            leftElem.classList.add("left");
            
            const fileTypeElem = document.createElement("span");
            fileTypeElem.classList.add("filetype");
            fileTypeElem.innerHTML = filetype;
            leftElem.append(fileTypeElem);

            const filetitleElem = document.createElement("h3");
            filetitleElem.innerHTML = fileName;
            leftElem.append(filetitleElem);

            showfileboxElem.append(leftElem);
            const rightElem = document.createElement("div");
            rightElem.classList.add("right");
            showfileboxElem.append(rightElem);

            const crossElem = document.createElement("span");
            crossElem.innerHTML = "&#215;";
            rightElem.append(crossElem);
            filewrapper.append(showfileboxElem);
        }

    })
</script>

<!-- script cambiado y si acepta y contabiliza los nombre de los archiovos e u¿imagenes -->
<input type="file" class="form-control border-0 bg-light shadow-sm" name="image[]" multiple id="fileInput" value="">
                    <input type="text" id="filesInput" name="filesInput" value="">
                    <input type="text" id="filesName" name="filesName" value="">
                    <p id="fileName"></p>
                    <div id="preview"></div>
                    <!-- ------------------- -->
                    <script>
                        // let fileNames = '';
                        let files = [];
                        let filesNames = [];
                        document.getElementById('fileInput').addEventListener('change', function(event) {
                            files = event.target.files;
                            const preview = document.getElementById('preview');
                            // console.log(preview);
                            console.log(files.length, files)

                            for (let i = 0; i < files.length; i++) {                                
                                const file = files[i];
                                filesNames.push(files[i].name);
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    const thumbnail = document.createElement('div');
                                    thumbnail.classList.add('thumbnail');
                                    thumbnail.innerHTML = `
                                    <img src="${e.target.result}" alt="${file.name}">
                                    <button type="button" onclick="removeImage(this, ${i})">X</button>
                                    `;
                                    preview.appendChild(thumbnail);
                                // fileNames += files[i].name;
                                // if (i < files.length - 1) {    
                                // fileNames += ',';
                                // }
                                // let fileNames = '';
                                // for (let i = 0; i < files.length; i++) {
                                //     fileNames += files[i].name;
                                //     if (i < files.length - 1) {
                                //         fileNames += ', '; // Agregar una coma y un espacio entre cada nombre de archivo
                                //     }
                                // }
                                    


                            
                            document.getElementById('filesInput').value = files[i].name;
                                };
                                // document.getElementById('filesInput').value = fileNames;

                                reader.readAsDataURL(file);
                                // console.log(file);
                                // $fileNames += file[i].name;                                                                
                            
                            }
                            

                            console.log("Datos de files",files);
                            console.log("Dato ", filesNames);

                            document.getElementById('filesName').value = filesNames;
                            

                            // document.getElementById('filesInput').value = files.name;
                            // console.log(files);
                        });

                        function removeImage(button,index) {
                            // const thumbnail = button.parentElement;
                            // thumbnail.remove();
                            // const fileInput = document.getElementById('fileInput');
                            // fileInput.value = null;
                            // 
                            const thumbnail = document.getElementById('preview');
                            thumbnail.removeChild(thumbnail.childNodes[index]);
                            files = Array.from(files).filter((_, i) => i !== index);
                            console.log('Despues del remove',files);
                            console.log("Datos de filesNames despues del remove",filesNames);
                        }
                    </script>

                    <!-- ------------------- -->  


                    <script>
                let files = [];
                let filesNames = [];

                document.getElementById('fileInput').addEventListener('change', function(event){
                  const files = event.target.files; 
                  const preview = document.getElementById('preview');
                //   preview.innerHTML = '';
                  for (let i = 0; i < files.length; i++){
                    const file = files[i];
                    filesNames.push(files[i].name);
                    const reader = new FileReader();

                    reader.onload = function(e){
                        const thumbnail = document.createElement('div');
                        thumbnail.classList.add('thumbnail');
                        thumbnail.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <button type="button" onclick="removeImage(this,${i})">X</button>
                        <p>${file.name}</p>
                        `;
                        preview.appendChild(thumbnail);

                        
                    };
                    reader.readAsDataURL(file,filesNames);
                  }

                  console.log("datos de las img",files);
                  console.log("nombres de las img",filesNames);

                  document.getElementById('filesNames').value = filesNames;

                });

                // function removeImage(button){
                    function removeImage(button,index){
                    // const thumbnail = document.getElementById('preview');
                    // thumbnail.removeChild(thumbnail.childNodes[index]);
                    // files = Array.from(files).filter((_, i) => i != index);
                    // files.splice(index, 1);
                    // filesNames.splice(index, 1);
                    // const index = Array.from(preview.children).indexOf(thumbnail);
                    const preview = document.getElementById('preview');
                    const thumbnail = button.parentElement;
                    // const index = Array.from(preview.children).indexOf(thumbnail);

                    if(index !== -1 && thumbnail.parentNode === preview){
                        preview.removeChild(thumbnail);
                        files.splice(index, 1);
                        filesNames.splice(index, 1);
                        console.log('Despues del remove',files);
                        console.log('nombres Despues del remove',filesNames);
                    }


                    console.log('Despues del remove',files);
                    console.log('nombres Despues del remove',filesNames);
                    document.getElementById('filesNames').value = filesNames;
                    document.getElementById('fileInput').value = files;
                    
                }

            </script>


<!-- ------------------------------------------- -->

<script>
                let files = [];
                let filesNames = [];

                document.getElementById('fileInput').addEventListener('change', function(event){
                    // const files = event.target.files; 
                  const filesList = event.target.files; //recien agregado
                  const preview = document.getElementById('preview');
                
                  for (let i = 0; i < filesList.length; i++){
                    const file = filesList[i];
                    files.push(file);
                    filesNames.push(file.name);

                    const reader = new FileReader();
                    reader.onload = function(e){
                        const thumbnail = document.createElement('div');
                        thumbnail.classList.add('thumbnail');
                        thumbnail.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <button type="button" onclick="removeImage(this,${i})">X</button>
                        <p>${file.name}</p>
                        `;
                        preview.appendChild(thumbnail);     
                    };
                    reader.readAsDataURL(file,filesNames);
                  }

                  console.log("datos de las img",files);
                  console.log("nombres de las img",filesNames);

                //   document.getElementById('filesNames').value = filesNames;
                updateFilesNamesInput();

                });

                
                    function removeImage(button,index){
                    const preview = document.getElementById('preview');
                    const thumbnail = button.parentElement;

                    if(index !== -1 && thumbnail.parentNode === preview){
                        preview.removeChild(thumbnail);
                        files.splice(index, 1);
                        filesNames.splice(index, 1);
                        updateFilesNamesInput();

                        console.log('Despues del remove',files);
                        console.log('nombres Despues del remove',filesNames);
                    }


                    console.log('Despues del remove',files);
                    console.log('nombres Despues del remove',filesNames);
                    // document.getElementById('filesNames').value = filesNames;
                    // document.getElementById('fileInput').value = files;
                    
                }
                function updateFilesNamesInput() {
                    document.getElementById('filesNames').value = filesNames.join(',');
                    document.getElementById('files').value = files;
                }

            </script>



<!-- ------------------------------------ -->
<script>

                
let files = [];
let filesNames = [];


document.getElementById('fileInput').addEventListener('change', function(event){
    // const files = event.target.files; 
  const filesList = event.target.files; //recien agregado
  const preview = document.getElementById('preview');

  for (let i = 0; i < filesList.length; i++){
    const file = filesList[i];
    files.push(file);
    filesNames.push(file.name);

    const reader = new FileReader();
    reader.onload = function(e){
        const thumbnail = document.createElement('div');
        const index = files.length - 1; // Obtener el índice actual en el array files
        thumbnail.dataset.index = index; // Almacenar el índice en un atributo de datos del elemento            
        thumbnail.classList.add('thumbnail');
        thumbnail.innerHTML = `
        <img src="${e.target.result}" alt="${file.name}">
        <button type="button" onclick="removeImage(this,${i})">X</button>
        <p>${file.name}</p>
        `;
        preview.appendChild(thumbnail);     
        console.log('Segun el push de filesnames',filesNames);
    console.log('Segun el push de files',files);
    // seccion para recorrer el file
    
    // 
    };
    reader.readAsDataURL(file);
    // reader.readAsDataURL(file,filesNames);


  }

//   console.log("datos de las img",files);
//   console.log("nombres de las img",filesNames);

//   document.getElementById('filesNames').value = filesNames;
updateFilesNamesInput();


});


    function removeImage(button){
        // function removeImage(button,index){
    // const preview = document.getElementById('preview');
    const thumbnail = button.parentElement;
    const index = thumbnail.dataset.index; // Obtener el índice almacenado en el atributo de datos


    // if(index !== -1 && thumbnail.parentNode === preview){
        if (index !== undefined) {
        // preview.removeChild(thumbnail);
        thumbnail.remove();
        files.splice(index, 1);
        filesNames.splice(index, 1);
        updateFilesNamesInput();

        console.log('Despues del remove',files);
        console.log('nombres Despues del remove',filesNames);
         // Actualizar los índices almacenados en los elementos restantes
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach((thumb, i) => {
            thumb.dataset.index = i;
        });
    }


    // console.log('Despues del remove',files);
    // console.log('nombres Despues del remove',filesNames);
    // document.getElementById('filesNames').value = filesNames;
    // document.getElementById('fileInput').value = files;
    
}
function updateFilesNamesInput() {
    document.getElementById('filesNames').value = filesNames.join(',');
    document.getElementById('files').value = files;
    document.getElementById('imgs').value = JSON.stringify(files);
    // files1 = Array.from(files);
    // document.getElementById('imgs').value = files1;
    
    // console.log('files1:',files1);
}
</script>