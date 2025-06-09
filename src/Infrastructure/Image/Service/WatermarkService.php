<?php

declare(strict_types=1);

namespace App\Infrastructure\Image\Service;

use InvalidArgumentException;

/**
 * WatermarkService
 *
 * @package App\Infrastructure\Image\Service
 */
class WatermarkService
{
    /**
     * Apply a watermark to an image
     *
     * @param string $sourceImageUrl Path to the input image (jpg/png)
     * @param string $outputImage    Path to save the watermarked image
     * @param int    $padding        Padding from the bottom-right corner in pixels
     *
     * @throws InvalidArgumentException
     */
    public function apply(string $sourceImageUrl, string $outputImage, int $padding = 10): void
    {
        $sourceImage = $this->prepareImage($sourceImageUrl);

        $main = match (strtolower(pathinfo($sourceImageUrl, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => imagecreatefromjpeg($sourceImage),
            'png' => imagecreatefrompng($sourceImage),
            default => throw new InvalidArgumentException("Unsupported image format: $sourceImage")
        };

        $watermark = imagecreatefrompng(WatermarkService::getLogoPath());

        $mainWidth = imagesx($main);
        $mainHeight = imagesy($main);
        $wmkWidth = imagesx($watermark);
        $wmkHeight = imagesy($watermark);

        $x = $mainWidth - $wmkWidth - $padding;
        $y = $mainHeight - $wmkHeight - $padding;

        imagecopy($main, $watermark, $x, $y, 0, 0, $wmkWidth, $wmkHeight);
        imagejpeg($main, $outputImage, 90);

        imagedestroy($main);
        imagedestroy($watermark);

        $this->cleanTemp($sourceImage);
    }

    /**
     * GetLogoPath
     * get watermark logo path
     */
    private static function getLogoPath(): string
    {
        $filePath = !empty($_ENV["IMG_LOGO"]) ? $_ENV["IMG_LOGO"] : "Image/logo.png";

        return  __DIR__ . "/../../../Storage/" . $filePath;
    }

    /**
     * PrepareImage
     * Download image from a remote location
     *
     * @param  string $path
     * @throws InvalidArgumentException
     */
    private function prepareImage(string $path): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $temp = tempnam(sys_get_temp_dir(), 'img_');
            $imageData = file_get_contents($path);
            if ($imageData === false) {
                $error = error_get_last();
                throw new InvalidArgumentException(
                    "Failed to download image from URL: $path. Error: " . ($error['message'] ?? 'unknown error')
                );
            }
            file_put_contents($temp, $imageData);
            return $temp;
        }
        if (!file_exists($path)) {
            throw new InvalidArgumentException("Image does not exist: $path");
        }

        return $path;
    }

    /**
     * cleanTemp
     * Delete temporary files
     *
     * @param string $file
     */
    private function cleanTemp(string $file): void
    {
        if (
            str_starts_with($file, sys_get_temp_dir())
            && file_exists($file) && !unlink($file)
        ) {
                error_log('');
        }
    }
}
