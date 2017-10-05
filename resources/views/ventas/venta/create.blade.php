@extends ('layout/admin')
@section ('contenido')
      <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <h3>Agregar Venta</h3>
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

      {!!Form::open(array('url'=>'ventas/venta','method'=>'POST','autocomplete'=>'off'))!!}
      {{Form::token()}}

      <div class="row">
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
              <div class="form-group">
                <label for="cliente">Cliente</label>
                <select class="form-control selectpicker" name="idcliente" id="idcliente" data-live-search="true">
                    @foreach($personas as $persona)
                      <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                    @endforeach
                </select>
              </div>
          </div>

          <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
              <div class="form-group">
                  <label>Comprobante</label>
                  <select class="form-control" name="tipo_comprobante">
                          <option value="boleta">Boleta</option>
                          <option value="factura">Factura</option>
                          <option value="ticket">Ticket</option>
                  </select>
              </div>
          </div>

          <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="serie_comprobante">Serie Comprobante</label>
                <input type="text" name="serie_comprobante" value="{{old('serie_comprobante')}}" class="form-control" placeholder="Serie de comprobante...">
              </div>
          </div>

          <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
              <div class="form-group">
                <label for="num_comprobante">Numero Comprobante</label>
                <input type="text" name="num_comprobante" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Numero de comprobante...">
              </div>
          </div>
      </div>

      <div class="row">
          <div class="panel panel-primary">
              <div class="panel-body">
                  <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                      <div class="form-group">
                          <label>Articulo</label>
                          <select class="form-control selectpicker" name="pidarticulo" id="pidarticulo" data-live-search="true">
                              @foreach($articulos as $articulo)
                                <option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{$articulo->precio_promedio}}">{{$articulo->articulo}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                      <div class="form-group">
                          <label for="cantidad">Cantidad</label>
                          <input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="Cantidad..">
                      </div>
                  </div>

                  <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                      <div class="form-group">
                          <label for="stock">Stock</label>
                          <input type="number" disabled name="pstock" id="pstock" class="form-control" placeholder="Stock..">
                      </div>
                  </div>

                  <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                      <div class="form-group">
                        <div class="form-group">
                            <label for="precio_venta">Precio Venta</label>
                            <input type="number" disabled name="pprecio_venta" id="pprecio_venta" class="form-control" placeholder="Precio de venta..">
                        </div>
                      </div>
                  </div>

                  <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                      <div class="form-group">
                          <label for="descuento">Descuento</label>
                          <input type="number" name="pdescuento" id="pdescuento" class="form-control" placeholder="Descuento..">
                      </div>
                  </div>



                  <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form-group">
                            <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                        </div>
                  </div>

                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                      <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                          <thead style="background-color:#a9d0f5">
                              <th>Opciones</th>
                              <th>Artículo</th>
                              <th>Cantidad</th>
                              <th>Precio Venta</th>
                              <th>Descuento</th>
                              <th>Sub total</th>
                          </thead>
                          <tfoot>
                              <th>TOTAL</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th><h4 id="total">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>
                          </tfoot>
                          <tbody>

                          </tbody>
                      </table>
                  </div>
              </div>
          </div>

          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
            <div class="form-group">
              <!--Token que me va permitir trabajar con las transacciones den controlador-->
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <button type="reset" class="btn btn-danger">Cancelar</button>
            </div>
          </div>
      </div>

      {!!Form::close()!!}
<!--Codigo JavaScript-->
@push ('scripts')
<script>
    $(document).ready(function(){
        $("#bt_add").click(function(){
            agregar();
        });
    });

    var cont=0;
    total=0;
    subtotal=[]; //array para cada detalle del ingreso
    $("#guardar").hide();
    $("#pidarticulo").change(mostrarValores);

    function mostrarValores(){
      datosArticulo=document.getElementById('pidarticulo').value.split('_');
      $("#pprecio_venta").val(datosArticulo[2]);
      $("#pstock").val(datosArticulo[1]);
    }


    //funcion agregar
    function agregar(){
        datosArticulo=document.getElementById('pidarticulo').value.split('_');

        idarticulo=datosArticulo[0];
        articulo=$("#pidarticulo option:selected").text();
        cantidad=$("#pcantidad").val();
        descuento=$("#pdescuento").val();
        precio_venta=$("#pprecio_venta").val();
        stock=$("#pstock").val();

      //Validamos que no esten vacios los campos
      if(idarticulo!="" && cantidad!="" && cantidad>0 && descuento!="" && precio_venta!="")
       {
           if(stock>=cantidad){
             subtotal[cont]=(cantidad*precio_venta-descuento);
             total=total+subtotal[cont];

             var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+idarticulo+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td><td><input type="number" name="descuento[]" value="'+descuento+'"></td><td>'+subtotal[cont]+'</td></tr>;'
             cont++;
             limpiar();
             $("#total").html("S/."+ total);
             $("#total_venta").val(total);
             evaluar();
             $('#detalles').append(fila);
           }else{
             alert("La cantidad a vender supera el stock");
           }

       }
       else
       {
           alert ("Error al ingresar el detalle de la venta, revise los datos del articulo");
       }
    }


    //funcion para limpiar el formulario
    function limpiar(){
      $("#pcantidad").val("");
      $("#pdescuento").val("");
      $("pprecio_venta").val("");
    }

    //Funcion para verificar si no tengo ningun detalle en la tabla voy a ocultar los botones de guardar
    function evaluar(){
      if(total>0){
        $("#guardar").show();
      }else{
        $("#guardar").hide();
      }
    }

    //Funcion para eliminar cada detalle de ingreso
    function eliminar(index){
        total=total-subtotal[index];
        $("#total").html("$/. " + total);
        $("#total_venta").val(total);
        $("#fila" + index).remove();
        evaluar();
    }
</script>
@endpush
@endsection
