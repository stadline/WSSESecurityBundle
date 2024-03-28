<?php 

namespace Stadline\WSSESecurityBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Stadline\WSSESecurityBundle\Security\Authentication\Token\WsseUserToken;

class WsseListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct(
        SecurityContextInterface $securityContext,
        AuthenticationManagerInterface $authenticationManager
    ) {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        //WSSE Part
        $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        if (!$request->headers->has('x-wsse') ||
            1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)
        ) {
            if (! $request->headers->has('Authorization')) {
                throw new AccessDeniedHttpException();
            }

            return;
        }

        $token = new WsseUserToken();
        $token->setUser($matches[1]);
        
        $token->digest   = $matches[2];
        $token->nonce    = $matches[3];
        $token->created  = $matches[4];
        
        try {
            $authToken = $this->authenticationManager->authenticate($token);
        
            $this->securityContext->setToken($authToken);
        } catch (AuthenticationException $failed) {
      
            // Deny authentication with a '403 Forbidden' HTTP response
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);
        
        }
    }
}
