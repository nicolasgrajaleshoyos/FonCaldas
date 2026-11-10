{{-- Lista con viñeta de nombres (integrantes de comités y órganos de dirección). --}}
@props(['nombres'])

<ul class="space-y-2 text-slate-700 dark:text-slate-300 text-[15px]">
    @foreach ($nombres as $nombre)
        <li class="flex items-start gap-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-[#0C67A3] shrink-0"></span><span>{{ $nombre }}</span></li>
    @endforeach
</ul>
