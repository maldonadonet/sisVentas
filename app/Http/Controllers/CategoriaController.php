<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;
use sisVentas\Http\Requests;
//Referencia al modelo
use sisVentas\Categoria;
//Redirecciones
use Illuminate\Support\Facades\Redirect;
//Request CategoriaFormRequest
use sisVentas\http\Requests\CategoriaFormRequest;
//Clase Db
use DB;

class CategoriaController extends Controller
{
  //Constructor
  public function __construct(){
    $this->middleware('auth');
  }

    //Metodo Index
    public function index(Request $request ){
        //Validamos si tenemos el objeto request
        if($request){
            //obtenemos la categoria a buscar
            $query=trim($request->get('searchText'));
            //muestra los datos con las condiciones en la var categoria
            $categorias=DB::table('categoria')->where('nombre','LIKE','%'.$query.'%')
            ->where('condicion','=','1')
            ->orderBy('idcategoria','desc')
            ->paginate(7);
            //regresamos a la vista los resultados
            return view('almacen.categoria.index',["categorias"=>$categorias,"searchText"=>$query]);
        }
    }

    //Metodo Create
    public function create(){
        return view('almacen.categoria.create');
    }

    //Metodo storer para almacenar
    public function store(CategoriaFormRequest $request){
        //Hacemos referencia o instancia del modelo Categoria en la var
        $categoria=new Categoria;
        //Mandamos los valores ya validados por el objeto request
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->condicion='1';
        //LLamamos ala funcion save para guardar el objeto categoria
        $categoria->save();
        //Retornamos una redireccion para ir al listado de las categorias
        return Redirect::to('almacen/categoria');
    }

    //Metodo show para mostrar
    public function show($id){
        //regresamos la vista mandando el id a el modelo categoria con el metodo findOrFail para mostrar solo esa categoria
        return view('almacen.categoria.show',['categoria'=>Categoria::findOrFail($id)]);
    }

    //Metodo edit para modificar
    public function edit($id){
        return view('almacen.categoria.edit',['categoria'=>Categoria::findOrFail($id)]);
    }

    //Metodo update para actualizar
    public function update(CategoriaFormRequest $request,$id){
        $categoria=Categoria::findOrFail($id);
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }

    //Metodo destroy para eliminar un objeto y destruirlo de la tabla y la db
    public function destroy($id){
        $categoria=Categoria::findOrFail($id);
        $categoria->condicion='0';
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }
}
