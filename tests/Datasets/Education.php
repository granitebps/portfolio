<?php

use App\Models\Education;

dataset('education', [
    fn () => Education::factory()->create()
]);
