<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    // home page test
    public function testHomePage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    
    public function testProductsPageCorrectUrl() 
    {
        $response = $this->post('/products', ['feedUrl' => 'http://pf.tradetracker.net/?aid=1&type=xml&encoding=utf-8&fid=251713&categoryType=2&additionalType=2&limit=1000pp', 'feedUrl', 'pageNumber' => '1', 'productsPerPage' => '100']);
        $response->assertStatus(200);
	}

	public function testProductsPageWrongUrl() 
    {
        $response = $this->post('/products', ['feedUrl' => 'zzzz.com', 'feedUrl']);
        $response->assertStatus(200);
	}
}
