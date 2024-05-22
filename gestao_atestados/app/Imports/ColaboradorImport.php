<?php

namespace App\Imports;

use App\Models\Colaborador;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
class ColaboradorImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        return new Colaborador([
            'nome' => $row['nome'],
            'setor' => $row['setor'], // Certifique-se de que a coluna 'setor' exista no arquivo
            // Adicione outros campos conforme necessário
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => 'required',
            'setor' => [
                'nullable', // O campo 'setor' é opcional
                Rule::in(['Agendamento', 'Configuração', 'Aprovisionamento', 'Buffer TI', 'Adoção', 'Centro Tático', 'Outro Setor']), // Valores permitidos
            ],
            // Adicione regras de validação para outros campos conforme necessário
        ];
    }
}
