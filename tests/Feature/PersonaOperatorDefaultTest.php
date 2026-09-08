<?php

use App\Http\Controllers\Contacto\PersonaController;

test('blank phone operator falls back to default operadora', function () {
    expect(PersonaController::normalizeIdOperadora(null))->toBe(1)
        ->and(PersonaController::normalizeIdOperadora(''))->toBe(1)
        ->and(PersonaController::normalizeIdOperadora('3'))->toBe(3);
});
