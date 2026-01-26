@props([
    'modelo',
    'titulo' => 'title',
    'cuerpo' => 'description',
    'maxChars' => 150,
    'solo' => null,
    'excepto' => [],
    'botones' => [] // Opcional: para definir acciones rápidas
])

@php
    // 1. Preparación de datos del modelo
    $datos = is_array($modelo) ? $modelo : $modelo->toArray();
    
    // 2. Lógica de Autor (intenta obtener de la relación o del array)
    $nombreAutor = $modelo->user->name ?? $datos['user']['name'] ?? 'Anónimo';
    $fecha = $modelo->created_at ? $modelo->created_at->diffForHumans() : '';
    $inicial = strtoupper(substr($nombreAutor, 0, 1));

    // 3. Filtrado de campos extra (limpieza de datos)
    $clavesIgnorar = array_merge(
        [$titulo, $cuerpo, 'id', 'created_at', 'updated_at', 'user_id', 'user', 'likes_count'], 
        (array) $excepto
    );

    $camposExtra = collect($datos)->except($clavesIgnorar);
    if ($solo) {
        $camposExtra = $camposExtra->only(is_array($solo) ? $solo : explode(',', $solo));
    }
@endphp

<div class="flex flex-col h-full bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
    {{-- CABECERA: Autor e Info --}}
    <div class="p-6 pb-0">
        <div class="flex items-center mb-4">
            <div class="h-10 w-10 rounded-full bg-teal-500 flex items-center justify-center text-white font-bold shadow-sm">
                {{ $inicial }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-bold text-gray-900 leading-none">{{ $nombreAutor }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $fecha }}</p>
            </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <h3 class="text-xl font-bold text-gray-800 mb-2">
            {{ $datos[$titulo] ?? 'Sin título' }}
        </h3>

        <p class="text-gray-600 text-sm leading-relaxed">
            {{ Str::limit($datos[$cuerpo] ?? '', $maxChars, '...') }}
        </p>

        {{-- CAMPOS EXTRA (Precio, Ubicación, etc.) --}}
        @if($camposExtra->isNotEmpty())
            <div class="mt-4 pt-4 border-t border-dashed border-gray-100">
                @foreach($camposExtra as $llave => $valor)
                    @if(!is_array($valor) && !is_object($valor))
                        <p class="text-xs text-gray-500">
                            <span class="font-semibold uppercase text-[10px]">{{ str_replace('_', ' ', $llave) }}:</span> 
                            {{ $valor }}
                        </p>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- FOOTER: Acciones (Botones o Slot) --}}
    <div class="px-6 py-4 mt-auto bg-gray-50 border-t border-gray-100 rounded-b-xl">
        <div class="flex flex-wrap gap-2 items-center">
            {{-- 1. Renderizar botones pasados por prop --}}
            @foreach($botones as $boton)
                @php
                    $metodo = $boton['metodo'] ?? 'GET';
                @endphp

                @if($metodo === 'GET')
                    <x-button :href="$boton['ruta']" :variant="$boton['variant'] ?? 'outline'" size="sm">
                        {{ $boton['texto'] }}
                    </x-button>
                @else
                    <form action="{{ $boton['ruta'] }}" method="POST" class="inline">
                        @csrf
                        @method($metodo)
                        <x-button type="submit" :variant="$boton['variant'] ?? 'danger'" size="sm" 
                                  onclick="{{ $metodo === 'DELETE' ? 'return confirm(\'¿Estás seguro?\')' : '' }}">
                            {{ $boton['texto'] }}
                        </x-button>
                    </form>
                @endif
            @endforeach

            {{-- 2. Slot para botones personalizados (Like, etc.) --}}
            {{ $slot }}
        </div>
    </div>
</div>