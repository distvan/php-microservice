<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class WatermarkControllerTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
            'http_errors' => false
        ]);
    }

    public function testWatermarkPost()
    {
        $imageUrl = "http://localhost:8081/test-image.jpg";
        $response = $this->client->post('/watermark', [
            'form_params' => [
                'image' => $imageUrl
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $imageBinary = $response->getBody()->getContents();
        $image = imagecreatefromstring($imageBinary);
        $this->assertNotFalse($image, 'The returned content is not a valid image.');
        imagedestroy($image);
    }
}
