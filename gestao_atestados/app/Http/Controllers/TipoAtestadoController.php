<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoAtestado;
use Carbon\Carbon; // Certifique-se de incluir o namespace do Carbon no início do seu Controller
use Illuminate\Support\Facades\Storage;
use App\Models\User; // Certifique-se de importar o modelo User
use Illuminate\Support\Facades\Mail; // Importe a classe Mail
use App\Mail\AtestadoCriado; // Importe a classe de e-mail AtestadoCriado
use App\Mail\AtestadoReprovado;
use App\Mail\AtestadoAprovado;
use Illuminate\Support\Facades\DB;

class TipoAtestadoController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('query');
        $usuario = auth()->user(); // Obtém o usuário autenticado

        // Inicialize a consulta
        $query = TipoAtestado::query();

        // Se o usuário não for um gerente ou admin, filtre apenas os atestados dele
        if ($usuario->type !== 'Gerente' && $usuario->type !== 'Admin') {
            $query->where('user_id', $usuario->id);
        }

        if (!empty($searchQuery)) {
            $query->where(function ($query) use ($searchQuery) {
                if (!empty($searchQuery['colaborador'])) {
                    $query->whereHas('user', function ($query) use ($searchQuery) {
                        $query->where('colaborador', 'LIKE', "%{$searchQuery['colaborador']}%");
                    });
                }

                if (!empty($searchQuery['data_atestado'])) {
                    $query->orWhere('data', 'LIKE', "%{$searchQuery['data_atestado']}%");
                }

                if (!empty($searchQuery['data_cadastro'])) {
                    $query->orWhere('created_at', 'LIKE', "%{$searchQuery['data_cadastro']}%");
                }

                if (!empty($searchQuery['tipo_atestado'])) {
                    $query->orWhere('tipo_atestado', 'LIKE', "%{$searchQuery['tipo_atestado']}%");
                }

                if (!empty($searchQuery['anexo'])) {
                    $query->orWhere('arquivo', 'LIKE', "%{$searchQuery['anexo']}%");
                }

                if (!empty($searchQuery['status'])) {
                    $query->orWhere('status', 'LIKE', "%{$searchQuery['status']}%");
                }
            });
        }

        // Paginar os resultados
        $tipoAtestados = $query->paginate(50);

        return view('tipo_atestado.index', compact('tipoAtestados', 'usuario'));
    }
    public function create()
    {
        $user = auth()->user();

        if ($user) {
            $type = $user->type;
            $name = $user->name;
            $setor = $user->setor;
            $gestor = $user->gestor_imediato;

            if ($type === 'Admin') {
                $colaboradores = User::all();
            } elseif ($type === 'Gerente') {
                $colaboradores = User::where('setor', $setor)
                    ->orWhere('gestor_imediato', $gestor)
                    ->get();
            } else {
                // Para Analista, apenas o próprio nome do usuário
                $colaboradores = [compact('name')];
            }
        } else {
            // Usuário não autenticado, tratamento adequado aqui
            $colaboradores = [];
        }

        return view('tipo_atestado.create', compact('colaboradores'));
    }

    public function store(Request $request)
    {
        // Valide os dados de entrada
        $validatedData = $request->validate([
            'tipo_atestado' => 'required',
            'tipo' => 'required',
            'CID' => 'nullable',
            'colaborador' => 'required',
            'data' => 'required|date_format:d-m-Y',
            'quantidade_dias' => 'nullable',
            'horas' => 'nullable',
            'minutos' => 'nullable',
            'data_fim' => 'nullable|date_format:d-m-Y',
            'data_retorno' => 'nullable|date_format:d-m-Y',
            'obs' => 'nullable',
            'arquivo' => 'required|file|mimes:pdf,jpeg,jpg,png',
        ]);

        $validatedData['data'] = Carbon::createFromFormat('d-m-Y', $request->data)->format('Y-m-d');

        if ($validatedData['tipo_atestado'] !== 'Horas') {
            // Se a opção selecionada não for "Horas", processe os campos data_fim e data_retorno
            if ($request->has('data_fim')) {
                $validatedData['data_fim'] = Carbon::createFromFormat('d-m-Y', $request->data_fim)->format('Y-m-d');
            }

            if ($request->has('data_retorno')) {
                $validatedData['data_retorno'] = Carbon::createFromFormat('d-m-Y', $request->data_retorno)->format('Y-m-d');
            }
        }

        // Upload e salvamento do arquivo
        if ($request->hasFile('arquivo')) {
            $file = $request->file('arquivo');
            $extension = $file->getClientOriginalExtension(); // Obtenha a extensão original do arquivo
            $fileName = time() . '_' . uniqid() . '.' . $extension; // Crie um nome único
            $filePath = $file->storeAs('documentos', $fileName, 'public'); // Armazene em 'documentos' ou qualquer outro diretório desejado

            $validatedData['arquivo'] = $filePath;
        }

        $user = auth()->user();
        $validatedData['user_id'] = $user->id;

        $supervisor = null;

        if ($user->type === 'Analista') {
            // Se o usuário é Analista (tipo 0), encontre o supervisor com o mesmo setor
            $supervisor = User::where('type', 2)->where('setor', $user->setor)->first();
        } elseif ($user->type === 'Gerente') {
            // Se o usuário é Gerente (tipo 2), ele mesmo é o supervisor
            $supervisor = $user;
        }

        // Crie o atestado
        $atestado = TipoAtestado::create($validatedData);

        // Enviar e-mail para o supervisor
        if ($supervisor && $supervisor->email) {
            $supervisorsEmails = User::where('type', 2)->where('setor', $supervisor->setor)->pluck('email')->toArray();

            // Remover o supervisor principal da lista de supervisores em cópia
            $supervisorPrincipalEmail = array_shift($supervisorsEmails);

            Mail::to($supervisorPrincipalEmail)
                ->cc($supervisorsEmails)
                ->send(new AtestadoCriado($atestado, $supervisor->name));
        }

        // Redirecione para a rota de índice
        return redirect()->route('tipo_atestado.index')->with('status', 'Atestado criado com sucesso!');
    }

    public function show(TipoAtestado $tipoAtestado)
    {
        return view('tipo_atestado.show', compact('tipoAtestado'));
    }

    public function showFile(TipoAtestado $tipoAtestado)
    {
        // Verifique se o arquivo existe
        if (Storage::disk('public')->exists($tipoAtestado->arquivo)) {
            $filePath = storage_path('app/public/' . $tipoAtestado->arquivo);

            $extension = pathinfo($tipoAtestado->arquivo, PATHINFO_EXTENSION);

            // Defina os cabeçalhos apropriados com base no tipo de arquivo
            $headers = [
                'Content-Type' => $this->getMimeType($extension),
                'Content-Disposition' => 'inline; filename="' . $tipoAtestado->arquivo . '"',
            ];

            // Retorne o arquivo como resposta
            return response()->file($filePath, $headers);
        }

        // Caso o arquivo não seja encontrado, redirecione para a página anterior ou faça alguma outra ação
        return back()->with('error', 'Arquivo não encontrado.');
    }

    private function getMimeType($extension)
    {
        switch ($extension) {
            case 'pdf':
                return 'application/pdf';
            case 'jpeg':
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            default:
                return 'application/octet-stream'; // Tipo de conteúdo padrão para outros tipos de arquivo
        }
    }

    public function aprovar($id)
    {
        // Encontre o atestado pelo ID
        $atestado = TipoAtestado::findOrFail($id);

        // Faça a aprovação do atestado (você deve ter um método aprovar em seu modelo TipoAtestado)
        $atestado->aprovar();

        // Enviar o email de notificação de aprovação
        $analistaName = $atestado->user->name;
        $email = new AtestadoAprovado($atestado, $analistaName);
        Mail::to($atestado->user->email)->send($email);

        return redirect()->back();
    }

    public function reprovar(Request $request, $id)
    {
        $atestado = TipoAtestado::findOrFail($id);
        $motivo = $request->input('motivoReprovacao');
        $atestado->reprovar($motivo);
        $analistaName = $atestado->user->name;

        Mail::to($atestado->user->email)->send(new AtestadoReprovado($atestado, $analistaName, $motivo));

        return redirect()->back(); // Redireciona para a página de lista de atestados após a reprovação.
    }

    public function showReprovarForm($id)
    {
        $atestado = TipoAtestado::findOrFail($id);
        return view('reprovar_form', ['atestado' => $atestado]);
    }

    public function destroy($id)
    {
        // Encontre o atestado pelo ID
        $tipoAtestado = TipoAtestado::findOrFail($id);

        // Verifique se o usuário atual é um Admin
        if (auth()->user()->type === 'Admin') {
            // Exclua o atestado
            $tipoAtestado->delete();

            // Redirecione de volta para a página de lista de atestados com uma mensagem de sucesso
            return redirect()->route('tipo_atestado.index')->with('success', 'Atestado excluído com sucesso');
        } else {
            // Se o usuário não for um Admin, redirecione de volta com uma mensagem de erro
            return redirect()->back()->with('error', 'Você não tem permissão para excluir atestados.');
        }
    }

    public function deleteSelected(Request $request)
    {
        // Obtenha os IDs dos atestados selecionados
        $selectedAtestados = $request->input('atestados');

        // Verifique se o usuário atual é um Admin
        if (auth()->user()->type === 'Admin') {
            // Execute a lógica de exclusão aqui, por exemplo:
            TipoAtestado::whereIn('id', $selectedAtestados)->delete();

            // Retorne uma resposta apropriada, como um redirecionamento
            return redirect()->route('tipo_atestado.index');
        } else {
            // Se o usuário não for um Admin, redirecione de volta com uma mensagem de erro
            return redirect()->back()->with('error', 'Você não tem permissão para excluir atestados selecionados.');
        }
    }

    public function calcularAbsenteismoEquipe(Request $request)
    {
        // Obtenha a quantidade de pessoas na equipe
        $quantidadeEquipe = User::whereIn('type', ['0', '2'])->count();

        // Obtenha os dados de cada analista e gerente do Setor Agendamento e Buffer TI, ordenando pelo nome do analista
        $usuarios = User::whereIn('setor', ['Agendamento', 'Buffer TI'])
            ->where('type', '!=', '1')
            ->orderBy('name')
            ->when($request->input('query'), function ($query, $searchQuery) {
                // Filtra os usuários pelo nome, se o parâmetro de busca estiver presente
                $query->where('name', 'LIKE', "%$searchQuery%");
            })
            ->get();

        $absenteismoEquipe = [];

        foreach ($usuarios as $usuario) {
            $mesAtual = Carbon::now()->month;
            $anoAtual = Carbon::now()->year;

            // Calcula o número de dias úteis no mês atual
            $diasUteis = 0;
            $primeiroDia = Carbon::createFromDate($anoAtual, $mesAtual, 1);

            for ($i = 1; $i <= $primeiroDia->daysInMonth; $i++) {
                $dia = $primeiroDia->copy()->addDays($i - 1);
                if (!$dia->isWeekend()) {
                    $diasUteis++;
                }
            }

            // Obtém os atestados aprovados para o usuário no mês atual
            $atestadosAprovados = TipoAtestado::where('user_id', $usuario->id)
                ->where('status', 'Aprovado')
                ->whereMonth('data', $mesAtual)
                ->get();

            $totalAbsenteismoHoras = 0;

            foreach ($atestadosAprovados as $atestado) {
                if ($atestado->tipo_atestado === 'Horas') {
                    // Para atestados do tipo 'Horas', calcula o total de horas de ausência
                    $totalAbsenteismoHoras += $atestado->horas + $atestado->minutos / 60;
                } elseif ($atestado->tipo_atestado === 'Licenca Medica' || $atestado->tipo_atestado === 'Licenca CLT') {
                    // Para atestados do tipo 'Licenca Medica' ou 'Licenca CLT', calcula o total de dias de ausência
                    $totalAbsenteismoHoras += $atestado->quantidade_dias * 8; // Considera 8 horas por dia
                }
            }

            // Calcula o limite de faltas tolerado em horas
            $limiteFaltasTolerado = $diasUteis * 8 * 0.04;
            // Calcula o total de absenteísmo em percentual para o usuário no mês atual
            $totalAbsenteismoPercent = ($totalAbsenteismoHoras / ($diasUteis * 8)) * 100;

            // Adiciona os dados do absenteísmo do usuário ao array da equipe
            $absenteismoEquipe[] = [
                'id' => $usuario->id,
                'analista' => $usuario->name,
                'absenteismo' => $totalAbsenteismoPercent,
                'ultrapassou_target' => $totalAbsenteismoPercent > 4, // Compara com o targetpercent
                'atestados_aprovados' => $atestadosAprovados->count(),
                'mes' => $mesAtual,
                'ano' => $anoAtual,
            ];
        }

        // Calcula o total de absenteísmo para toda a equipe
        $totalAbsenteismoEquipe = collect($absenteismoEquipe)->sum('absenteismo');

        $searchQuery = $request->input('query');

        // Retornar o resultado para a view, incluindo o total de absenteísmo
        return view('tipo_atestado.components.absenteismo-equipe', compact('absenteismoEquipe', 'quantidadeEquipe', 'totalAbsenteismoEquipe'));
    }


    public function puxarDadosTipoAtestados(Request $request)
    {
        try {
            // Obtenha a quantidade de pessoas na equipe
            $quantidadeEquipe = User::whereIn('type', ['0', '2'])->count();

            // Obtenha os dados de cada analista e gerente do Setor Agendamento e Buffer TI, ordenando pelo nome do analista
            $usuarios = User::whereIn('setor', ['Agendamento', 'Buffer TI'])
                ->where('type', '!=', '1')
                ->orderBy('name')
                ->get();

            $absenteismoEquipe = [];

            foreach ($usuarios as $usuario) {
                $mesAtual = Carbon::now()->month;
                $anoAtual = Carbon::now()->year;

                // Calcula o número de dias úteis no mês atual
                $diasUteis = 0;
                $primeiroDia = Carbon::createFromDate($anoAtual, $mesAtual, 1);

                for ($i = 1; $i <= $primeiroDia->daysInMonth; $i++) {
                    $dia = $primeiroDia->copy()->addDays($i - 1);
                    if (!$dia->isWeekend()) {
                        $diasUteis++;
                    }
                }

                // Obtém os atestados aprovados para o usuário no mês atual
                $atestadosAprovados = TipoAtestado::where('user_id', $usuario->id)
                    ->where('status', 'Aprovado')
                    ->whereMonth('data', $mesAtual)
                    ->get();

                $totalAbsenteismoHoras = 0;

                foreach ($atestadosAprovados as $atestado) {
                    if ($atestado->tipo_atestado === 'Horas') {
                        // Para atestados do tipo 'Horas', calcula o total de horas de ausência
                        $totalAbsenteismoHoras += $atestado->horas + $atestado->minutos / 60;
                    } elseif ($atestado->tipo_atestado === 'Licenca Medica' || $atestado->tipo_atestado === 'Licenca CLT') {
                        // Para atestados do tipo 'Licenca Medica' ou 'Licenca CLT', calcula o total de dias de ausência
                        $totalAbsenteismoHoras += $atestado->quantidade_dias * 8; // Considera 8 horas por dia
                    }
                }

                // Calcula o total de absenteísmo em percentual para o usuário no mês atual
                $totalAbsenteismoPercent = ($totalAbsenteismoHoras / ($diasUteis * 8)) * 100;

                // Adiciona os dados do absenteísmo do usuário ao array da equipe
                $absenteismoEquipe[] = [
                    'id' => $usuario->id,
                    'analista' => $usuario->name,
                    'absenteismo' => $totalAbsenteismoPercent,
                    'ultrapassou_target' => $totalAbsenteismoPercent > 4, // Compara com o targetpercent
                    'atestados_aprovados' => $atestadosAprovados->count(),
                    'mes' => $mesAtual,
                    'ano' => $anoAtual,
                    'target' => 4
                ];
            }

            // Agrupe os valores que você deseja retornar em um array associativo
            $dados = collect($absenteismoEquipe)->values();

            // return response()->json($dados, 200);
            return $dados;
        } catch (\Exception $e) {
            // Lide com erros de maneira adequada
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
