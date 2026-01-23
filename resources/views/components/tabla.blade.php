@props(['datos'])

@php
    $columnas = count($datos) > 0 ? array_keys((array)$datos[0]) : [];
@endphp

<div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 bg-white">
        <thead class="bg-teal-600">
            <tr>
                {{-- TITULOS --}}
                @foreach($columnas as $columna)
                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">
                        {{ str_replace('_', ' ', $columna) }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            {{-- FILAS --}}
            @forelse($datos as $fila)
                <tr class="transition-colors hover:bg-teal-100">
                    @foreach($columnas as $columna)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $fila[$columna] ?? $fila->$columna ?? 'Vacío' }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columnas) }}" class="px-6 py-10 text-center text-gray-500 italic">
                        No hay registros para mostrar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>