{{-- Botón de eliminación con confirmación. Solo lo ve el administrador principal; la ruta también está protegida. --}}
@props(['action', 'message' => '¿Eliminar este registro? Esta acción no se puede deshacer.', 'label' => 'Eliminar'])

@if(auth()->user()?->is_super_admin)
    <form method="POST" action="{{ $action }}" data-confirm="{{ $message }}" class="inline">
        @csrf
        <button type="submit" {{ $attributes->merge(['class' => 'rounded-full border border-rose-200 dark:border-rose-500/40 px-3 py-2 text-sm font-medium text-rose-600 dark:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition']) }}>{{ $label }}</button>
    </form>
@endif
