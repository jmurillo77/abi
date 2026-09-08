<?php

use App\Models\matriz\Parroquia;
use App\Models\matriz\Provincia;

test('las relaciones de jerarquía usan los nombres esperados por la vista', function () {
    expect(method_exists(Provincia::class, 'cantones'))->toBeTrue()
        ->and(method_exists(Parroquia::class, 'canton'))->toBeTrue();
});
