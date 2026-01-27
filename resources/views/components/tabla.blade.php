@props(['datos', 'configuracion'])

<div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 bg-white">
        <thead class="bg-teal-600">
            <tr>
                @foreach($configuracion as $titulo => $campo)
                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">
                        {{ $titulo }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($datos as $index => $fila)
                <tr class="transition-colors hover:bg-teal-50">
                    @foreach($configuracion as $titulo => $campo)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{-- Lógica para el puesto --}}
                            @if($campo === 'puesto')
                                <span class="font-bold text-gray-400">#{{ $index + 1 }}</span>
                            
                            {{-- Lógica para campos que pueden ser arrays u objetos --}}
                            @else
                                @php
                                    // Intentamos sacar el valor sea objeto o array
                                    $valor = is_array($fila) ? ($fila[$campo] ?? null) : ($fila->$campo ?? null);
                                @endphp
                                
                                {{-- Aquí podrías añadir lógica especial por nombre de columna --}}
                                @if($titulo === 'Nombre')
                                    <span class="font-bold text-teal-700">{{ $valor }}</span>
                                @else
                                    {{ $valor ?? '—' }}
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($configuracion) }}" class="px-6 py-10 text-center text-gray-400 italic">
                        No hay datos disponibles.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>