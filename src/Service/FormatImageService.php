<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



class FormatImageService 
{

    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    public function imgSize($imageName)
    {
        $filename = $this->parameters->get('picture_dir') . '/' . uniqid() . '-' . $imageName;
        $copyFilename = $this->parameters->get('picture_dir') . '/copy-' . uniqid() . $imageName;

        list($width, $height) = getimagesize($filename);

        $newWidth = $width;
        $newHeight = $height;
        
        if($width < 150) {
            $coeff = 150/$width;
            $newWidth *= $coeff;
            $newHeight *= $coeff;
        } if ($newHeight < 150)
        {
            $coeff = 150/$newHeight;
            $newWidth *= $coeff;
            $newHeight *= $coeff;
        }


        $newImg = imagecreatetruecolor($newWidth, $newHeight);
        $source = imagecreatefromjpeg($filename);

        imagecopyresized($newImg, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);


        $imgSize = min(imagesx($newImg), imagesy($newImg));
        $croppedImg = imagecrop($newImg, ['x' => 0, 'y' => 0, 'width' => $imgSize, 'height' => $imgSize ]);

        if($croppedImg !== FALSE)
        {
            imagejpeg($croppedImg, $copyFilename);
            imagedestroy($croppedImg);
        }

        imagedestroy($newImg);
        imagedestroy($source);

        return $copyFilename;


    }
}