@if($tickets->isEmpty())
    <p class="text-danger">No hay tickets para mostrar.</p>
@else
<div class="col-12 mt-1">
        <div class="card fluid">
            <div class="card-body">  
                <div class="table-responsive" >
                <table id="reportTableone" class="table table-striped table-bordered dt-responsive nowrap" style="width:98%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Creador</th>
                            <th>asignado</th>
                            <th>Concepto</th>
                            <th>Categoría</th>
                            <th>Título</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Personal Sistemas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se llenarán los datos con AJAX -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endif