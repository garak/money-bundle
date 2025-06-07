<?php

namespace Garak\Money\FormTypeExtension;

use Money\Currency;
use Money\Money;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MoneyTypeExtension extends AbstractTypeExtension
{
    /**
     * @param non-empty-string $currency
     */
    public function __construct(
        private bool $transformToMoney = false,
        private string $currency = 'EUR',
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($this->transformToMoney) {
            $transformer = new CallbackTransformer(
                static fn (?Money $value): ?string => $value?->getAmount(),
                fn (?float $value): ?Money => null === $value ? null : new Money((string) $value, new Currency($this->currency)),
            );
        } else {
            $transformer = new CallbackTransformer(
                static fn (?int $value): ?int => $value,
                static fn (?float $value): ?int => null === $value ? null : (int) $value,
            );
        }
        $builder->addModelTransformer($transformer);
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
