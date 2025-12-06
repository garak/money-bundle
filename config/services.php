<?php

use Garak\Money\FormTypeExtension\MoneyTypeExtension;
use Garak\Money\TwigExtension\MoneyFormatRuntime;
use Garak\Money\TwigExtension\MoneyFormat;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set(MoneyFormat::class)
        ->tag('twig.extension')
    ;

    $services
        ->set(MoneyFormatRuntime::class)
        ->args([
            '%garak.money.currency%',
            '%garak.money.decimal%',
            '%garak.money.thousands%',
            '%garak.money.after%',
            '%garak.money.space%',
        ])
        ->tag('twig.runtime')
    ;

    $services
        ->set(MoneyTypeExtension::class)
        ->args([
            '%garak.money.money_transform%',
            '%garak.money.currency%',
        ])
        ->tag('form.type_extension', ['extended_type' => MoneyType::class])
    ;
};
