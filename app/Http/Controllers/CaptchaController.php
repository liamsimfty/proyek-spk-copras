<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    public function generate()
    {
        try {
            // Generate random string for CAPTCHA (using only numbers and uppercase letters)
            $randomString = strtoupper(Str::random(4));
            
            // Store in session
            session(['captcha' => $randomString]);
            
            // Create image with larger size
            $image = imagecreatetruecolor(140, 50);
            
            // Set background color (light gray)
            $bg = imagecolorallocate($image, 240, 240, 240);
            imagefill($image, 0, 0, $bg);
            
            // Add minimal noise (dots)
            for($i = 0; $i < 200; $i++) {
                $color = imagecolorallocate($image, rand(200, 220), rand(200, 220), rand(200, 220));
                imagesetpixel($image, rand(0, 140), rand(0, 50), $color);
            }
            
            // Add text using built-in font
            $textColor = imagecolorallocate($image, 50, 50, 50);
            
            // Use built-in font with larger size and better spacing
            for($i = 0; $i < strlen($randomString); $i++) {
                $x = 15 + ($i * 25);
                $y = 15;
                imagestring($image, 5, $x, $y, $randomString[$i], $textColor);
            }
            
            // Add a single line for minimal security
            $lineColor = imagecolorallocate($image, 180, 180, 180);
            imageline($image, 0, rand(20, 30), 140, rand(20, 30), $lineColor);
            
            // Output image
            header('Content-Type: image/png');
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
            imagepng($image);
            imagedestroy($image);
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('CAPTCHA generation failed: ' . $e->getMessage());
            
            // Return a simple error image
            $errorImage = imagecreatetruecolor(140, 50);
            $bg = imagecolorallocate($errorImage, 240, 240, 240);
            $textColor = imagecolorallocate($errorImage, 255, 0, 0);
            imagefill($errorImage, 0, 0, $bg);
            imagestring($errorImage, 3, 10, 20, 'Error', $textColor);
            header('Content-Type: image/png');
            imagepng($errorImage);
            imagedestroy($errorImage);
        }
    }
} 