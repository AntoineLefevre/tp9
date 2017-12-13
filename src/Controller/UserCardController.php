<?php

namespace App\Controller;


use App\AppAccess;
use App\Entity\Card;
use App\Entity\UserCard;
use App\AppEvent;
use App\Event\UserCardEvent;
use App\Form\UserCardType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(path="/usercard")
 */
class UserCardController extends Controller
{
    /**
     * @Route(path="/{id}/new", name="app_usercard_new")
     *
     */
    public function newAction(Request $request, Card $card)
    {
        $userCard = $this->get(UserCard::class);

        $form = $this->createForm(UserCardType::class, $userCard, ["card" => $card]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(UserCardEvent::class);
            $event->setUserCard($userCard);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::USER_CARD_ADD, $event);

            return $this->redirectToRoute("app_user_index");
        }

        return $this->render("UserCard/new.html.twig", ["form" => $form->createView()]);

    }

    /**
     * @Route(path="/{id}/edit", name="app_usercard_edit")
     *
     */
    public function editAction(Request $request, UserCard $userCard, AuthorizationCheckerInterface $authorizationChecker)
    {

        if(false === $authorizationChecker->isGranted(AppAccess::UserCardEdit, $userCard)){
            $this->addFlash('error', 'access deny !');
            return $this->redirectToRoute("app_user_index");
        }

        $form = $this->createForm(UserCardType::class, $userCard);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(UserCardEvent::class);
            $event->setUserCard($userCard);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::USER_CARD_EDIT, $event);

            return $this->redirectToRoute("app_user_index");
        }

        return $this->render("UserCard/edit.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/delete", name="app_usercard_delete")
     *
     */
    public function deleteAction(UserCard $userCard, AuthorizationCheckerInterface $authorizationChecker)
    {
        if(false === $authorizationChecker->isGranted(AppAccess::UserCardEdit, $userCard)){
            $this->addFlash('error', 'access deny !');
            return $this->redirectToRoute("app_user_index");
        }

        $event = $this->get(UserCardEvent::class);
        $event->setUserCard($userCard);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::USER_CARD_DELETE, $event);

        return $this->redirectToRoute("app_user_index");

    }

}

