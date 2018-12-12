<?php

namespace Tests\Unit;

use Faker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testIndex()
    {
        $response = $this->get(route('index'));
        $this->assertFalse((bool)$response->exception, ($response->exception)?$response->exception:false);
        $response->assertStatus(200);
    }
    
    public function testCreate()
    {
        $faker = Faker\Factory::create();
        $url = $faker->imageUrl();
        $response = $this->post(route('store'), [
            'url'   =>  $url
        ]);
        $this->assertFalse((bool)$response->exception, ($response->exception)?$response->exception:false);
        $response->assertStatus(302);
    }
    
    public function testDownload()
    {
        $download = factory(\App\Downloads::class)->state('success')->create();
        $response = $this->get(route('download', $download->id));
        $this->assertTrue($response->baseResponse instanceof BinaryFileResponse);
        $response->assertStatus(200);
    }
}
