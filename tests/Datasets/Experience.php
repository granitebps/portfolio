<?php

use App\Models\Experience;

dataset('experience', [
    fn () => Experience::factory()->create()
]);
