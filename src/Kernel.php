<?php

namespace App;

use App\Common\Attribute\AsMessageValidator;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $this->registerValidators($container);
    }

    public function registerValidators(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->registerAttributeForAutoconfiguration(
            attributeClass: AsMessageValidator::class,
            configurator: static function (
                ChildDefinition $childDefinition,
                AsMessageValidator $attribute,
            ): void {
                $childDefinition->addTag('message.validator', get_object_vars($attribute));
            }
        );
    }
}
