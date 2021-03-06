<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    protected $table = 'categorias';
    public $primarykey = 'id';


    public static function GuardarCategoria($data)
    {
    	$categoria = new Categoria();

    	$categoria->nombre_categoria = $data['nombre_categoria'];
    	$categoria->descripcion = $data['descripcion'];
		$categoria->created_at = date_create()->format('Y-m-d H:i:s');
		$categoria->updated_at = date_create()->format('Y-m-d H:i:s');
		$categoria->save();
		return true;

    }

    public static function Listar_Categorias()
    {
        return Categoria::select('categorias.id', 'categorias.nombre_categoria', 'categorias.descripcion')->get();
        
    }
    public static function Id2Nombre($id)
    {
        return Categoria::select('categorias.nombre_categoria')->where('categorias.id', $id)->get();
    }
}
