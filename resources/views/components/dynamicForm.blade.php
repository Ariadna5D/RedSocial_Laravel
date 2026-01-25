@props([
    'datos', 
    'action', 
    'method' => 'POST', 
    'buttonText' => 'Guardar Cambios',
    'exclude' => ['id', 'email_verified_at', 'created_at', 'updated_at']
])

@php
    // Convertimos a array si es un objeto de Eloquent
    $datosArray = is_array($datos) ? $datos : $datos->toArray();
    
    // Filtramos los campos que no queremos que sean editables
    $campos = array_filter(array_keys($datosArray), function($key) use ($exclude) {
        return !in_array($key, $exclude);
    });
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6 bg-white p-6 rounded-xl shadow-md border border-gray-100">
    @csrf
    @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($campos as $campo)
            <div class="flex flex-col">
                <label for="{{ $campo }}" class="text-sm font-bold text-gray-700 uppercase mb-2">
                    {{ str_replace('_', ' ', $campo) }}
                </label>
                
                <input 
                    type="{{ $campo === 'email' ? 'email' : 'text' }}" 
                    id="{{ $campo }}" 
                    name="{{ $campo }}" 
                    value="{{ old($campo, $datosArray[$campo]) }}"
                    class="rounded-lg border-gray-300 shadow-sm focus:ring-teal-500 transition-all"
                >

                @error($campo)
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded-lg transition-colors shadow-lg">
            {{ $buttonText }}
        </button>
    </div>
</form>