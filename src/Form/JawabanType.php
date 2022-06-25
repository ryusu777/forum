<?php

namespace App\Form;

use App\Entity\Jawaban;
use App\Entity\Pertanyaan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JawabanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userName')
            ->add('jawab')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('vote')
            // ->add('approveStatus')
            // ->add($pertanyaan->getIdPertanyaan())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jawaban::class,
        ]);
    }
}
