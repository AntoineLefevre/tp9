<?php
namespace App\Controller;
use App\AppAccess;
use App\AppEvent;
use App\Entity\User;
use App\Event\UserEvent;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\UserCard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * @Route(path="/user")
 */
class UserController extends Controller
{
    /**
     * @Route(
     *     path="/",
     *     name="app_user_index"
     * )
     */
    public function indexdAction(AuthorizationCheckerInterface $authorizationChecker)
    {
        if($authorizationChecker->isGranted('ROLE_ADMIN')){
            $userCards = $this->getDoctrine()->getManager()->getRepository(UserCard::class)->findAll();
            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
            return $this->render('User/index.html.twig', ["userCards" => $userCards,'users' => $users]);

        }else{
            $userCards = $this->getDoctrine()->getManager()->getRepository(UserCard::class)->findBy(["user" => $this->getUser()]);
            return $this->render('User/index.html.twig', ["userCards" => $userCards]);
        }

    }

    /**
     * @Route(path="/new", name="app_user_new")
     *
     */
    public function newAction(Request $request)
    {
        $user = $this->get(User::class);

        $form = $this->createForm(UserType::class, $user, ['validation_groups' => 'new']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(UserEvent::class);
            $event->setUser($user);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::USER_ADD, $event);

            return $this->redirectToRoute("app_user_index");
        }

        return $this->render("User/new.html.twig", ["form" => $form->createView()]);

    }

    /**
     * @Route(path="/{id}/edit", name="app_user_edit")
     *
     */
    public function editAction(Request $request, User $user, AuthorizationCheckerInterface $authorizationChecker)
    {

        if(false === $authorizationChecker->isGranted(AppAccess::UserEdit, $user)){
            $this->addFlash('error', 'access deny !');
            return $this->redirectToRoute("app_user_index");
        }

        $form = $this->createForm(UserType::class, $user, ['validation_groups' => 'new']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(UserEvent::class);
            $event->setUser($user);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::USER_EDIT, $event);

            return $this->redirectToRoute("app_user_index");
        }

        return $this->render("User/edit.html.twig", ["form" => $form->createView()]);

    }
}