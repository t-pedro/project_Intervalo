<?php
   
namespace App\Imports;
   
use App\Models\Competencia;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
    
class CompetenciaImport implements ToModel,WithHeadingRow
{
    private $rows = 0;
    private $rowsDuplicados = 0;
    private $registrosDuplicados = '';
    private $registrosImcompletos = '';

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {      
        ++$this->rows;
        if ($this->comparar_competencia($row['competencia'])) {
            $competencia = new Competencia;
            $competencia->competencia  = $row['competencia'];
            $competencia->definicion  = $row['definicion'];
            $competencia->comportamiento  =$row['comportamiento'];
            $competencia->save();

            if($row['definicion'] == '' || $row['comportamiento'] == ''){
                $this->registrosImcompletos .= 'Registro N° '. strval($this->rows+1).' - '. $row['competencia'] .' posee campos incompletos <br>';
            }

        }else{
            ++$this->rowsDuplicados;
            $this->registrosDuplicados .= ' - Registro N° '. strval($this->rows+1).' - '. $row['competencia'] .' <br>';
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
        }

        $retorno .= $this->registrosDuplicados;
        $retorno .= $this->registrosImcompletos;
        return $retorno;
    }

    function comparar_competencia($competencia){
        $competencia_import = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $this->elimina_acentos($competencia));
        $competencias = Competencia::select('competencia')->get();
        $listado = [];

        foreach ($competencias as $c) {
            $competencia_database = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $this->elimina_acentos($c['competencia']));
            if($competencia_database === $competencia_import){
                return false;
            }
        }
        return true;
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