@props(['datos', 'configuracion'])

<div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 bg-white">
        <thead class="bg-teal-600">
            <tr>
                @foreach ($configuracion as $titulo => $campo)
                    <th class="px-6 py-3 text-left text-md font-bold text-white uppercase tracking-wider">
                        {{ $titulo }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($datos as $index => $fila)
                @if ($index == 0)
                    <tr class="transition-colors bg-gradient-to-r from-amber-300/70  to-amber-200/50 hover:bg-teal-50">
                    @elseif ($index == 1)
                    <tr class="transition-colors bg-gradient-to-r from-gray-300/70  to-gray-200/50 hover:bg-teal-50">
                    @elseif ($index == 2)
                    <tr class="transition-colors bg-gradient-to-r from-orange-300/70  to-orange-200/50 hover:bg-teal-50">
                    @else
                    <tr class="transition-colors bg-gradient-to-r hover:bg-teal-50">
                @endif
                @foreach ($configuracion as $titulo => $campo)
                    <td class="px-6 py-4 whitespace-nowrap text-xl text-gray-700">
                        @if ($campo === 'puesto')
                            <span class="font-bold text-gray-800">#{{ $index + 1 }}</span>
                        @else
                            @php
                                $valor = is_array($fila) ? $fila[$campo] ?? null : $fila->$campo ?? null;
                            @endphp

                            @if ($titulo === 'Nombre')
                                <span class="font-bold text-teal-900">{{ $valor }}</span>
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
