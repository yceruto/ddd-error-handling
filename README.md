# DDD Error Handling

## Define your Domain exceptions

In Shared context, create generic Domain exceptions:

```php
namespace App\Shared\Domain\Exception;

class NotFound extends \DomainException
{
}
```

Next, configure how the presentation layer will interpret the given exception in HTTP context and its status code:

```yaml
# config/packages/framework.yaml
framework:
    exceptions:
        App\Shared\Domain\Exception\NotFound:
            status_code: 404
```

After that, every custom Domain exception extending from `NotFound` will return a `404` status code:

```php
namespace App\Order\Domain\Model;

use App\Shared\Domain\Exception\NotFound;

class OrderNotFound extends NotFound
{
    public static function create(string $id): self
    {
        return new self(sprintf('The order "%s" could not be found.', $id));
    }
}
```

## Customizing the exception response

Installing the `Serializer` component will have a significant effect on how problem exceptions are handled. By default,
the `Symfony\Component\Serializer\Normalizer\ProblemNormalizer` will take care of it following the https://tools.ietf.org/html/rfc7807 spec.

However, you can customize it by creating a new normalizer class:

```php
namespace App\Shared\Presentation\Serializer\Normalizer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DomainExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param FlattenException $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'title' => $object->getStatusText(),
            'status' => $object->getStatusCode(),
            'detail' => $object->getMessage(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }
}
```

Note that here you don't care about the response format (JSON, XML) as it's another layer that instead will depend on
the request format (Content-Type, Accept).

## Testing

Look at the functional test to see how it works in practice:

```ssh
./bin/phpunit
```
