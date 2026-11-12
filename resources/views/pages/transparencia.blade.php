@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <!-- HEADER -->
        <div class="flex flex-col items-center mb-4">
            <x-mascot pose="estadistica" class="mb-6" sol />
            <x-page-header
                icon="shield"
                title="Transparencia y Gobierno Corporativo"
                subtitle="Consulte documentos institucionales, reglamentos, políticas, informes y lineamientos relacionados con el gobierno corporativo, la gestión tecnológica y la transparencia institucional de Foncaldas."
                center />
        </div>

        <div x-data='{
                search: "",
                category: "{{ $selectedCategory }}",
                fromDate: "",
                toDate: "",
                documents: @json($documents),
                categories: @json($categories),
                get filtered() {
                    return this.documents.filter((d) => {
                        if (this.category && d.category_slug !== this.category) return false;
                        if (this.fromDate && (!d.published_at || d.published_at < this.fromDate)) return false;
                        if (this.toDate && (!d.published_at || d.published_at > this.toDate)) return false;
                        if (this.search) {
                            const q = this.search.toLowerCase();
                            return d.title.toLowerCase().includes(q) || (d.description || "").toLowerCase().includes(q);
                        }
                        return true;
                    });
                }
            }'>

            <!-- FILTROS A LA IZQUIERDA (fijos al bajar) y RESULTADOS A LA DERECHA: todo filtra al instante, sin recargar -->
            <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)] lg:items-start">

                <aside class="flex flex-col gap-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-28 dark:border-white/10 dark:bg-slate-800">

                    <div>
                        <label for="t-buscar" class="mb-2 block text-[15px] font-semibold text-slate-800 dark:text-slate-100">Buscar</label>
                        <div class="relative">
                            <x-icon name="search" class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                            <input id="t-buscar" type="text" x-model.debounce.150ms="search" placeholder="Título o descripción…"
                                class="w-full rounded-full border border-slate-300 bg-slate-50 pl-12 pr-4 py-3 text-base text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#0C67A3] focus:bg-white focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-sky-400 dark:focus:bg-slate-900">
                        </div>
                    </div>

                    <!-- CATEGORÍAS: cada una con la cantidad de documentos que tiene -->
                    <div>
                        <p class="mb-2 text-[15px] font-semibold text-slate-800 dark:text-slate-100">Categoría</p>
                        <div class="space-y-1.5">
                            <button type="button" @click="category = ''"
                                class="flex w-full items-center justify-between gap-3 rounded-2xl px-4 py-2.5 text-left text-[15px] font-semibold transition"
                                :class="category === '' ? 'bg-[#0B4870] text-white shadow-md dark:bg-[#0C67A3]' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/10'">
                                Todas
                                <span class="rounded-full px-2 py-0.5 text-xs font-bold" :class="category === '' ? 'bg-white/20' : 'bg-slate-200 dark:bg-white/10'" x-text="documents.length"></span>
                            </button>

                            <template x-for="[slug, label] in Object.entries(categories)" :key="slug">
                                <button type="button" @click="category = (category === slug ? '' : slug)"
                                    class="flex w-full items-center justify-between gap-3 rounded-2xl px-4 py-2.5 text-left text-[15px] font-semibold transition"
                                    :class="category === slug ? 'bg-[#0B4870] text-white shadow-md dark:bg-[#0C67A3]' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/10'">
                                    <span x-text="label"></span>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-bold" :class="category === slug ? 'bg-white/20' : 'bg-slate-200 dark:bg-white/10'" x-text="documents.filter((d) => d.category_slug === slug).length"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-[15px] font-semibold text-slate-800 dark:text-slate-100">Fecha de publicación</p>
                        <div class="grid grid-cols-2 gap-3 lg:grid-cols-1">
                            <div>
                                <label for="t-desde" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Desde</label>
                                <input id="t-desde" type="date" x-model="fromDate"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 outline-none transition focus:border-[#0C67A3] focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:[color-scheme:dark]">
                            </div>
                            <div>
                                <label for="t-hasta" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Hasta</label>
                                <input id="t-hasta" type="date" x-model="toDate"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-900 outline-none transition focus:border-[#0C67A3] focus:ring-4 focus:ring-[#0C67A3]/15 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:[color-scheme:dark]">
                            </div>
                        </div>
                    </div>

                    <button type="button" x-show="search || category || fromDate || toDate" x-cloak
                        @click="search = ''; category = ''; fromDate = ''; toDate = ''"
                        class="w-full rounded-full border-2 border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-[#0B4870] hover:text-[#0B4870] dark:border-white/20 dark:text-slate-200 dark:hover:border-sky-400 dark:hover:text-sky-200">
                        Limpiar filtros
                    </button>

                </aside>

                <!-- RESULTADOS -->
                <div>
                    <p class="mb-5 text-base font-semibold text-slate-700 dark:text-slate-200" x-text="filtered.length + (filtered.length === 1 ? ' documento encontrado' : ' documentos encontrados')"></p>

                    <div class="grid gap-4" x-show="filtered.length > 0" x-cloak>

                        <template x-for="doc in filtered" :key="doc.id">
                            <div class="group relative flex flex-col gap-5 overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B4870]/10 sm:flex-row sm:items-center dark:border-white/10 dark:bg-slate-800">
                                <!-- Barra de color que crece al pasar el mouse -->
                                <span class="absolute inset-y-0 left-0 w-1.5 origin-top scale-y-0 bg-linear-to-b from-[#FACC15] via-[#0B8B7B] to-[#0C67A3] transition-transform duration-300 group-hover:scale-y-100" aria-hidden="true"></span>

                                <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-[#0C67A3] to-[#0B4870] text-white shadow-lg shadow-[#0C67A3]/30 transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                                    <x-icon name="document" class="w-7 h-7" />
                                </span>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                        <span class="inline-flex rounded-full bg-[#0B8B7B]/10 px-2.5 py-0.5 text-xs font-semibold text-[#0B8B7B] dark:bg-teal-400/15 dark:text-teal-300" x-text="doc.category"></span>
                                        <span class="inline-flex items-center gap-1.5 text-sm text-slate-500 dark:text-slate-300" x-show="doc.published_at_display">
                                            <x-icon name="calendar" class="w-4 h-4" />
                                            <span x-text="doc.published_at_display"></span>
                                        </span>
                                    </div>

                                    <h3 class="mt-2 text-lg md:text-xl font-bold text-slate-900 dark:text-white" x-text="doc.title"></h3>

                                    <p class="mt-1 text-base leading-relaxed text-slate-600 dark:text-slate-300" x-text="doc.description"></p>
                                </div>

                                <a :href="doc.file_url" target="_blank" rel="noopener"
                                   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-[#0B4870] px-6 py-3 font-semibold text-white shadow-lg shadow-[#0B4870]/25 transition hover:-translate-y-0.5 hover:bg-[#0a3d60] dark:bg-[#0C67A3] dark:hover:bg-sky-600">
                                    Ver PDF
                                    <x-icon name="arrow-right" class="w-4 h-4 -rotate-45" />
                                </a>

                            </div>
                        </template>

                    </div>

                    <div x-show="filtered.length === 0" x-cloak class="rounded-3xl border border-slate-200 bg-white py-14 text-center dark:border-white/10 dark:bg-slate-800">
                        <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] dark:bg-sky-400/15 dark:text-sky-300 mb-4">
                            <x-icon name="folder" class="w-7 h-7" />
                        </span>
                        <p class="text-base text-slate-600 dark:text-slate-300">
                            No se encontraron documentos para estos criterios.
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection