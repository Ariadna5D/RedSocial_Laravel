@props([
    'modelo',
    'titulo' => 'title',
    'cuerpo' => 'description',
    'maxChars' => 150,
    'solo' => null,
    'excepto' => [],
    'botones' => [],
])

@php
    // pasamos el modelo a array
    $datos = is_array($modelo) ? $modelo : $modelo->toArray();

    $nombreAutor = $modelo->user->name ?? ($datos['user']['name'] ?? 'Anónimo');

    $fecha = $modelo->created_at ? $modelo->created_at->diffForHumans() : '';
    $fechaEdicion = $modelo->updated_at ? $modelo->updated_at->diffForHumans() : '';

    $inicial = $modelo->user->pic;
    $color = $modelo->user->theme;

    // quitamos los campos que no queremos mostrar
    $clavesIgnorar = array_merge(
        [
            $titulo,
            $cuerpo,
            'id',
            'created_at',
            'updated_at',
            'user_id',
            'user',
            'likes_count',
            'edited_by',
            'comments',
            'likes',
            'pic',
            'theme',
        ],
        (array) $excepto,
    );

    // añadimos campos que quiero mostrás además de los del modelo
    $camposExtra = collect($datos)->except($clavesIgnorar);
    if ($solo) {
        $camposExtra = $camposExtra->only(is_array($solo) ? $solo : explode(',', $solo));
    }

    // par controlar si el post ha sido editado
    $fueEditado = !empty($modelo->edited_by);

    // para el like, reviso que el usuario si le ha dado like o no
    $yaTieneLike = auth()->check() && $modelo->likes->where('user_id', auth()->id())->isNotEmpty();
@endphp

<div
    class="flex flex-col h-full bg-{{ $color }}-300/30 rounded-xl border border-gray-200 shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
    <div class="p-6 pb-0 ">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div
                    class="bg-{{ $color }}-300 h-10 w-10 rounded-full  flex items-center justify-center text-white font-bold shadow-sm">
                    {{ $inicial }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-900 leading-none">{{ $nombreAutor }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                    {{ $fecha }}
                    @if ($fueEditado)
                        <span class="ml-1 text-teal-600 font-bold uppercase text-[9px] tracking-widest">
                            • Editado por {{ $modelo->edited_by }} ({{ $fechaEdicion }})
                        </span>
                    @endif
                    </p>
                </div>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-2">
            {{ $datos[$titulo] ?? 'Sin título' }}
        </h3>

        <p class="text-gray-600 text-sm leading-relaxed mb-4">
            {{ str($datos[$cuerpo] ?? '')->limit($maxChars) }}
        </p>

        @if ($camposExtra->isNotEmpty())
            <div class="mt-4 py-3 border-t border-dashed border-gray-100 grid grid-cols-2 gap-2">
                @foreach ($camposExtra as $llave => $valor)
                    <div class="text-[11px]">
                        <span
                            class="font-bold uppercase text-gray-400 block mb-0.5">{{ str_replace('_', ' ', $llave) }}</span>
                        <span class="text-gray-700">{{ $valor }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- FOOTER --}}
    <div class=" border-t-2 border-{{ $color }}-300 px-6 py-4 mt-auto bg-gray-50 rounded-b-xl">
        <div class="flex flex-wrap gap-2 items-center">

            @foreach ($botones as $boton)
                @php $metodo = $boton['metodo'] ?? 'GET'; @endphp

                @if ($metodo === 'GET')
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
            {{ $slot }}
        </div>
    </div>
</div>
