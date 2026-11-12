@extends('layouts.app')

@section('title', 'Página no encontrada · Foncaldas')

@section('content')
<x-pagina-error codigo="404" titulo="No encontramos esta página"
    mensaje="Es posible que el enlace esté mal escrito o que la página ya no exista. No te preocupes, te ayudamos a volver." />
@endsection
