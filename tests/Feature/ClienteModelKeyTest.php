<?php

use App\Models\Cliente;

test('cliente model uses the real primary key of the clientes table', function () {
    expect((new Cliente())->getKeyName())->toBe('id');
});
