<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\ApiPlatform;

use Dbp\Relay\CoreBundle\ProxyApi\ProxyDataInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class ProxyData implements ProxyDataInterface
{
    /**
     * @Groups({"ProxyData:output"})
     *
     * @var string
     */
    private $identifier;

    /**
     * @Groups({"ProxyData:input"})
     *
     * @var array
     */
    private $arguments;

    /**
     * @Groups({"ProxyData:output"})
     *
     * @var mixed|null
     */
    private $data;

    /**
     * @Groups({"ProxyData:output"})
     *
     * @var array|null
     */
    private $errors;

    /**
     * @Groups({"ProxyData:input"})
     *
     * @var string
     */
    private $functionName;

    /**
     * @Groups({"ProxyData:input"})
     *
     * @var string
     */
    private $namespace;

    public function __construct()
    {
        $this->arguments = [];
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
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

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
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

    public function setFunctionName(string $functionName): void
    {
        $this->functionName = $functionName;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
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
