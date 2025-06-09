<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Infrastructure\Image\Service\WatermarkService;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use Throwable;

/**
 * ApiController
 *
 * @package App\Http\Controllers
 */
class WatermarkController
{
    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param WatermarkService $watermarkService
     */
    public function __construct(
        private LoggerInterface $logger,
        private WatermarkService $watermarkService
    ) {
    }

    /**
     * __invoke
     *
     * @param ServerRequestInterface $request
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $inputPath = $data['image'] ?? '';
        $outputPath = sys_get_temp_dir() . '/watermarked.jpg';

        if (!$inputPath) {
            return new Response(400, [], "Missing input image.");
        }

        try {
            $this->watermarkService->apply($inputPath, $outputPath);
        } catch (Throwable $e) {
            $this->logger->error(self::class . " Msg: " . $e->getMessage());
            return new Response(500, [], 'Error: ' . $e->getMessage());
        }

        $stream = Stream::create(fopen($outputPath, 'rb'));

        return new Response(200, [
            'Content-Type' => 'image/jpeg'
        ], $stream);
    }
}
