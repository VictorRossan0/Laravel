<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TipoAtestado extends Model
{
    use HasFactory;

    protected $table = 'tipo_atestados';

    protected $fillable = [
        'tipo_atestado', 'colaborador', 'tipo', 'CID', 'data', 'quantidade_dias', 'horas',
        'minutos', 'data_fim', 'data_retorno', 'obs', 'arquivo','status','user_id', 'motivo_reprovacao',
    ];

    protected $dates = [
        'data', 'data_fim', 'data_retorno', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'data' => 'date',
        'data_fim' => 'date',
        'data_retorno' => 'date',
    ];

    public function aprovar()
    {
        $this->status = 'Aprovado';
        $this->save();
    }

    public function reprovar($motivo)
    {
        $this->status = 'Reprovado';
        $this->motivo_reprovacao = $motivo;
        $this->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->orderBy('name', 'asc');
    }

    public function getHorasFormatAttribute()
    {
        $hours = floor($this->horas);
        $minutes = floor($this->minutos);
        return sprintf('%02dh%02dmin', $hours, $minutes);
    }

    public function getMinutosFormatAttribute()
    {
        return sprintf('%02dmin', $this->minutos);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function diasUteisNoMes($ano, $mes)
    {
        $totalDiasNoMes = Carbon::createFromDate($ano, $mes, 1)->daysInMonth;
        $diasUteis = 0;

        // Array com feriados nacionais
        $feriados = [
            '01-01', // Ano Novo
            '21-04', // Tiradentes
            '01-05', // Dia do Trabalho
            '07-09', // Independência do Brasil
            '12-10', // Nossa Senhora Aparecida
            '02-11', // Finados
            '15-11', // Proclamação da República
            '25-12', // Natal
            // Adicione outros feriados conforme necessário
        ];

        for ($dia = 1; $dia <= $totalDiasNoMes; $dia++) {
            $data = Carbon::createFromDate($ano, $mes, $dia);

            // Se não for sábado (6) nem domingo (0) e não for feriado, conta como dia útil
            if ($data->dayOfWeek !== 6 && $data->dayOfWeek !== 0 && !in_array($data->format('d-m'), $feriados)) {
                $diasUteis++;
            }
        }

        return $diasUteis;
    }

    public function calcularDiasTrabalhados()
    {
        // Obtenha o ano e mês do atestado
        $ano = $this->data->year;
        $mes = $this->data->month;

        // Calcule os dias úteis do mês/ano do atestado
        $diasUteis = $this->diasUteisNoMes($ano, $mes);

        // Calcule os dias trabalhados com base nos dias úteis e na quantidade de dias do atestado
        $diasTrabalhados = $this->quantidade_dias * $diasUteis;

        return $diasTrabalhados;
    }

    public function calcularAbsenteismoMensal()
    {
        // Obtenha o ano e mês do atestado
        $ano = $this->data->year;
        $mes = $this->data->month;

        // Calcule os dias úteis do mês/ano do atestado
        $diasUteis = $this->diasUteisNoMes($ano, $mes);

        // Calcule os dias trabalhados com base nosdias úteis e na quantidade de dias do atestado
        $diasTrabalhados = $this->quantidade_dias * $diasUteis;

        // Calcule o absenteísmo mensal com base nos dias trabalhados e na carga horária mensal
        $cargaHorariaMensal = $diasUteis * 8; // Assumindo 8 horas por dia de trabalho
        $absenteismoMensal = ($diasTrabalhados / $cargaHorariaMensal) * 100;

        return $absenteismoMensal;
    }

    public function atingiuLimiteAbsenteismo()
    {
        return $this->calcularAbsenteismoMensal() > 4;
    }

    function calcularAbsenteismo($atestados, $mesAtual) {
        $targetpercent = 0.04;

        $atestadosAprovados = $atestados
            ->filter(function ($atestado) use ($mesAtual) {
                return $atestado->status == 'Aprovado' &&
                    Carbon::parse($atestado->data)->month == $mesAtual &&
                    ($atestado->tipo_atestado == 'Horas' ||
                        ($atestado->tipo_atestado == 'Licenca Medica' ||
                            ($atestado->tipo_atestado == 'Licenca CLT' &&
                                Carbon::parse($atestado->data)->isWeekday())));
            })
            ->count();

        $totalAbsenteismo = $atestadosAprovados * $targetpercent;
        $ultrapassouTarget = $totalAbsenteismo > $targetpercent;

        return [
            'atestadosAprovados' => $atestadosAprovados,
            'totalAbsenteismo' => $totalAbsenteismo,
            'ultrapassouTarget' => $ultrapassouTarget,
        ];
    }

}
