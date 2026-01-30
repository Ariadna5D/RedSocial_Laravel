@props([
    'modelo',
    'titulo' => 'title',
    'cuerpo' => 'description',
    'maxChars' => 150,
    'footerInfo' => null,
    'solo' => null,      // lista blanca
    'excepto' => [],     // lista negra
    'botones' => []      // botones
])

@php
    $datos = is_array($modelo) ? $modelo : $modelo->toArray();

    $camposExtra = collect($datos)->except(array_merge(
        [$titulo, $cuerpo, $footerInfo, 'id', 'created_at', 'updated_at', 'deleted_at'],
        $excepto
    ));

    // si solo tiene contenido, solo se muestra lo que ponga
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
                        <x-button
                            :href="$boton['ruta']"
                            :variant="$boton['variant'] ?? 'primary'"
                            size="sm"
                        >
                            {{ $boton['texto'] }}
                        </x-button>
                    @else
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
