<?php

use App\Models\Gallery;

dataset('gallery', [
    fn () => Gallery::factory()->create()
]);
