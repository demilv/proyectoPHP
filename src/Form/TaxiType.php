<?php

namespace App\Form;

use App\Entity\Taxi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class TaxiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marca', null, [
            'label' => 'Nombre de la marca',])
            ->add('velocidad', null, [
                'label' => 'Velocidad del coche',
                'required' => false,
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 999,
                        'message' => 'La velocidad no puede ser mayor de 999'
                    ])
                ]
            ])
            ->add('activo')
            ->add('nombre', null, [
                'required' => false, 
                'label' => 'Nombre del coche',
                'label_attr' => ['class' => 'formvioleta'],                             
                'constraints' => 
                new NotBlank(['message' => 'El nombre es obligatorio'])]
                )
            ->add('propietario', null, [
                'label' => 'Nombre del propietario',
            ])
            ->add('modelo', FileType::class, [
                'mapped' => false,
                'label' => 'Sube la foto del taxi',
                'required' => false,
                'constraints' => [
                    new File([                        
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG, PNG)',
                    ]),
                ],
            ])
            ->add('localizacion', null, [
                'label' => 'Lugar en el que se encuentra operando',
                'attr' => ['placeholder'=>'Esta será la unica zona donde se encontrará trabajando']
            ])
            ->add('Enviar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Taxi::class,
        ]);
    }
}
