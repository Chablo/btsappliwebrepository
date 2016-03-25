<?php
/**
 * * @copyright Copyright (c) 2009-2014 Steven TITREN - www.webaki.com
* @packageWebaki\UserBundle\Redirection
* @author Steven Titren <contact@webaki.com>
*/

namespace btsappli\UserBundle\Redirection;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AfterLoginRedirection implements AuthenticationSuccessHandlerInterface
{
    /**
    * @var \Symfony\Component\Routing\RouterInterface
    */
    private $router;
    
    /**
    * @param RouterInterface $router
    */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    /**
    * @param Request $request
    * @param TokenInterface $token
    * @return RedirectResponse
    */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // On récupère la liste des rôles d'un utilisateur
        $roles = $token->getRoles();
        // On transforme le tableau d'instance en tableau simple
        $rolesTab = array_map(function($role){ 
        return $role->getRole(); 
        }, $roles);
        
        // S'il s'agit d'un admin on le redirige vers l'accueil admin
        if (in_array('ROLE_SUPER_ADMIN', $rolesTab, true)) {
            $redirection = new RedirectResponse($this->router->generate('btsappli_utilisateurs_accueilAdmin'));
        }
        // sinon il s'agit d'un simple utilisateur et il est redirigé vers l'accueil étudiant
        else {
            $redirection = new RedirectResponse($this->router->generate('btsappli_utilisateurs_accueilEtu'));
        }
        return $redirection;
    }
}