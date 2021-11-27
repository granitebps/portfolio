<?php

use App\Models\Message;

dataset('message', [
    fn () => Message::factory()->create()
]);
