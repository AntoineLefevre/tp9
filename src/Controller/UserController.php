<?php

namespace App\Controller;



use App\Entity\User;
use App\Entity\UserCard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * @Route(path="/user")
 */
class UserController extends Controller
{
    /**
     * @Route(
     *     path="/",
     *     name="user_index"
     * )
     */
    public function indexdAction(AuthorizationChecker $authorizationChecker)
    {
        if($authorizationChecker->isGranted('ROLE_ADMIN')){
            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
            $usercards = $this->getDoctrine()->getManager()->getRepository(UserCard::class)->findAll();
            return $this->render('User/index.html.twig',  ['usercards' => $usercards,'users' => $users ]);
        }
        else
        {
            $usercards = $this->getDoctrine()->getManager()->getRepository(UserCard::class)->findBy(["user" => $this->getUser()]);
            return $this->render('User/index.html.twig',  ['usercards' => $usercards]);
        }

    }
}
