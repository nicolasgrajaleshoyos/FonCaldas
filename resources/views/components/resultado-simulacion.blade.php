@props(['producto', 'datos', 'rutaNueva', 'rutaSalir', 'total' => null, 'nota' => null])

<section aria-labelledby="producto-simulado"
    class="rounded-3xl border border-slate-200 bg-white p-5 font-[family-name:Inter,system-ui,sans-serif] shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">

    <div class="mb-6">
        <p class="text-sm text-slate-600 dark:text-slate-400">Producto</p>
        <h2 id="producto-simulado" class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">
            {{ $producto }}
        </h2>
    </div>

    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
        @foreach ($datos as $dato)
            @php($destacado = $dato['destacado'] ?? false)
            <div @class([
                'min-w-0 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-transparent sm:p-5',
                'dark:bg-slate-900/50' => ! $destacado,
                'dark:bg-sky-950/30' => $destacado,
            ])>
                <dt class="text-sm text-slate-600 dark:text-slate-400">{{ $dato['etiqueta'] }}</dt>
                <dd @class([
                    'mt-2 break-words text-xl font-bold tabular-nums',
                    'text-slate-900 dark:text-white' => ! $destacado,
                    'text-[#0B4870] dark:text-sky-300' => $destacado,
                ])>
                    {{ $dato['valor'] }}
                    @isset($dato['detalle'])
                        <span class="mt-1 block text-xs font-normal text-slate-600 dark:text-slate-400">
                            {{ $dato['detalle'] }}
                        </span>
                    @endisset
                </dd>
            </div>
        @endforeach
    </dl>

    @if ($total !== null)
        <dl class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-sky-900 dark:bg-sky-950/30 sm:p-6">
            <dt class="text-sm text-slate-600 dark:text-slate-300">{{ $total['etiqueta'] }}</dt>
            <dd class="mt-2 break-words text-3xl font-bold tabular-nums text-[#0B4870] dark:text-sky-300">{{ $total['valor'] }}</dd>
        </dl>
    @endif

    @if ($nota)
        <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $nota }}</p>
    @endif

    <aside aria-label="Aviso sobre la simulación"
        class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm leading-relaxed text-slate-700 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-300 sm:px-5">
        <p>{{ $aviso }}</p>
    </aside>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <a href="{{ $rutaNueva }}"
            class="inline-flex items-center rounded-xl bg-[#0B4870] px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#083653] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600">
            Nueva simulación
        </a>
        <a href="{{ $rutaSalir }}"
            class="inline-flex items-center rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">
            Salir
        </a>
    </div>
</section>
