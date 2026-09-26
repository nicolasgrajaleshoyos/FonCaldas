<?php

use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentoController;
use App\Http\Controllers\Admin\EventoController as AdminEventoController;
use App\Http\Controllers\Admin\PqrsController as AdminPqrsController;
use App\Http\Controllers\Admin\SolicitudController as AdminSolicitudController;
use App\Http\Controllers\Admin\TramiteTipoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PqrsController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\TransparenciaController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::view('/servicios', 'pages.servicios')->name('servicios');

Route::view('/servicios/ahorro', 'pages.servicios.ahorro')->name('servicios.ahorro');

Route::view('/servicios/creditos', 'pages.servicios.creditos')->name('servicios.creditos');

Route::view('/servicios/bienestar', 'pages.servicios.bienestar')->name('servicios.bienestar');

Route::view('/servicios/convenios', 'pages.servicios.convenios')->name('servicios.convenios');

Route::view('/contacto', 'pages.contacto')->name('contacto');

Route::view('/terminos-y-condiciones', 'pages.terminos')->name('terminos');

Route::get('/eventos', EventoController::class)->name('eventos');

Route::get('/tramites', [SolicitudController::class, 'crear'])->name('solicitudes.crear');
Route::post('/tramites', [SolicitudController::class, 'store'])->middleware('throttle:5,1')->name('solicitudes.store');
Route::get('/tramites/consultar', [SolicitudController::class, 'consultar'])->name('solicitudes.consultar');
Route::post('/tramites/consultar', [SolicitudController::class, 'buscar'])->middleware('throttle:10,1')->name('solicitudes.buscar');
Route::middleware('signed')->group(function () {
    Route::get('/tramites/documentos/{documento}', [SolicitudController::class, 'descargarDocumento'])->name('solicitudes.documentos.descargar');
    Route::post('/tramites/{solicitud}/documentos', [SolicitudController::class, 'adjuntarDocumentos'])->middleware('throttle:10,1')->name('solicitudes.documentos.store');
});

Route::get('/pqrs', [PqrsController::class, 'crear'])->name('pqrs.crear');
Route::post('/pqrs', [PqrsController::class, 'store'])->middleware('throttle:5,1')->name('pqrs.store');
Route::get('/pqrs/consultar', [PqrsController::class, 'consultar'])->name('pqrs.consultar');
Route::post('/pqrs/consultar', [PqrsController::class, 'buscar'])->middleware('throttle:10,1')->name('pqrs.buscar');

Route::get('/admin', [AdminAuthController::class, 'showLogin'])->name('admin');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documents');
    Route::post('/documentos', [DocumentoController::class, 'store'])->name('documents.store');
    Route::post('/documentos/{document}/delete', [DocumentoController::class, 'destroy'])->name('documents.delete');

    Route::get('/eventos', [AdminEventoController::class, 'index'])->name('events');
    Route::post('/eventos', [AdminEventoController::class, 'store'])->name('events.store');
    Route::post('/eventos/{event}/delete', [AdminEventoController::class, 'destroy'])->name('events.delete');

    Route::get('/solicitudes', [AdminSolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/{solicitud}', [AdminSolicitudController::class, 'show'])->name('solicitudes.show');
    Route::get('/solicitudes/{solicitud}/documentos/{documento}', [AdminSolicitudController::class, 'descargarDocumento'])->name('solicitudes.documentos.descargar');
    Route::post('/solicitudes/{solicitud}/verificar', [AdminSolicitudController::class, 'verificar'])->name('solicitudes.verificar');
    Route::post('/solicitudes/{solicitud}/aprobar', [AdminSolicitudController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::post('/solicitudes/{solicitud}/rechazar', [AdminSolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');
    Route::post('/solicitudes/{solicitud}/reasignar', [AdminSolicitudController::class, 'reasignar'])->name('solicitudes.reasignar');
    Route::post('/solicitudes/{solicitud}/finalizar', [AdminSolicitudController::class, 'finalizar'])->name('solicitudes.finalizar');
    Route::post('/solicitudes/{solicitud}/documentos', [AdminSolicitudController::class, 'subirDocumento'])->name('solicitudes.documentos.store');

    Route::get('/pqrs', [AdminPqrsController::class, 'index'])->name('pqrs.index');
    Route::get('/pqrs/{pqrs}', [AdminPqrsController::class, 'show'])->name('pqrs.show');
    Route::post('/pqrs/{pqrs}/tramitar', [AdminPqrsController::class, 'tramitar'])->name('pqrs.tramitar');
    Route::post('/pqrs/{pqrs}/responder', [AdminPqrsController::class, 'responder'])->name('pqrs.responder');

    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria');

    Route::middleware('super_admin')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/nuevo', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::post('/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggleActive'])->name('usuarios.toggle');

        Route::get('/tipos-tramite', [TramiteTipoController::class, 'index'])->name('tramite-tipos.index');
        Route::post('/tipos-tramite', [TramiteTipoController::class, 'store'])->name('tramite-tipos.store');
        Route::post('/tipos-tramite/{tramiteTipo}/toggle', [TramiteTipoController::class, 'toggleActive'])->name('tramite-tipos.toggle');
    });
});

Route::get('/transparencia', [TransparenciaController::class, 'index'])->name('transparencia');
Route::get('/transparencia/categoria/{slug}', [TransparenciaController::class, 'categoria'])->name('transparencia.category');

Route::view('/afiliaciones', 'pages.afiliaciones.index')->name('afiliaciones');

Route::redirect('/afiliaciones/quien-puede-ser-socio', '/afiliaciones#quien-puede-ser-socio');
Route::redirect('/afiliaciones/como-asociarse', '/afiliaciones#como-asociarse');
Route::redirect('/afiliaciones/beneficios', '/afiliaciones#beneficios');

Route::view('/institucional', 'pages.institucional.index')->name('institucional');

Route::redirect('/inicio/mision-y-vision', '/institucional#mision-vision');
Route::redirect('/inicio/junta-directiva', '/institucional#organos-direccion');
Route::redirect('/inicio/organigrama', '/institucional#organigrama');

Route::view('/creditos/expres', 'creditos.expres')->name('creditos.expres');

Route::view('/creditos/lineas', 'creditos.lineas')->name('creditos.lineas');
