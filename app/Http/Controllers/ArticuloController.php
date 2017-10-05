<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;
use sisVentas\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\ArticuloFormRequest;
use sisVentas\Articulo;
use DB;

class ArticuloController extends Controller
{
  //Constructor
  public function __construct(){
    $this->middleware('auth');
  }

  //Metodo Index
  public function index(Request $request ){
      //Validamos si tenemos el objeto request
      if($request){
          $query=trim($request->get('searchText'));
          $articulos=DB::table('articulo as a')
          ->join('categoria as c','a.idcategoria','=','c.idcategoria')
          ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.descripcion','a.imagen','a.estado')
          ->where('estado','=','Activo')
          ->where('a.nombre','LIKE','%'.$query.'%')
          //->where('a.codigo','LIKE','%'.$query.'%')
          ->orderBy('a.idarticulo','desc')
          ->paginate(7);
          return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);
      }
  }

  //Metodo Create
  public function create(){
      $categorias = DB::table('categoria')->where('condicion','=','1')->get();
      return view('almacen.articulo.create',['categorias'=>$categorias]);
  }

  //Metodo storer para almacenar
  public function store(ArticuloFormRequest $request){


      $articulo=new Articulo;
      $articulo->idcategoria=$request->get('idcategoria');
      $articulo->codigo=$request->get('codigo');
      $articulo->nombre=$request->get('nombre');
      $articulo->stock=$request->get('stock');
      $articulo->descripcion=$request->get('descripcion');
      $articulo->estado='Activo';

      if(Input::hasFile('imagen')){
        $file=Input::file('imagen');
        $file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
        $articulo->imagen=$file->getClientOriginalName();
      }
      $articulo->save();
      return Redirect::to('almacen/articulo');
  }

  //Metodo show para mostrar
  public function show($id){
      return view('almacen.articulo.show',['articulo'=>Articulo::findOrFail($id)]);
  }

  //Metodo edit para modificar
  public function edit($id){
      $articulo=Articulo::findOrFail($id);
      $categoria=DB::table('categoria')->where('condicion','=','1')->get();
      return view('almacen.articulo.edit',['articulo'=>$articulo,'categorias'=>$categoria]);
  }

  //Metodo update para actualizar
  public function update(ArticuloFormRequest $request,$id){
      $articulo=Articulo::findOrFail($id);

      $articulo->idcategoria=$request->get('idcategoria');
      $articulo->codigo=$request->get('codigo');
      $articulo->nombre=$request->get('nombre');
      $articulo->stock=$request->get('stock');
      $articulo->descripcion=$request->get('descripcion');

      if(Input::hasFile('imagen')){
        $file=Input::file('imagen');
        $file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
        $articulo->imagen=$file->getClientOriginalName();
      }

      $articulo->update();
      return Redirect::to('almacen/articulo');
  }

  //Metodo destroy para eliminar un objeto y destruirlo de la tabla y la db
  public function destroy($id){
      $articulo=Articulo::findOrFail($id);
      $articulo->estado='inactivo';
      $articulo->update();
      return Redirect::to('almacen/articulo');
  }
}
