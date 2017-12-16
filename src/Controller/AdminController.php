<?php

namespace App\Controller;


use App\Entity\LogAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route(path="/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route(
     *     path="",
     *     name="admin_index"
     * )
     */
    public function indexdAction()
    {
        $logs = $this->getDoctrine()->getManager()->getRepository(LogAction::class)->findAll();
        return $this->render('Admin/index.html.twig',['logs' => $logs]);
    }
}
