<?php

namespace App\Form;


use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\Sorties;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('datedebut', DateTimeType::class)
            ->add('duree', IntegerType::class)
            ->add('datecloture', DateTimeType::class)
            ->add('nbinscriptionsmax', IntegerType::class)
            ->add('descriptioninfos')

            ->add('lieux_id', EntityType::class,[
                'class'=> Lieux::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom_lieu', 'ASC');
                },
                'choice_label' =>'nom_lieu',
            ])

            ->add('urlPhoto')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
