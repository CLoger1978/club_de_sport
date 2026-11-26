<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\CompetitionParticipation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class CompetitionParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sportif', EntityType::class, [
                'class' => User::class,
                'choice_label' => static fn (User $user): string => $user->getPrenom().' '.$user->getNom(),
                'label' => 'Sportif',
            ])
            ->add('competition', EntityType::class, [
                'class' => Competition::class,
                'choice_label' => 'nom',
                'label' => 'Competition',
            ])
            ->add('progression', IntegerType::class, [
                'label' => 'Progression (%)',
                'constraints' => [new Range(min: 0, max: 100)],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompetitionParticipation::class,
        ]);
    }
}
