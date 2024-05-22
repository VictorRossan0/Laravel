<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;


class ColaboradorController extends Controller
{   
    public function __construct()
    {
        $this->middleware('filter.sector');
    }

    public function index(Request $request)
    {
        $queryTerm = $request->input('query');
        $perPage = $request->input('per_page', 10); // Valor padrão de 10

        // Use a consulta conforme necessário, aplicando a pesquisa se um termo de pesquisa for fornecido
        $colaboradores = Colaborador::where(function ($query) use ($queryTerm) {
            $query->where('nome', 'LIKE', "%$queryTerm%")
                ->orWhere('setor', 'LIKE', "%$queryTerm%");
        })->paginate($perPage);

        return view('colaboradores.index', compact('colaboradores'));
    }

    public function create()
    {
        return view('colaboradores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'setor' => 'nullable', // Adicione a validação para o campo 'setor'
            // Adicione outras validações conforme necessário
        ]);

        Colaborador::create([
            'nome' => $request->nome,
            'setor' => $request->setor, // Armazene o setor
            // Preencha outros campos conforme necessário
        ]);

        return redirect()->route('colaboradores.index')->with('success', 'Colaborador cadastrado com sucesso!');
    }

    public function show(Colaborador $colaborador)
    {
        return view('colaboradores.show', compact('colaborador'));
    }

    public function edit(Colaborador $colaborador)
    {
        return view('colaboradores.edit', compact('colaborador'));
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        $request->validate([
            'nome' => 'required',
            'setor' => 'nullable', // Adicione a validação para o campo 'setor'
            // Adicione outras validações conforme necessário
        ]);

        $colaborador->update([
            'nome' => $request->nome,
            'setor' => $request->setor, // Atualize o setor
            // Atualize outros campos conforme necessário
        ]);

        return redirect()->route('colaboradores.index')->with('success', 'Colaborador atualizado com sucesso!');
    }

    public function destroy($id)
    {
        // Encontre o colaborador pelo ID
        $colaborador = Colaborador::findOrFail($id);

        if (auth()->user()->type === 'Admin') {
            // Exclua o colaborador
            $colaborador->delete();

            // Redirecione de volta para a página de lista de colaboradores com uma mensagem de sucesso
            return redirect()->route('colaboradores.index')->with('success', 'Colaborador excluído com sucesso');
        } else {
            // Se o usuário não for um Admin, redirecione de volta com uma mensagem de erro
            return redirect()->back()->with('error', 'Você não tem permissão para excluir atestados.');
        }

    }

    public function deleteSelected(Request $request)
    {
        // Obtenha os IDs dos colaboradores selecionados
        $selectedColaboradores = $request->input('colaboradores');

        // Verifique se pelo menos um colaborador foi selecionado
        if (empty($selectedColaboradores)) {
            return redirect()->route('colaboradores.index')->with('error', 'Selecione pelo menos um colaborador para excluir.');
        }

        // Execute a lógica de exclusão aqui, verificando as permissões
        if (auth()->user()->type === 'Admin') {
            Colaborador::whereIn('id', $selectedColaboradores)->delete();
            // Retorne uma resposta apropriada, como um redirecionamento
            return redirect()->route('colaboradores.index')->with('success', 'Colaboradores selecionados excluídos com sucesso');
        } else {
            return redirect()->route('colaboradores.index')->with('error', 'Você não tem permissão para excluir colaboradores selecionados');
        }
    }

}
