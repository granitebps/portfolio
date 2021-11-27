<?php

use App\Models\Portfolio;
use App\Models\PortfolioPic;

dataset('portfolio', [
    function () {
        $portfolio = Portfolio::factory()
            ->has(PortfolioPic::factory()->count(2), 'pic')
            ->create();
        return $portfolio;
    }
]);
