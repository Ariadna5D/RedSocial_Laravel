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
    // 1. PREPARACIÓN DE DATOS
    // Convertimos a array para manejar tanto objetos Eloquent como arrays simples
    $datos = is_array($modelo) ? $modelo : $modelo->toArray();

    // Acceso seguro al nombre del autor (evita errores si el usuario fue eliminado)
    $nombreAutor = $modelo->user->name ?? ($datos['user']['name'] ?? 'Anónimo');
    
    // Carbon (librería de fechas) nos da el formato "hace 2 horas" automáticamente
    $fecha = $modelo->created_at ? $modelo->created_at->diffForHumans() : '';
    $inicial = strtoupper(substr($nombreAutor, 0, 1));

    // 2. FILTRADO DE CAMPOS EXTRA
    // Definimos qué campos NO queremos mostrar en la cuadrícula de detalles
    $clavesIgnorar = array_merge(
        [
            $titulo, $cuerpo, 'id', 'created_at', 'updated_at', 
            'user_id', 'user', 'likes_count', 'edited_by', 'comments','likes',
        ],
        (array) $excepto,
    );

    $camposExtra = collect($datos)->except($clavesIgnorar);
    if ($solo) {
        $camposExtra = $camposExtra->only(is_array($solo) ? $solo : explode(',', $solo));
    }

    // 3. LÓGICA DE EDICIÓN
    // Si el campo edited_by tiene contenido, sabemos que el post fue modificado
    $fueEditado = !empty($modelo->edited_by);

    // 4. LÓGICA DE LIKE (NUEVO)
    // Verificamos si el usuario actual ha dado like usando el método que creamos en el modelo
    $yaTieneLike = auth()->check() && $modelo->likes->where('user_id', auth()->id())->isNotEmpty();
@endphp

<div class="flex flex-col h-full bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
    {{-- CUERPO DEL POST --}}
    <div class="p-6 pb-0">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-teal-500 flex items-center justify-center text-white font-bold shadow-sm">
                    {{ $inicial }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-900 leading-none">{{ $nombreAutor }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $fecha }}
                        @if ($fueEditado)
                            <span class="ml-1 text-teal-600 font-bold uppercase text-[9px] tracking-widest">
                                • Editado por {{ $modelo->edited_by }}
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

        {{-- CAMPOS EXTRA (Se muestran automáticamente si existen) --}}
        @if ($camposExtra->isNotEmpty())
            <div class="mt-4 py-3 border-t border-dashed border-gray-100 grid grid-cols-2 gap-2">
                @foreach ($camposExtra as $llave => $valor)
                    <div class="text-[11px]">
                        <span class="font-bold uppercase text-gray-400 block mb-0.5">{{ str_replace('_', ' ', $llave) }}</span>
                        <span class="text-gray-700">{{ $valor }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- FOOTER / ACCIONES --}}
    <div class="px-6 py-4 mt-auto bg-gray-50 border-t border-gray-100 rounded-b-xl">
        <div class="flex flex-wrap gap-2 items-center">

            {{-- RENDERIZADO DE BOTONES DINÁMICOS (Editar, Eliminar, Ver, etc.) --}}
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

            {{-- El $slot permite inyectar botones personalizados desde el archivo .blade que llame al componente --}}
            {{ $slot }}
        </div>
    </div>
</div>