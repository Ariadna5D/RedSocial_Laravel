@props([
    'modelo',
    'titulo' => 'title',
    'cuerpo' => 'description',
    'maxChars' => 150,
    'footerInfo' => null,
    'solo' => null,
    'excepto' => [],
])

@php
    $datos = is_array($modelo) ? $modelo : $modelo->toArray();
    
    // Limpiamos datos técnicos para que no salgan en "campos extra"
    $clavesIgnorar = array_merge(
        [$titulo, $cuerpo, 'id', 'created_at', 'updated_at', 'user_id', 'user', 'likes_count'], 
        $excepto
    );

    $camposExtra = collect($datos)->except($clavesIgnorar);

    if ($solo) {
        $camposExtra = $camposExtra->only($solo);
    }
@endphp

<div class="flex flex-col h-full bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="p-6 flex-grow">
        {{-- Título --}}
        <h3 class="text-xl font-bold text-gray-800 mb-3 italic">
            {{ $datos[$titulo] ?? 'Sin título' }}
        </h3>

        {{-- Descripción --}}
        <p class="text-gray-600 text-sm leading-relaxed">
            {{ Str::limit($datos[$cuerpo] ?? '', $maxChars, '...') }}
        </p>

        {{-- Metadatos adicionales automáticos --}}
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

    {{-- Footer --}}
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
        <div class="flex flex-col gap-3">
            @if($footerInfo)
                <span class="text-xs text-gray-500 font-medium italic">
                    {{ $footerInfo }}
                </span>
            @endif

            {{-- AQUÍ VAN LOS BOTONES (SLOT) --}}
            <div class="flex flex-wrap gap-2">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>