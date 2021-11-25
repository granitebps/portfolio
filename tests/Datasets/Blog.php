<?php

use App\Models\Blog;

dataset('blog', [
    fn () => Blog::factory()->create()
]);
