<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        // Intercept the Response before sending it
        $response = $event->getResponse();

        // Chech if it's a jsonResponse
        if ($response->headers->get('Content-Type') === 'application/json') {
            // Allow access to the API from everywhere
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('access-control-allow-methods', 'POST, GET, OPTIONS, DELETE, PUT, PATCH');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
        }
    }
}