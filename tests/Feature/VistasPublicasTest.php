<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('las páginas públicas de radicar y consultar trámites y PQRS cargan correctamente', function () {
    $this->get('/tramites')->assertOk();
    $this->get('/tramites/consultar')->assertOk();
    $this->get('/pqrs')->assertOk();
    $this->get('/pqrs/consultar')->assertOk();
});
