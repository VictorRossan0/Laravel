<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar todos os usuários
    public function index(Request $request)
    {
        $searchQuery = $request->input('query');
        $perPage = $request->input('per_page', 10); // Obtém o valor selecionado no <select> ou usa 10 como padrão
        
        $users = User::orderBy('name', 'asc')
            ->when($searchQuery, function ($query, $searchQuery) {
                $query->where('name', 'LIKE', "%$searchQuery%");
            })
            ->paginate($perPage); // Usa o valor de $perPage para definir a quantidade de resultados por página
        
        return view('admin.users.index', compact('users'));
    }

    // Mostrar o formulário de criação de usuário
    public function create()
    {
        return view('admin.users.create');
    }

    // Armazenar um novo usuário no banco de dados
    public function store(Request $request)
    {
        // Valide os dados do formulário aqui, se necessário

        User::create($request->all());

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    // Mostrar um usuário específico
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Mostrar o formulário de edição de usuário
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@globalhitss.com.br') && !str_ends_with($value, '.terceiros@claro.com.br')) {
                        $fail('O endereço de e-mail deve pertencer a @globalhitss.com.br ou .terceiros@claro.com.br');
                    }
                },
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'integer', Rule::in([0, 1, 2])],
            'setor' => ['nullable', 'string'],
            'gestor_imediato' => ['nullable', 'string'],
        ]);

        // Atualize os campos de usuário, exceto a senha, se o campo 'password' estiver vazio
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->type = $request->input('type');
        $user->setor = $request->input('setor');
        $user->gestor_imediato = $request->input('gestor_imediato');

        if ($request->filled('password')) {
            // Atualize a senha se um novo valor for fornecido
            $user->password = Hash::make($request->input('password'));
        }

        // Salve as alterações no banco de dados
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    // Excluir um usuário do banco de dados
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }
}
