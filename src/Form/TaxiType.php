<?php

namespace App\Form;

use App\Entity\Taxi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class TaxiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marca')
            ->add('velocidad')
            ->add('activo')
            ->add('nombre', null, [
                'required' => false,
            ])
            ->add('propietario')
            ->add('modelo', FileType::class, [
                'mapped' => false,
                'label' => 'Sube la foto del taxi',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG, PNG)',
                    ]),
                ],
            ])
            ->add('localizacion')
            ->add('Enviar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Taxi::class,
        ]);
    }
}
