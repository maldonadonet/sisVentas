@extends ('layout/admin')
@section ('contenido')
      <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <h3>Nuevo Cliente</h3>
              <!--Si existe algun error muestra el div con la lista de errores-->
              @if (count($errors)>0)
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
          </div>
      </div>

      {!!Form::open(array('url'=>'ventas/cliente','method'=>'POST','autocomplete'=>'off'))!!}
      {{Form::token()}}

      <div class="row">
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre..">
              </div>
          </div>

          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" value="{{old('direccion')}}" class="form-control" placeholder="Dirección..">
              </div>
          </div>

          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                  <label>Documento</label>
                  <select class="form-control" name="tipo_documento">
                          <option value="DNI">Ife/Ine</option>
                          <option value="PAS">Pasaporte</option>
                          <option value="CED">Cedula Profesional</option>
                          <option value="CAD">Cartilla Militar</option>
                  </select>
              </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                <label for="num_documento">Numero de Documento.</label>
                <input type="text" name="num_documento" value="{{old('num_documento')}}" class="form-control" placeholder="Numero de Documento...">
              </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                <label for="telefono">Telefono.</label>
                <input type="tel" name="telefono" value="{{old('telefono')}}" class="form-control" placeholder="Telefono...">
              </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                <label for="email">Email.</label>
                <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="Email...">
              </div>
          </div>

          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <button type="reset" class="btn btn-danger">Cancelar</button>
            </div>
          </div>
      </div>

      {!!Form::close()!!}
@endsection
