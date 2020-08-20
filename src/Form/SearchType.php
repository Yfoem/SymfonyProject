<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Sorties;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('lieux_id', EntityType::class,[
                'class'=> Lieux::class,
                'required'=> false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom_lieu', 'ASC');
                },
                'choice_label' =>'nom_lieu',
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
