<?php


namespace App\Form;

use App\Entity\UserCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserCardType extends AbstractType
{
    private $token;

    private $card;
    /**
     * UserCardType constructor.
     * @param $token
     */
    public function __construct(TokenStorage $token)
    {
        $this->token = $token;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => UserCard::class,'card' => null));
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->card = $options["card"];
        $builder
            ->add('actionPoint', IntegerType::class)
            ->add('attack', IntegerType::class)
            ->add('defense', IntegerType::class)
            ->addEventListener( FormEvents::PRE_SET_DATA,
                array($this, 'onPreSetData'))

            ->getForm();
    }
    public function onPreSetData(FormEvent $event)
    {
        $usercard = $event->getData();
        $form = $event->getForm();
        if ($usercard->getId() === null){
            $usercard->setUser($this->token->getToken()->getUser());
            $usercard->setCard($this->card);
            $form->add('save',SubmitType::class, array('label'=>"créer"));
        }
        else
        {
            $form->add('save',SubmitType::class, array('label'=>"modifier"));
        }

    }
}