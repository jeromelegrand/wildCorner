<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function admin()
    {
        return new Response('<html><body><h1>Page Admin !</h1></body></html>');
    }
}