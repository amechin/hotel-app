<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, ['label' => 'Titre ', 'attr' => ['placeholder' => 'Titre de la chambre']])
            ->add('description',TextType::class, ['label' => 'Description', 'attr' => ['placeholder' => 'Description de la chambre']])
            ->add('price',NumberType::class, ['label' => 'Prix ', 'attr' => ['placeholder' => 'Prix de la chambre']])
            ->add('pict', FileType::class, ['label' => 'Image ', 'mapped' => false, 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
