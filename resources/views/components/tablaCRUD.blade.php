@props(['datos'])


@php

    $primerRegistro = $datos->first();
    $columnas = $primerRegistro
        ? array_keys(is_array($primerRegistro) ? $primerRegistro : $primerRegistro->toArray())
        : [];

@endphp


<div class="overflow-hidden rounded-xl border border-gray-200 shadow-lg bg-white">


    <div class="flex p-3 bg-slate-50 border-b border-gray-100">

        {{ $datos->links() }}

    </div>


    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gradient-to-r from-teal-400 to-teal-500">
                <tr>
                    @foreach ($columnas as $columna)
                        <th class="text-left px-6 py-4 text-xs font-extrabold text-white uppercase tracking-wider">
                            {{ str_replace('_', ' ', $columna) }}
                        </th>
                    @endforeach
                    <th class="px-6 py-4 text-center text-xs font-extrabold text-white uppercase tracking-wider">
                        Acciones</th>
                    <th class="px-6 py-4 text-center text-xs font-extrabold text-white uppercase tracking-wider">Gestión
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($datos as $fila)
                    <tr class="transition-colors hover:bg-teal-100/80 group">
                        @foreach ($columnas as $columna)
                            <td
                                class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                                {{ $fila[$columna] ?? ($fila->$columna ?? '—') }}
                            </td>
                        @endforeach
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @can('watch user')
                                <a href="{{ route('user.profile', $fila['ID'] ?? $fila->ID) }}"
                                    class="inline-flex items-center bg-teal-600 text-white text-sm font-semibold rounded-lg px-4 py-2 shadow-sm transition-all duration-200 hover:bg-teal-500 hover:shadow-md active:scale-95">
                                    Ver Perfil
                                </a>
                            @endcan
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @can('delete user')
                                <form action="{{ route('user.delete', $fila['ID'] ?? $fila->ID) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Confirmar eliminación?')"
                                        class="inline-flex items-center bg-rose-600 text-white text-sm font-bold rounded-lg px-4 py-2 shadow-sm transition-all duration-200 hover:bg-rose-700 hover:shadow-md active:scale-95">
                                        Eliminar
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columnas) + 2 }}" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <p class="italic">No se encontraron registros en la base de datos.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
