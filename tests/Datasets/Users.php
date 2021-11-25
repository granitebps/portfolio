<?php

use App\Models\Profile;

dataset('profile', [
    fn () => Profile::factory()->create()
]);
