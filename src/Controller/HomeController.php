<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route(
     *     path="/",
     *     name="app_home_index"
     * )
     */
    public function indexAction()
    {
        return $this->render('Home/index.html.twig');
    }
}
