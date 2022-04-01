@extends('layouts.plantillaCalendario')
@section('contenido')
<div class="row">
    <div class="col">
        <br>
        <a class="btn btn-primary" href="calendar/create" role="button">Crear evento</a>
    </div>
</div>
@if(Session::has('message'))
  <div class="row">
    <div class="col">
        <br>
        <div class="alert alert-success">{{ Session::get('message') }}</div>
    </div>
  </div>
@endif

<table class="table table-dark table-striped mt-4">
    <thead>
        <th>id</th>
        <th>resumen</th>
        <th>fecha inicio</th>
        <th>fecha fin</th>
        <th>acciones</th>
    </thead>
    <tbody>
        
    </tbody>
</table>
@endsection

<script>
let stateCheck = setInterval(() => {
    if (document.readyState === 'complete') {
        clearInterval(stateCheck);
        let btn = document.getElementById("btnEliminar");
        btn.onclick = mostrarAlerta;
        function mostrarAlerta(evento) {
            swal("Estas seguro de eliminar este evento?", {
            buttons: ["Cancelar", true],
            }).then((value) => {
                if(value === true){ 
                    let form = document.getElementById("formDelete").submit();
                }
            });
        }
    }
}, 100);
</script>