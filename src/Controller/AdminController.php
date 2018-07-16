<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     * @Security("has_role('ROLE_ADMIN')", message="Accès refusé !!!")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function admin()
    {
        return new Response('<html><body><h1>Page Admin !</h1></body></html>');
    }
}