<?php
// src/EventListener/NotFoundExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotFoundExceptionListener
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception) {
            $url = $this->urlGenerator->generate('home'); // Nombre de la ruta a la que deseas redirigir
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}
