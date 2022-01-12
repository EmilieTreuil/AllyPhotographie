<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/error", name="error")
     */
    public function show(FlattenException $exception): Response
    {
        $code = $exception->getStatusCode();
        
        if($code == "404"){

            return $this->render('error/index.html.twig');

        } else { 
            return new Response($exception->getMessage());
        }

    }
}

