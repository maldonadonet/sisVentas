<?php

Route::get('/', function () {
    return view('auth/login');
});

Route::resource('almacen/categoria','CategoriaController');
Route::resource('almacen/articulo','ArticuloController');
Route::resource('ventas/cliente','ClienteController');
Route::resource('ventas/venta','VentaController');
Route::resource('compras/proveedor','ProveedorController');
Route::resource('compras/ingreso','IngresoController');
Route::resource('seguridad/usuario','UsuarioController');
Route::auth();
Route::get('/home', 'HomeController@index');

//Si la ruta no existe
Route::get('/{slug?}','HomeController@index');
