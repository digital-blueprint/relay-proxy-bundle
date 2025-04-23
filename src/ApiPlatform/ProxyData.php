<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\ApiPlatform;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use Dbp\Relay\CoreBundle\ProxyApi\ProxyDataInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'ProxyData',
    operations: [
        new Post(
            uriTemplate: '/proxydata',
            openapi: new Operation(
                tags: ['Proxy'],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                            ],
                        ],
                    ]),
                ),
            ),
            processor: ProxyDataProcessor::class
        ),
    ],
    normalizationContext: [
        'groups' => ['ProxyData:output'],
        'jsonld_embed_context' => true,
    ],
    denormalizationContext: [
        'groups' => ['ProxyData:input'],
    ],
)]
class ProxyData implements ProxyDataInterface
{
    #[Groups(['ProxyData:output'])]
    private ?string $identifier = null;

    #[Groups(['ProxyData:input'])]
    private array $arguments = [];

    #[Groups(['ProxyData:output'])]
    private mixed $data = null;

    #[Groups(['ProxyData:output'])]
    private ?array $errors = null;

    #[Groups(['ProxyData:input'])]
    private ?string $functionName = null;

    #[Groups(['ProxyData:input'])]
    private ?string $namespace = null;

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function setFunctionName(?string $functionName): void
    {
        $this->functionName = $functionName;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function setErrorsFromException(\Exception $exception): void
    {
        $this->errors = [];
        do {
            $this->errors[] = [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];

            $exception = $exception->getPrevious();
        } while ($exception !== null);
    }
}
