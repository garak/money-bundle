<?php

namespace Garak\Money\FormTypeExtension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MoneyTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            static fn (?int $value): ?int => $value,
            static fn (?float $value): ?int => null === $value ? null : (int) $value,
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('divisor', 100);
    }

    public static function getExtendedTypes(): iterable
    {
        return [MoneyType::class];
    }
}
