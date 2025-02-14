<?php
   
namespace App\Imports;
   
use App\Models\Afirmation;
use App\Models\Competencia;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
    
class AfirmationImport implements ToModel,WithHeadingRow
{
    private $rows = 0;
    private $rowsDuplicados = 0;
    private $rowsCompetenciasNoRegistrada = 0;
    private $registrosDuplicados = '';
    private $competenciasNoRegistrada = '';

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->rows;
        // VERIFICO LAS COMPETENCIAS
        $cant_competencias = Competencia::count();
        $competencias = [];
        // Comparo la existencia de la competencia.
        for($i = 1; $cant_competencias; $i++) {
            if($row[$i] != ''){
               // $id = Competencia::select('id')->where('competencia', $row[$i])->get();
                $id = $this->comparar_competencia($row[$i]);
                if ($id > 0) {
                    $competencias[$i-1] = $id;
                }else{
                    $competencia = new Competencia;
                    $competencia->competencia  = $row[$i];
                    $competencia->save();
                    ++$this->rowsCompetenciasNoRegistrada;
                    $this->competenciasNoRegistrada .= ' - Compentencia en Registro N° '. strval($this->rows+1).' - '. $row[$i] .' ha sido registrada.<br>';
                }
            }else{
                break;
            }
        }

        // VERIFICO LA EXISTENCIA DE LA AFIRMACION
        if ($this->comparar_afirmaciones($row['afirmacion'])) {

                $afirmation = new Afirmation;
                $afirmation->text         =  $row['afirmacion'];
                $afirmation->ponderacion  = $row['ponderacion'];
                
                $afirmation->save();
                $afirmation->competencias()->sync($competencias);
            }else{
                ++$this->rowsDuplicados;
                $this->registrosDuplicados .= ' - Registro N° '. strval($this->rows+1).' - '. $row['afirmacion'] .' <br>';
            }      
        return;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }   

    public function getStatus() 
    {
        $retorno = 'Se han procesado un total de '.strval($this->rows).' registros <br>';

        if($this->rowsDuplicados > 0){
            $retorno .= 'Se ha detectado un total de '.strval($this->rowsDuplicados). ' registros duplicados <br>';
            $retorno .= $this->registrosDuplicados;
        }

        if($this->rowsCompetenciasNoRegistrada > 0){
            $retorno .= 'Se ha detectado un total de '.strval($this->rowsCompetenciasNoRegistrada). ' competencias no registradas <br>';
            $retorno .= $this->competenciasNoRegistrada;
        }
        
        
        return $retorno;
    }

    function comparar_afirmaciones($afirmacion){
        $afirmacion_import = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $this->elimina_acentos($afirmacion));
        $afirmaciones = Afirmation::select('text')->get();
        $listado = [];

        foreach ($afirmaciones as $a) {
            $afirmacion_database = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $this->elimina_acentos($a['text']));
            if($afirmacion_database === $afirmacion_import){
                return false;
            }
        }
        return true;
    }

    function comparar_competencia($competencia){
        $competencia_import = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $this->elimina_acentos($competencia));
        $competencias = Competencia::get();
        $listado = [];

        foreach ($competencias as $c) {
            $competencia_database = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $this->elimina_acentos($c->competencia));
            if($competencia_database === $competencia_import){
                return $c->id;
            }
        }
        return 0;
    }

    function elimina_acentos($text)
    {
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        $text = strtolower($text);
        $patron = array (
            '/\+/' => '',
            '/&agrave;/' => 'a',
            '/&egrave;/' => 'e',
            '/&igrave;/' => 'i',
            '/&ograve;/' => 'o',
            '/&ugrave;/' => 'u',
 
            '/&aacute;/' => 'a',
            '/&eacute;/' => 'e',
            '/&iacute;/' => 'i',
            '/&oacute;/' => 'o',
            '/&uacute;/' => 'u',
 
            '/&acirc;/' => 'a',
            '/&ecirc;/' => 'e',
            '/&icirc;/' => 'i',
            '/&ocirc;/' => 'o',
            '/&ucirc;/' => 'u',
 
            '/&atilde;/' => 'a',
            '/&etilde;/' => 'e',
            '/&itilde;/' => 'i',
            '/&otilde;/' => 'o',
            '/&utilde;/' => 'u',
 
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
 
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
 
            '/&aring;/' => 'a',
            '/&ntilde;/' => 'n',
        );
 
        $text = preg_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }

}