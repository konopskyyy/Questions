<?php

namespace App\Common\Middleware;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class CommandValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        #[TaggedLocator('app.message_validator', indexAttribute: 'class')]
        private readonly ContainerInterface $validators,
    ) {
    }

    #[\Override]
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $messageClass = get_class($message);
        /** @var string $validatorClass */
        $validatorClass = $messageClass.'Validator';

        if ($this->validators->has($validatorClass)) {
            $validator = $this->validators->get($validatorClass);
            $reflection = new \ReflectionClass($validatorClass);
            $attributes = $reflection->getAttributes(AsMessageValidator::class);
            if (empty($attributes)) {
                throw new \LogicException("Validator $validatorClass must have #[AsMessageValidator] attribute.");
            }
            $validator($message);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
