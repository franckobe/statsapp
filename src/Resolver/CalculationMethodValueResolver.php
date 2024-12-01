<?php
namespace App\Resolver;

use App\Enum\CalculationMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CalculationMethodValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== CalculationMethod::class) {
            return [];
        }

        $method = $request->attributes->get($argument->getName());

        foreach (CalculationMethod::cases() as $case) {
            if ($case->name === $method || $case->value === $method) {
                return [$case];
            }
        }

        throw new \InvalidArgumentException(sprintf('Invalid calculation method: "%s".', $method));
    }
}
