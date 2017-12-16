<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 16/12/2017
 * Time: 20:52
 */

namespace App\Controller;



use App\AppAccess;
use App\Entity\Card;
use App\Entity\User;
use App\Entity\UserCard;
use App\AppEvent;
use App\Event\UserCardEvent;
use App\Form\UserCardType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
/**
 * @Route(path="/usercard")
 */
class UserCardController extends Controller
{
    /**
     * @Route(
     *     path="/{id}/new",
     *     name="usercard_new"
     * )
     */
    public function newAction(Request $request, Card $card, AuthorizationChecker $authorizationChecker)
    {

        $usercard = $this->get(\App\Entity\UserCard::class);

        $form = $this->createForm(UserCardType::class, $usercard, ['card' => $card]);

        $form->handleRequest($request);

        if( $form->isSubmitted() &&  $form->isValid() ) {

            $usercardEvent = $this->get(\App\Event\UserCardEvent::class);
            $usercardEvent->setUsercard($usercard);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppEvent::USER_CARD_ADD, $usercardEvent);
        }
        return $this->render('UserCard/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route(
     *     path="/{id}/edit",
     *     name="usercard_edit"
     * )
     */
    public function editAction(Request $request, UserCard $usercard, AuthorizationChecker $authorizationChecker)
    {

        if(false === $authorizationChecker->isGranted(AppAccess::UserCardEdit, $usercard)){
            return $this->redirectToRoute("user_index");
        }

        $form = $this->createForm(UserCardType::class, $usercard);

        $form->handleRequest($request);

        if( $form->isSubmitted() &&  $form->isValid() ) {

            $usercardEvent = $this->get(\App\Event\UserCardEvent::class);
            $usercardEvent->setUsercard($usercard);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppEvent::USER_CARD_EDIT, $usercardEvent);
        }
        return $this->render('UserCard/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route(
     *     path="/{id}/remove",
     *     name="usercard_remove"
     * )
     */
    public function removeAction(UserCard $usercard, AuthorizationChecker $authorizationChecker)
    {

        if(false === $authorizationChecker->isGranted(AppAccess::UserCardRemove, $usercard)){
            return $this->redirectToRoute("user_index");
        }
            $usercardEvent = $this->get(\App\Event\UserCardEvent::class);
            $usercardEvent->setUsercard($usercard);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppEvent::USER_CARD_REMOVE, $usercardEvent);

        return $this->redirectToRoute('user_index');
    }

}