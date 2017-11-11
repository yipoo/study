<?php
/**
 * Created by PhpStorm.
 * User: dingzong
 * Date: 2017/11/11
 * Time: 20:10
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils $helper
     * @Route("/login", name="security_login")
     */
    public function loginAction(AuthenticationUtils $helper)
    {
        return $this->render('security/login.html.twig',[
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout",name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('logout');
    }
}