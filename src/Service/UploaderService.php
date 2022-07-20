<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderService
{

    private $uploadsPath;
    
    const POST_IMAGES = 'post_images';
    const PRODUCT_IMAGES = 'product_images';
    const PRODUCT_GALERIE = 'product_galerie';


    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }


    public function uploadPostImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath. '/' .self::POST_IMAGES;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move(
            $destination,
            $newFilename
        );

        return $newFilename;
    }

    public function uploadProductImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath. '/' .self::PRODUCT_IMAGES;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move(
            $destination,
            $newFilename
        );
        
        return $newFilename;
    }

    public function uploadGalerieImages(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath. '/' .self::PRODUCT_GALERIE;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move(
            $destination,
            $newFilename
        );
        
        return $newFilename;
    }

}