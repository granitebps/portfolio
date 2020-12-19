<?php

namespace Tests\Feature;

use App\Portfolio;
use App\PortfolioPic;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class PortfolioTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_portfolios()
    {
        $response = $this->json('GET', '/api/v1/portfolio');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_portfolio()
    {
        Storage::fake('portfolio');

        $response = $this->json('POST', '/api/v1/portfolio', $this->portfolioData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/portfolio', $this->portfolioData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Portfolio Created'
        ]);
    }

    /** @test */
    public function test_update_portfolio()
    {
        $response = $this->json('PUT', '/api/v1/portfolio/99', $this->portfolioData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/portfolio/99', $this->portfolioData());
        $response->assertStatus(404);

        $portfolio = $this->createPortfolio();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/portfolio/' . $portfolio->id, $this->portfolioData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Portfolio Updated'
        ]);
    }

    /** @test */
    public function test_delete_portfolio()
    {
        $response = $this->json('DELETE', '/api/v1/portfolio/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/portfolio/99');
        $response->assertStatus(404);

        $portfolio = $this->createPortfolio();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/portfolio/' . $portfolio->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Portfolio Deleted'
        ]);
    }

    /** @test */
    public function test_delete_portfolio_pic()
    {
        $response = $this->json('GET', '/api/v1/portfolio-photo/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/portfolio-photo/99');
        $response->assertStatus(404);

        $portfolio = $this->createPortfolio();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/portfolio-photo/' . $portfolio->pic[0]->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Portfolio Picture Deleted'
        ]);
    }

    public function portfolioData()
    {
        $thumb = UploadedFile::fake()->image('thumb.jpg')->size(512);
        $pic = UploadedFile::fake()->image('pic.jpg')->size(512);

        return [
            'name' => 'Test Portfolio Name',
            'desc' => 'Test Portfolio Description',
            'type' => 1,
            'thumbnail' => $thumb,
            'pic' => [
                $pic
            ],
            'url' => 'https://granitebps.com',
        ];
    }

    public function createPortfolio()
    {
        Storage::fake('portfolio');

        $thumb = UploadedFile::fake()->image('thumb.jpg')->size(512);
        $pic = UploadedFile::fake()->image('pic.jpg')->size(512);

        $portfolio = Portfolio::create([
            'name' => 'Test Portfolio Name',
            'desc' => 'Test Portfolio Description',
            'type' => 1,
            'thumbnail' => $thumb,
            'url' => 'https://granitebps.com',
        ]);
        PortfolioPic::create([
            'portfolio_id' => $portfolio->id,
            'pic' => $pic
        ]);

        return $portfolio;
    }
}
