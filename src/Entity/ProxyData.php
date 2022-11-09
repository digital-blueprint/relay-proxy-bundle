<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Dbp\Relay\CoreBundle\ProxyApi\ProxyDataInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post" = {
 *             "security" = "is_granted('IS_AUTHENTICATED_FULLY') and is_granted('ROLE_SCOPE_API-PROXY')",
 *             "path" = "/proxy/proxydata",
 *             "openapi_context" = {
 *                 "tags" = {"Proxy"},
 *                 "requestBody" = {
 *                     "content" = {
 *                         "application/json" = {
 *                             "schema" = {"type" = "object"},
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get" = {
 *             "security" = "is_granted('IS_AUTHENTICATED_FULLY')",
 *             "path" = "/proxy/proxydata",
 *             "openapi_context" = {
 *                 "tags" = {"Proxy"},
 *             },
 *         }
 *     },
 *     itemOperations={
 *         "get" = {
 *             "path" = "/proxy/proxydata/{identifier}",
 *             "openapi_context" = {
 *                 "tags" = {"Proxy"}
 *             },
 *         },
 *     },
 *     shortName="ProxyData",
 *     normalizationContext={
 *         "groups" = {"ProxyData:output"},
 *         "jsonld_embed_context" = true
 *     },
 *     denormalizationContext={
 *         "groups" = {"ProxyData:input"},
 *         "jsonld_embed_context" = true
 *     }
 * )
 */
class ProxyData implements ProxyDataInterface
{
    /**
     * @ApiProperty(identifier=true)
     * @Groups({"ProxyData:output"})
     *
     * @var string
     */
    private $identifier;

    /**
     * @ApiProperty
     * @Groups({"ProxyData:input"})
     *
     * @var array
     */
    private $arguments;

    /**
     * @ApiProperty
     * @Groups({"ProxyData:output"})
     *
     * @var mixed|null
     */
    private $data;

    /**
     * @ApiProperty
     * @Groups({"ProxyData:output"})
     *
     * @var array|null
     */
    private $errors;

    /**
     * @ApiProperty
     * @Groups({"ProxyData:input"})
     *
     * @var string
     */
    private $functionName;

    /**
     * @ApiProperty
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
