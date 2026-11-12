{{-- Botón de eliminación con confirmación. Solo lo ve el administrador principal; la ruta también está protegida. --}}
@props(['action', 'message' => '¿Eliminar este registro? Esta acción no se puede deshacer.', 'label' => 'Eliminar'])

@if(auth()->user()?->is_super_admin)
    <form method="POST" action="{{ $action }}" data-confirm="{{ $message }}" class="inline">
        @csrf
        <button type="submit" {{ $attributes->merge(['class' => 'adm-btn-peligro']) }}>{{ $label }}</button>
    </form>
@endif
