<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;


class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * Envoi un message si un utilisateur tente d'accéder à /admin
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $content = "Vous n'avez pas la permission ";

        return new Response($content, 403);
    }
}