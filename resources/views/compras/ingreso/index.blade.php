@extends ('layout/admin')
@section ('contenido')
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
      <!-- Boton que en el href nos lleva a la ruta categoria/create -->
      <h3>Listado de Ingresos <a href="ingreso/create"><button class="btn btn-success">Nuevo</button></a></h3>
      <!--Se incluye el archivo que vincula el formulario con la eleccion de categorias  -->
      @include('compras.ingreso.search')
    </div>
  </div>

  <!--  -->
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead>
            <th>Fecha</th>
            <th>Proveedor.</th>
            <th>Comprobante.</th>
            <th>Impuesto</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Opciones</th>
          </thead>
          <!--Estructura de control que recorre todas las categorias por medio de la variable recibida por el controlador  -->
          @foreach ($ingresos as $ing)
          <tr>
            <td>{{ $ing->fecha_hora }}</td>
            <td>{{ $ing->nombre }}</td>
            <td>{{ $ing->tipo_comprobante.': '.$ing->serie_comprobante.'-'.$ing->num_comprobante }}</td>
            <td>{{ $ing->impuesto }}</td>
            <td>{{ $ing->total }}</td>
            <td>{{ $ing->estado }}</td>
            <td>
              <a href="{{URL::action('IngresoController@show',$ing->idingreso)}}"><button class="btn btn-primary">Detalles</button></a>
              <a href="#" data-target="#modal-delete-{{$ing->idingreso}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
            </td>
          </tr>
          @include('compras.ingreso.modal')
          @endforeach
        </table>
      </div>
      <!-- Funcion render que nos va hacer la paginacion -->
      {{$ingresos->render()}}
    </div>
  </div>
@stop