<?php

namespace Agrume\Limonade\DTO;

use Agrume\Limonade\DTO\DTOInterface;

abstract class AbstractDTO implements DTOInterface
{
    protected array $context;
    protected array $mountableStdClass;
    protected array $payload;
    protected string $shortName;
    /**
     * @inheritDoc
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMountableStdClass(): array
    {
        return $this->mountableStdClass;
    }

    /**
     * @inheritDoc
     */
    public function getMountableStdClassName(): string
    {
        return $this->getMountableStdClass()[0];
    }
    /**
     * @inheritDoc
     */
    public function setMountableStdClass(array $mountableStdClass): self
    {
        $this->mountableStdClass = $mountableStdClass;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serialize(): string
    {
        $format = "test";
        $filtered = $this;
        switch ($format) {
            case 'json':
                return json_encode($filtered, JSON_THROW_ON_ERROR);
                break;
            default:
                return serialize($filtered);
        }
    }

    /**
     * @inheritDoc
     */
    public function unserialize(\Stringable|string $serialized, \Stringable|string $format =''): \Agrume\Limonade\DTO\DTOInterface
    {
        switch ($format) {
//           case 'json':
//               if(method_exists($this,'json_deserialize')) {
//                   return $this->json_deserialize($serialized);
//               }
//               return $this;
            default:
                return unserialize($serialized);
        }
    }

    protected function var_export(): string
    {
        return var_export($this, true);
    }

    /**
     * @inheritDoc
     */
    public function mount(...$payload): object
    {
        return new ($this->getMountableStdClassName())(...$payload);
    }

    /**
     * @inheritDoc
     */
    public function autoMount(): object
    {
        return $this->mount(...$this->getPayload());
    }
    /**
     * @inheritDoc
     */
    public function getPayload(): mixed
    {
        return $this->payload[0];
    }
    /**
     * @inheritDoc
     */
    public function setPayload(mixed ...$payload): self
    {
        $this->payload = $payload;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
       return $this->shortName;
    }
    /**
     * @inheritDoc
     */
    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;
        return $this;
    }

}