<?php

namespace App\Form;

use App\Entity\Rent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Place;
use App\Entity\Client;

class RentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'secondName',
                'label' => 'Заказчик'
            ])
            ->add('places', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'number',
                'multiple' => true,
                'label' => 'Место/Места'
            ])
            ->add('beginDate', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Дата заселения'
            ])
            ->add('endDate', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Дата выселения'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Добавить'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rent::class,
        ]);
    }
}
