<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Atestado extends Model
{
    use HasFactory;

    protected $fillable = [
        'atestado_de_horas',
        'atestado_de_dias',
        'dias_uteis',
        'conversao_dias_em_horas',
        'total_de_horas',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($atestado) {
            $atestado->calculateDerivedFields();
        });
    }

    protected function calculateDerivedFields()
    {
        $horasPorDia = 8; // 8 horas por dia útil

        if ($this->atestado_de_horas && $this->atestado_de_dias == 0) {

            // Caso 1: Atestado de horas sem dias úteis
            $this->conversao_dias_em_horas = $this->atestado_de_dias;
            $this->total_de_horas = $this->atestado_de_horas;
    
        } elseif ($this->atestado_de_dias && $this->atestado_de_horas == 0) {

            // Caso 2: Atestado de dias sem horas
            $this->conversao_dias_em_horas = $this->dias_uteis * $horasPorDia;
            $this->total_de_horas = $this->conversao_dias_em_horas;
    
        } elseif ($this->atestado_de_horas && $this->atestado_de_dias) {
            
            // Caso 3: Atestado de horas com dias úteis
            $this->conversao_dias_em_horas = $this->dias_uteis * $horasPorDia;
            $this->total_de_horas = $this->conversao_dias_em_horas + $this->atestado_de_horas;
    
        }
    }

    public function getAtestadoDeHorasAttribute($value)
    {
        return (int) $value;
    }
    
    public function getAtestadoDeHorasFormattedAttribute()
    {   
        if($this->atestado_de_horas >= 0 && $this->atestado_de_horas <= 23){
            $hours = floor($this->atestado_de_horas);
            $minutes = ($this->atestado_de_horas - $hours) * 60;
            return sprintf('%02dh%02dmin', $hours, $minutes);
        }
        elseif($this->atestado_de_horas >= 24){
            $hours = floor($this->atestado_de_horas / 100);
            $minutes = $this->atestado_de_horas % 100;
            return sprintf('%02dh%02dmin', $hours, $minutes);
        }
    }

    public function getConversaoDiasEmHorasFormattedAttribute()
    {
        $hours = floor($this->conversao_dias_em_horas);
        $minutes = ($this->conversao_dias_em_horas - $hours) * 60;
        return sprintf('%02dh%02dmin', $hours, $minutes);
    }

    public function getTotalDeHorasFormattedAttribute()
    {   
        if($this->total_de_horas >= 0 && $this->total_de_horas <= 23){

            $hours = floor($this->total_de_horas);
            $minutes = ($this->total_de_horas - $hours) * 60;
            return sprintf('%02dh%02dmin', $hours, $minutes);
            
        }
        elseif($this->total_de_horas >= 24 && $this->dias_uteis == 0){

            $hours = floor($this->total_de_horas / 100);
            $minutes = $this->total_de_horas % 100;
            return sprintf('%02dh%02dmin', $hours, $minutes);

        }
    }


}