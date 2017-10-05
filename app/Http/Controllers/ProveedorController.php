<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;
use sisVentas\Http\Requests;
use sisVentas\Persona;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\PersonaFormRequest;
use DB;

class ProveedorController extends Controller
{
  //Constructor
  public function __construct(){
    $this->middleware('auth');
  }

  //Metodo Index
  public function index(Request $request ){

      if($request){
          $query=trim($request->get('searchText'));
          $personas=DB::table('persona')
          ->where('nombre','LIKE','%'.$query.'%')
          ->where('tipo_persona','=','Proveedor')
          ->orwhere('num_documento','LIKE','%'.$query.'%')
          ->where('tipo_persona','=','Proveedor')
          ->orderBy('idpersona','desc')
          ->paginate(7);
          return view('compras.proveedor.index',["personas"=>$personas,"searchText"=>$query]);
      }
  }

  //Metodo Create
  public function create(){
      return view('compras.proveedor.create');
  }

  //Metodo storer para almacenar
  public function store(PersonaFormRequest $request){
      $persona=new Persona;
      $persona->tipo_persona='Proveedor';
      $persona->nombre=$request->get('nombre');
      $persona->tipo_documento=$request->get('tipo_documento');
      $persona->num_documento=$request->get('num_documento');
      $persona->direccion=$request->get('direccion');
      $persona->telefono=$request->get('telefono');
      $persona->email=$request->get('email');
      $persona->save();
      return Redirect::to('compras/proveedor');
  }

  //Metodo show para mostrar
  public function show($id){
      //regresamos la vista mandando el id a el modelo categoria con el metodo findOrFail para mostrar solo esa categoria
      return view('compras.proveedor.show',['persona'=>Persona::findOrFail($id)]);
  }

  //Metodo edit para modificar
  public function edit($id){
      return view('compras.proveedor.edit',['persona'=>Persona::findOrFail($id)]);
  }

  //Metodo update para actualizar
  public function update(PersonaFormRequest $request,$id){
      $persona=Persona::findOrFail($id);
      $persona->nombre=$request->get('nombre');
      $persona->tipo_documento=$request->get('tipo_documento');
      $persona->num_documento=$request->get('num_documento');
      $persona->direccion=$request->get('direccion');
      $persona->telefono=$request->get('telefono');
      $persona->email=$request->get('email');
      $persona->update();
      return Redirect::to('compras/proveedor');
  }

  //Metodo destroy para eliminar un objeto y destruirlo de la tabla y la db
  public function destroy($id){
      $persona=Persona::findOrFail($id);
      $persona->tipo_persona='Inactivo';
      $persona->update();
      return Redirect::to('compras/proveedor');
  }

}
