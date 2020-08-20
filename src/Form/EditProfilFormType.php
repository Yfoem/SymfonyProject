<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participants;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo' )
            ->add('email', EmailType::class)
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('campus', EntityType::class,[
                'class'=> Campus::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom_campus', 'ASC');
                },
                'choice_label' =>'campus.nom_campus',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}
