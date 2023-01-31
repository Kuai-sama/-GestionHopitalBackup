<?php

namespace App\Form;

use App\Entity\RDV;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextAreaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PrendreRDV extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Récupère la date d’aujourd’hui
        $date = new \DateTime('now');
        // On enlève 15 minutes pour éviter que l’utilisateur puisse prendre un rendez-vous dans le passé
        $date->sub(new \DateInterval('PT15M'));
        // On enlève les secondes
        $date->setTime($date->format('H'), $date->format('i'), 0);

        $minutes = [];
        $hours = [];
        for ($i = 0; $i < 60; $i += 15) {
            $minutes[] = $i;
        }

        for ($i = 6; $i < 24; $i++) {
            $hours[] = $i;
        }

        $builder->add('date_heure', DateTimeType::class, [
            'widget' => 'single_text',
            'with_seconds' => false,
            'data' => $date,
            'attr' => ['min' => $date->format('Y-m-d H:i')],
        ])
            ->add('description', TextAreaType::class)
            ->add('save', SubmitType::class, ['label' => 'Prendre rendez-vous']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // On rassemble la date et l'heure
        $resolver->setDefaults([
            'data_class' => RDV::class,
        ]);
    }
}

/*


 $builder->add('date', DateType::class, [
            'widget' => 'single_text',
            'data' => $date,
            'attr' => ['min' => $date->format('Y-m-d')],
        ])
            // Les minutes sont toutes les 15 minutes
            ->add('heure', TimeType::class, [
                'widget' => 'choice',
                'minutes' => $minutes,
                'hours' => $hours,
                'input' => 'datetime',
                'with_seconds' => false,
            ])
/*/