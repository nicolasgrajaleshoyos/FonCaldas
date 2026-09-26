@extends('layouts.app')

@section('content')

<div class="min-h-screen page-bg py-14">

    <div class="max-w-6xl mx-auto px-6">

        <!-- HEADER -->
        <div class="flex flex-col items-center mb-12">
            <x-mascot class="mb-4" />
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

            <!-- BUSCADOR Y FECHAS: filtran al instante, sin recargar la página -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 mb-8">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div class="md:col-span-2">
                        <label class="text-sm text-slate-600 dark:text-slate-400 mb-1 block">Buscar</label>
                        <div class="relative">
                            <x-icon name="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                            <input type="text" x-model.debounce.150ms="search" placeholder="Buscar por título o descripción..."
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 pl-11 pr-4 py-3 text-slate-600 dark:text-slate-400 outline-none transition focus:border-[#0C67A3] focus:ring-2 focus:ring-[#0C67A3]/20">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-400 mb-1 block">Desde</label>
                        <input type="date" x-model="fromDate"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-3 text-slate-600 dark:text-slate-400 outline-none transition focus:border-[#0C67A3] focus:ring-2 focus:ring-[#0C67A3]/20">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600 dark:text-slate-400 mb-1 block">Hasta</label>
                        <input type="date" x-model="toDate"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-3 text-slate-600 dark:text-slate-400 outline-none transition focus:border-[#0C67A3] focus:ring-2 focus:ring-[#0C67A3]/20">
                    </div>

                </div>

            </div>

            <!-- CATEGORÍAS: chips que activan/desactivan el filtro al instante -->
            <div class="flex flex-wrap gap-2 mb-10">

                <button type="button" @click="category = ''"
                    class="px-3.5 py-2 rounded-full text-xs font-semibold transition whitespace-nowrap"
                    :class="category === '' ? 'bg-[#0B4870] text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-[#0C67A3]/40'">
                    Todas
                </button>

                <template x-for="[slug, label] in Object.entries(categories)" :key="slug">
                    <button type="button" @click="category = (category === slug ? '' : slug)"
                        class="px-3.5 py-2 rounded-full text-xs font-semibold transition whitespace-nowrap"
                        :class="category === slug ? 'bg-[#0B4870] text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-[#0C67A3]/40'"
                        x-text="label">
                    </button>
                </template>

            </div>

            <!-- RESULTADOS -->
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-5" x-text="filtered.length + (filtered.length === 1 ? ' documento encontrado' : ' documentos encontrados')"></p>

            <div class="grid gap-6 md:grid-cols-2" x-show="filtered.length > 0" x-cloak>

                <template x-for="doc in filtered" :key="doc.id">
                    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="doc.title"></h3>

                        <p class="mt-2 text-slate-600 dark:text-slate-400 text-sm" x-text="doc.description"></p>

                        <p class="mt-3 text-slate-500 dark:text-slate-400 text-xs" x-text="'Categoría: ' + doc.category"></p>

                        <p class="text-slate-500 dark:text-slate-400 text-xs" x-text="doc.published_at_display ? 'Publicado: ' + doc.published_at_display : ''"></p>

                        <a :href="doc.file_url" target="_blank"
                           class="mt-4 inline-flex rounded-lg bg-[#0B4870] px-4 py-2 text-sm text-white hover:bg-[#0a3d60] transition">
                            Ver PDF
                        </a>

                    </div>
                </template>

            </div>

            <div x-show="filtered.length === 0" x-cloak class="text-center py-12 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0C67A3]/10 text-[#0C67A3] mb-4">
                    <x-icon name="folder" class="w-7 h-7" />
                </span>
                <p class="text-slate-600 dark:text-slate-400">
                    No se encontraron documentos para estos criterios.
                </p>
            </div>

        </div>

    </div>

</div>

@endsection