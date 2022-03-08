<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Track;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('duration')
            ->add('image', FileType::class, array('data_class' => null))
            ->add('file', FileType::class, array('data_class' => null))
            ->add('tags', EntityType::class, array(
                'class' => Tag::class,
                'multiple' => true,
                'expanded' => true
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
