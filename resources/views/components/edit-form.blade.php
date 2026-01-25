@props([
    'modelo',
    'accion',
    'metodo' => 'POST',
    'submitText' => 'Guardar',
    'solo' => null,
    'excepto' => [],
    'ocultos' => [],
])

@php
    // 1. Identificar todos los campos posibles (Fillable + Atributos actuales)
    if ($solo) {
        if (is_array($solo)) {
            $campos = $solo;
        } else {
            $campos = explode(',', $solo);
        }
    } else {
        // Fusionamos lo que se puede rellenar con lo que el modelo ya tiene asignado
        $campos = array_unique(array_merge($modelo->getFillable(), array_keys($modelo->getAttributes())));
    }

    // 2. Definir campos que nunca deben mostrarse como inputs de texto
    $sistema = ['id', 'created_at', 'updated_at', 'password', 'remember_token', 'email_verified_at'];
    $listaNegra = array_merge($sistema, (array) $excepto, array_keys($ocultos));

    // 3. Filtrar la lista final
    $camposFinales = array_diff($campos, $listaNegra);
@endphp

<form method="POST" action="{{ $accion }}" {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @csrf
    @method($metodo)

    {{-- CAMPOS OCULTOS --}}
    @foreach ($ocultos as $nombre => $valor)
        <input type="hidden" name="{{ $nombre }}" value="{{ $valor }}">
    @endforeach

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($camposFinales as $campo)
            @php
                $valor = old($campo, $modelo->$campo ?? '');

                $tipo = match (true) {
                    str_contains($campo, 'email') => 'email',
                    str_contains($campo, 'date') => 'date',
                    str_contains($campo, 'password') => 'password',
                    default => 'text',
                };

                $esLargo = false;
                if ($campo === 'description' || $campo === 'body' || $campo === 'content') {
                    $esLargo = true;
                } elseif (is_string($valor) && strlen($valor) > 100) {
                    $esLargo = true;
                }
            @endphp

            <div class="flex flex-col">
                <label for="{{ $campo }}" class="block font-bold text-sm text-gray-700 uppercase mb-1">
                    {{ str_replace('_', ' ', $campo) }}
                </label>

                @if ($esLargo)
                    <textarea id="{{ $campo }}" name="{{ $campo }}" rows="4"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ $valor }}</textarea>
                @else
                    <input id="{{ $campo }}" type="{{ $tipo }}" name="{{ $campo }}"
                        value="{{ $valor }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                @endif

                @error($campo)
                    <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $slot }}
    </div>

    <div class="pt-4">
        <button type="submit"
            class="bg-teal-600 text-white px-10 py-2 rounded-lg font-bold hover:bg-teal-700 transition shadow-lg active:scale-95">
            {{ $submitText }}
        </button>
    </div>
</form>
