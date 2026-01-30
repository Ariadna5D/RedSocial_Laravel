@props(['datos', 'configuracion'])

<div class="overflow-hidden rounded-xl border border-gray-200 shadow-lg bg-white">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gradient-to-r from-teal-400 to-teal-500">
                <tr>
                    @foreach($configuracion as $titulo => $campo)
                        <th class="px-6 py-5 text-left text-sm font-extrabold text-white uppercase tracking-wider">
                            {{ $titulo }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($datos as $index => $fila)
                    @php
                        $puesto = $index + 1;

                        // para estilar diferente el ranking
                        $estiloPuesto = match($puesto) {
                            1 => 'text-4xl font-black text-amber-500',
                            2 => 'text-3xl font-black text-slate-400',
                            3 => 'text-2xl font-black text-orange-400',
                            default => 'text-xl font-bold text-gray-300'
                        };

                        $esPodio = $puesto <= 3;
                    @endphp

                    <tr class="transition-colors hover:bg-teal-100/80 group">
                        @foreach($configuracion as $titulo => $campo)
                            <td class="px-6 py-5 whitespace-nowrap">

                                @if($campo === 'puesto')
                                    <span class="{{ $estiloPuesto }}">
                                        #{{ $puesto }}
                                    </span>

                                @elseif($titulo === 'Nombre')
                                    <div class="flex flex-col">
                                        <span class="transition-colors group-hover:text-black {{ $esPodio ? 'text-2xl font-black text-gray-900' : 'text-lg font-bold text-gray-700' }}">
                                            @php $valor = is_array($fila) ? ($fila[$campo] ?? null) : ($fila->$campo ?? null); @endphp
                                            {{ $valor }}
                                        </span>
                                        @if($puesto === 1)
                                        @endif
                                    </div>

                                @else
                                    @php $valor = is_array($fila) ? ($fila[$campo] ?? null) : ($fila->$campo ?? null); @endphp
                                    <span class="text-lg {{ $esPodio ? 'font-extrabold text-gray-800' : 'font-medium text-gray-600' }} group-hover:text-gray-900 transition-colors">
                                        {{ $valor ?? '—' }}
                                    </span>
                                @endif

                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($configuracion) }}" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-xl">No hay datos disponibles en el ranking.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
