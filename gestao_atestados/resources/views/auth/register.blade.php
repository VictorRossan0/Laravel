@extends('auth.layouts.app')

@section('content')
    <section>
        <div class="d-flex justify-content-center align-items-center flex-column mt-2">
            <div><img src="{{ asset('storage/images/logo.jpg') }}" alt=""></div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-2"
                    style="width: 430px; border: 1px solid rgb(232 232 232); border-top: 0; padding: 25px; margin-top: 30px; border-radius: 10px; box-shadow: 3px 2px 2px rgb(232 232 232);">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Nome') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            oninput="capitalizeInput(this)">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Endereço de Email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Senha') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Confirmar Senha') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password">
                    </div>
                    <div class="mb-3">
                        <label for="setor" class="form-label">{{ __('Nome do Projeto') }}</label>
                        <input id="setor" type="text" class="form-control" name="setor" required autocomplete="projeto" value="Agendamento" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="gestor_imediato" class="form-label">{{ __('Gestor Imediato') }}</label>
                        <select id="gestor_imediato" class="form-select" name="gestor_imediato" required>
                            <option value="">Selecione o Gestor Imediato</option>
                            <option value="Daniel Pinto De Almeida">Daniel Pinto De Almeida</option>
                            <option value="Daniela Cristina da Silva">Daniela Cristina da Silva</option>
                            <option value="Mateus Oliveira dos Santos">Mateus Oliveira dos Santos</option>
                            <option value="João Borges Neto">João Borges Neto</option>
                            <!-- Adicione mais opções conforme necessário -->
                        </select>
                    </div>

                    <div class="mb-0">
                        <button type="submit" class="btn btn-primary">{{ __('Registrar') }}</button>
                        <a href="{{ route('login') }}"
                            style="font-size: 14px; color: black; margin-right: 20px;">{{ __('Já é registrado?') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function capitalizeName(string) {
                if (string && typeof string === 'string') {
                    var parts = string.split(' ');
                    for (var i = 0; i < parts.length; i++) {
                        parts[i] = parts[i].charAt(0).toUpperCase() + parts[i].slice(1);
                    }
                    return parts.join(' ');
                } else {
                    return string;
                }
            }

            function capitalizeInput(inputElement) {
                var valorDoInput = inputElement.value;
                if (inputElement.id !== "gestor_imediato") {
                    valorDoInput = capitalizeName(valorDoInput);
                }
                inputElement.value = valorDoInput;
                // console.log("Valor do Input:", valorDoInput);
            }

            var nameInput = document.getElementById("name");
            var setorInput = document.getElementById("setor");
            var gestorInput = document.getElementById("gestor_imediato");

            nameInput.addEventListener("input", function() {
                capitalizeInput(nameInput);
            });

            setorInput.addEventListener("input", function() {
                capitalizeInput(setorInput);
            });

            gestorInput.addEventListener("input", function() {
                capitalizeInput(gestorInput);
            });

            // Adicione mais eventos conforme necessário para outros inputs
        });
    </script>
@endsection
