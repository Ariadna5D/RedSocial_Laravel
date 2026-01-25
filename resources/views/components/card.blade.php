@props([
    'modelo',              // El objeto/modelo con los datos
    'titulo' => 'title', // Campo que se usará como título
    'cuerpo' => 'description', // Campo que se usará como descripción
    'maxChars' => 150,   // Longitud máxima del texto
    'footerInfo' => null,// Campo opcional para el pie (ej. autor o fecha)
    'solo' => null,      // Si quieres mostrar campos específicos extra
    'excepto' => [],     // Campos a ignorar
    'botones' => []      // Array de botones con: 'texto', 'ruta', 'clase', 'metodo'
])

@php
    // Convertimos a array si es objeto para manejar las llaves dinámicamente
    $datos = is_array($modelo) ? $modelo : $modelo->toArray();
    
    // Filtrar campos adicionales si se requiere
    $camposExtra = collect($datos)->except(array_merge([$titulo, $cuerpo, $footerInfo, 'id', 'created_at', 'updated_at'], $excepto));
    if ($solo) {
        $camposExtra = $camposExtra->only($solo);
    }
@endphp

<div class="flex flex-col h-full bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="p-6 flex-grow">
        {{-- Título --}}
        <h3 class="text-xl font-bold text-gray-800 mb-3">
            {{ $datos[$titulo] ?? 'Sin título' }}
        </h3>

        {{-- Cuerpo con límite de longitud --}}
        <p class="text-gray-600 text-sm leading-relaxed">
            {{ Str::limit($datos[$cuerpo] ?? '', $maxChars, '...') }}
        </p>

        @if($camposExtra->isNotEmpty())
    <div class="mt-4 pt-4 border-t border-dashed border-gray-100">
        @foreach($camposExtra as $llave => $valor)
            @if(!is_array($valor) && !is_object($valor))
                <p class="text-xs text-gray-500">
                    <span class="font-semibold uppercase">{{ str_replace('_', ' ', $llave) }}:</span> {{ $valor }}
                </p>
            @endif
        @endforeach
    </div>
@endif
    </div>

    {{-- Footer con información y botones --}}
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
        <div class="flex flex-col gap-3">
            @if($footerInfo && isset($datos[$footerInfo]))
                <span class="text-xs text-gray-400 italic">Info: {{ $datos[$footerInfo] }}</span>
            @endif

            <div class="flex flex-wrap gap-2">
                @forelse($botones as $boton)
                    @if(($boton['metodo'] ?? 'GET') === 'GET')
                        <a href="{{ $boton['ruta'] }}" class="{{ $boton['clase'] ?? 'bg-teal-500 text-white' }} px-3 py-1 rounded text-sm font-medium transition-colors">
                            {{ $boton['texto'] }}
                        </a>
                    @else
                        {{-- Para métodos DELETE o POST --}}
                        <form action="{{ $boton['ruta'] }}" method="POST" class="inline">
                            @csrf
                            @method($boton['metodo'])
                            <button type="submit" class="{{ $boton['clase'] ?? 'bg-red-500 text-white' }} px-3 py-1 rounded text-sm font-medium">
                                {{ $boton['texto'] }}
                            </button>
                        </form>
                    @endif
                @empty
                    <span class="text-xs text-gray-400">Sin acciones disponibles</span>
                @endforelse
            </div>
        </div>
    </div>
</div>