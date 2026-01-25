@props(['datos'])

@php
    $primerRegistro = $datos->first();
    $columnas = $primerRegistro
        ? array_keys(is_array($primerRegistro) ? $primerRegistro : $primerRegistro->toArray())
        : [];
@endphp

<div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm bg-white">
    <div class="flex p-3 bg-gray-50 border-b">
        {{ $datos->links() }}
    </div>

    <table class="min-w-full divide-y divide-gray-200 bg-white">
        <thead class="bg-teal-600">
            <tr>
                @foreach ($columnas as $columna)
                    <th class="text-left px-6 py-3 text-xs font-bold text-white uppercase tracking-wider">
                        {{ str_replace('_', ' ', $columna) }}
                    </th>
                @endforeach
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Ver</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Eliminar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($datos as $fila)
                <tr class="transition-colors hover:bg-teal-50">
                    @foreach ($columnas as $columna)
                        <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-700">
                            {{ $fila[$columna] ?? ($fila->$columna ?? 'Vacío') }}
                        </td>
                    @endforeach
                    <td class="px-6 py-4 text-center whitespace-nowrap text-sm">
                        @can('watch user')
                            <a href="{{ route('user.profile', $fila['ID']) }}"
                                class="inline-block bg-teal-500 text-white text-xl rounded-lg px-4 py-2 transition-all duration-300 transform scale-100 hover:bg-teal-400 hover:scale-105 hover:shadow-md">
                                Ver
                            </a>
                        @endcan
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap text-sm">
                        @can('delete user')
                            <form action="{{ route('user.delete', $fila['ID']) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Confirmar eliminación?')"
                                    class="inline-block bg-red-500 text-white text-xl rounded-lg px-4 py-2 transition-all duration-300 transform scale-100 hover:bg-red-400 hover:scale-105 hover:shadow-md">
                                    Borrar
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columnas) + 2 }}" class="px-6 py-10 text-center text-gray-500 italic">
                        No hay registros para mostrar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
