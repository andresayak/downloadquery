<?php

namespace Tests\Unit;

use Faker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConsoleTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testAdd()
    {
        $faker = Faker\Factory::create();
        $url = $faker->imageUrl();
        
        $output = $this->artisan('downloads:add', [
            'url'   =>  $url
        ])
            ->expectsOutput('Done');
    }
    
    public function testJob()
    {
        $download = factory(\App\Downloads::class)->state('without_file')->create();
        $job = new \App\Jobs\DownloadFile($download);
        $job->handle();
        $download->refresh();
        
        $this->assertEquals($download->status, \App\Downloads::STATUS_SUCCESS);
    }
}
