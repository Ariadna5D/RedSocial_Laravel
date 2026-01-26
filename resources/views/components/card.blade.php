@props([
    'modelo',            // El objeto Eloquent o Array con los datos.
    'titulo' => 'title', // El nombre de la columna que hace de título (por defecto 'title').
    'cuerpo' => 'description', // Columna para el texto principal.
    'maxChars' => 150,   // Si el texto es muy largo, lo corta automáticamente.
    'footerInfo' => null,// Columna extra para mostrar abajo (ej: 'fecha').
    'solo' => null,      // Lista blanca de campos extra a mostrar.
    'excepto' => [],     // Lista negra de campos a ocultar.
    'botones' => []      // Array de configuración para los botones de acción.
])

@php
    // Convertimos el modelo a array para poder acceder a las llaves como $datos['columna']
    $datos = is_array($modelo) ? $modelo : $modelo->toArray();
    
    // FILTRADO DINÁMICO:
    // 1. Tomamos todos los datos.
    // 2. Quitamos los que ya usamos en el título/cuerpo y los IDs/fechas del sistema.
    // 3. Quitamos lo que tú digas en 'excepto'.
    $camposExtra = collect($datos)->except(array_merge(
        [$titulo, $cuerpo, $footerInfo, 'id', 'created_at', 'updated_at', 'deleted_at'], 
        $excepto
    ));

    // Si definiste 'solo', nos quedamos únicamente con esos.
    if ($solo) {
        $camposExtra = $camposExtra->only(is_array($solo) ? $solo : explode(',', $solo));
    }
@endphp
<div class="flex flex-col h-full bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="p-6 flex-grow">
        <h3 class="text-xl font-bold text-gray-800 mb-3">
            {{ $datos[$titulo] ?? 'Sin título' }}
        </h3>

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

    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
        <div class="flex flex-col gap-3">
            @if($footerInfo && isset($datos[$footerInfo]))
                <span class="text-xs text-gray-400 italic">Info: {{ $datos[$footerInfo] }}</span>
            @endif

            <div class="flex flex-wrap gap-2">
                @forelse($botones as $boton)
                    @if(($boton['metodo'] ?? 'GET') === 'GET')
                        {{-- USAMOS EL NUEVO COMPONENTE COMO ENLACE --}}
                        <x-button 
                            :href="$boton['ruta']" 
                            :variant="$boton['variant'] ?? 'primary'" 
                            size="sm"
                        >
                            {{ $boton['texto'] }}
                        </x-button>
                    @else
                        {{-- USAMOS EL NUEVO COMPONENTE COMO BOTÓN DE FORMULARIO --}}
                        <form action="{{ $boton['ruta'] }}" method="POST" class="inline">
                            @csrf
                            @method($boton['metodo'])
                            <x-button 
                                type="submit" 
                                :variant="$boton['variant'] ?? 'danger'" 
                                size="sm"
                            >
                                {{ $boton['texto'] }}
                            </x-button>
                        </form>
                    @endif
                @empty
                    <span class="text-xs text-gray-400">Sin acciones disponibles</span>
                @endforelse
            </div>
        </div>
    </div>
</div>