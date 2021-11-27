<?php

use App\Models\Skill;

dataset('skill', [
    fn () => Skill::factory()->create()
]);
