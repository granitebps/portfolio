<?php

use App\Models\Technology;

dataset('technology', [
    fn () => Technology::factory()->create()
]);
