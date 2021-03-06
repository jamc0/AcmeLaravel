<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB as DB;
class PersonaJuridica extends Model
{
    //

    protected $table = 'personasjuridicas';
    public $primarykey = 'id';

    public static function GuardarPersonaJuridica($data)
    {
    	try
         {
            DB::beginTransaction();

            	$codigo_persona_generado = DB::table('personas')->insertGetId(
	     			[
	     				'tipo_persona_id' => 2,
	     				'estado_id' => 1, 
			 			'created_at' =>  date_create()->format('Y-m-d H:i:s'),
			 			'updated_at' =>  date_create()->format('Y-m-d H:i:s')				
	     			]
		 		);

		    	// Insertando en la tabla

		    	$persona_juridica = new PersonaJuridica();

		    	$persona_juridica->Ruc = $data['Ruc'];
				$persona_juridica->RazonSocial = $data['RazonSocial'];
				$persona_juridica->cDescripcionEmpresa = $data['cDescripcionEmpresa'];
				$persona_juridica->departamento_id = $data['departamento_id'];
				$persona_juridica->provincia_id = $data['provincia_id'];
				$persona_juridica->distrtio_id = $data['distrito_id'];
				$persona_juridica->cDireccionNegocio = $data['cDireccionNegocio'];
				$persona_juridica->nLatitudNegocio = $data['nLatitudNegocio'];
				$persona_juridica->nLongitudNegocio = $data['nLongitudNegocio'];
				$persona_juridica->cPaginaContacto = $data['cPaginaContacto'];
				$persona_juridica->persona_id = $codigo_persona_generado;
				$persona_juridica->created_at = date_create()->format('Y-m-d H:i:s');
				$persona_juridica->updated_at = date_create()->format('Y-m-d H:i:s');
				$persona_juridica->save();

          	DB::commit();

          	return true;  

         } catch(Exception $e)
         {
            DB::rollback();

            return false; 

    	 }

    }
    public static function ListarPersonasJuridicas($datos)
    {

        $query = '';
        
        $records_per_page = 10;
        
        $start_from = 0;
        
        $current_page_number = 0;

        if(isset($_POST["rowCount"]))
        {
         $records_per_page = $datos["rowCount"];
        }
        else
        {
         $records_per_page = 10;
        }

        if(isset($_POST["current"]))
        {
         $current_page_number = $datos["current"];
        }
        else
        {
         $current_page_number = 1;
        }

        $start_from = ($current_page_number - 1) * $records_per_page;
        
        $query .= " SELECT personasjuridicas.id, personasjuridicas.Ruc, 
                          personasjuridicas.RazonSocial,
                          estados.nombre_estado
                    FROM personasjuridicas 
                        inner join personas on personas.id = personasjuridicas.persona_id 
                        inner join estados on estados.id = personas.estado_id";

        if(!empty($_POST["searchPhrase"]))
        {
         $query .= ' WHERE (personasjuridicas.id LIKE "%'.$_POST["searchPhrase"].'%" ';
         $query .= 'OR personasjuridicas.Ruc LIKE "%'.$_POST["searchPhrase"].'%" ';
         $query .= 'OR personasjuridicas.RazonSocial LIKE "%'.$_POST["searchPhrase"].'%" ';
         $query .= 'OR estados.nombre_estado LIKE "%'.$_POST["searchPhrase"].'%" )';
        }

        $order_by = '';

        if(isset($_POST["sort"]) && is_array($_POST["sort"]))
        {
         foreach($_POST["sort"] as $key => $value)
         {
          $order_by .= " $key $value, ";
         }
        }
        else
        {
         $query .= ' ORDER BY personasjuridicas.id DESC ';
        }

        if($order_by != '')
        {
         $query .= ' ORDER BY ' . substr($order_by, 0, -2);
        }

        if($records_per_page != -1)
        {
         $query .= " LIMIT " . $start_from . ", " . $records_per_page .";";
        }


        $results = DB::select($query);


        $total_records = PersonaJuridica::count();


        $output = array(
         'current'  => intval($datos["current"]),
         'rowCount'  => $records_per_page,
         'total'   => intval($total_records),
         'rows'   => $results
        );

        $total_records = null;
        $query = null;
        $records_per_page = null;
        $order_by = null;
        $start_from = null;

        return json_encode($output);

    }
}
