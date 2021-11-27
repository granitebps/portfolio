<?php

use App\Models\Service;

dataset('service', [
    fn () => Service::factory()->create()
]);
