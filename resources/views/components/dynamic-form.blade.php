@props([
    'modelo', 
    'accion', 
    'metodo' => 'POST', 
    'submitText' => 'Guardar',
    'solo' => null,      
    'excepto' => [],     
    'ocultos' => []      
])

@php
    // 1. Determinar qué campos procesar
    if ($solo) {
        $campos = is_array($solo) ? $solo : explode(',', $solo);
    } else {
        $campos = $modelo->exists ? array_keys($modelo->getAttributes()) : $modelo->getFillable();
    }

    // 2. Limpiar campos prohibidos y excluidos
    $sistema = ['id', 'created_at', 'updated_at', 'password', 'remember_token'];
    $quitar = array_merge($sistema, (array) $excepto, array_keys($ocultos));
    
    $camposFinales = array_diff($campos, $quitar);
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
                // Valor actual del campo
                $valor = old($campo, $modelo->$campo ?? '');

                // Determinar el tipo de input (Sustituye ternarias anidadas)
                $tipo = match(true) {
                    str_contains($campo, 'email') => 'email',
                    str_contains($campo, 'date') => 'date',
                    default => 'text'
                };

                // Decidir si es un área de texto
                $esLargo = ($campo === 'description' || $campo === 'body' || strlen($valor) > 100);
            @endphp

            <div class="flex flex-col">
                <label class="block font-bold text-sm text-gray-700 uppercase mb-1">
                    {{ str_replace('_', ' ', $campo) }}
                </label>

                @if ($esLargo)
                    <textarea 
                        name="{{ $campo }}" 
                        rows="3" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                    >{{ $valor }}</textarea>
                @else
                    <input 
                        type="{{ $tipo }}" 
                        name="{{ $campo }}" 
                        value="{{ $valor }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                    >
                @endif

                @error($campo)
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="pt-4">
        <button type="submit" class="bg-teal-600 text-white px-8 py-2 rounded-lg font-bold hover:bg-teal-700 transition shadow-md">
            {{ $submitText }}
        </button>
    </div>
</form>