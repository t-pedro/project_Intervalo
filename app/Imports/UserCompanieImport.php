<?php
   
namespace App\Imports;
   
use App\Models\User;
use App\Notifications\NuevoUsuarioNotification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Str;
    
class UserCompanieImport implements ToModel,WithHeadingRow
{
    private $idCompanie;
    private $rows = 0;
    private $rowsDuplicados = 0;
    private $registrosDuplicados = '';
    private $registrosImcompletos = '';

    public function __construct($idCompanie)
    {
        $this->idCompanie = $idCompanie;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {      
        ++$this->rows;
        try {
            if($row['email'] != '' && $row['nombre_completo'] != '' && $row['rol_sistema'] != ''){
                if (!$this->comparar_email($row['email'])) {
                    $randomPassword = Str::random(15);
                    
                    $user = User::create([
                        'name' => $row['nombre_completo'],
                        'email' => $row['email'],
                        'password' => bcrypt($randomPassword) 
                    ]);
                    
                    // Asignar Empresa
                    $user->companies()->sync($this->idCompanie);
        
                    // Asignar Rol Empleado
                    $user->roles()->sync(3);

                    $user->notify(new NuevoUsuarioNotification($randomPassword));
        
                }else{
                    ++$this->rowsDuplicados;
                    $this->registrosDuplicados .= ' - Registro N° '. strval($this->rows+1).' - '. $row['email'] .' ya se encuentra registrado en el sistema <br>';
                }                 
            }else{
                $this->registrosImcompletos .= ' - Registro N° '. strval($this->rows+1).' - se encuentra incompleto <br>';
            }
            return;
        } catch (\Throwable $th) {
            
        }
        
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

    function comparar_email($email){
        $user = User::where('email', $email)->first();
        
        if($user){
            return true;
        }else{
            return false;
        }
    }

    
}