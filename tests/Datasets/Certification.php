<?php

use App\Models\Certification;

dataset('certification', [
    fn () => Certification::factory()->create()
]);
