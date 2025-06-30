<?php

namespace Agrume\Limonade\DTO;

interface DTOInterface
{
    /**
     * Optional conditions.
     *
     * Example: permit to handle context comportment eg. on case return HTTPException instead of Exception
     *
     */
    public function getContext(): array;
    /**
     * Optional conditions.
     *
     * Example: set context comportment eg. on case return HTTPException instead of Exception
     *
     * @param mixed[] $context
     */
    public function setContext(array $context): self;
    /**
     * Return instance
     *
     * @param mixed[] $payload payload to mount the instance
     *
     * @return Object return the correct instance of object "kernel.exception" return exception
     */
    public function mount(mixed $payload): Object;
    /**
     * Return payload to put inside the Event interface to mount
     *
     *
     * @return mixed[] return the correct instance of object "kernel.exception" return exception
     */
    public function getPayload(): mixed;
    /**
     * Set payload to put inside the Event interface to mount
     *
     * @param mixed[] $payload payload to mount the instance
     *
     * @return mixed[] return the correct instance of object "kernel.exception" return exception
     */
    public function setPayload(mixed $payload): self;
    /**
     * Return instance mounted with payload
     *
     * @return Object return the correct instance of object mounted with payload "kernel.exception" return new Exception(...$self->payload)
     */
    public function autoMount(): object;

    /**
     * Return stdClass array
     *
     * @return string[] return the stdClass "kernel.exception" return exception
     */
    public function getMountableStdClass(): array;

    /**
     * get correct stdClass name
     *
     * @return string set the correct object stdClass
     */
    public function getMountableStdClassName(): string;
    /**
     * Set stdClass
     *
     * @return self set the correct object stdClass
     */
    public function setMountableStdClass(array $mountableStdClass): self;

    /**
     * Serialize this DTO into json, in preview to add more format in the future
     *
     * @return string return the correct object stdClass "kernel.exception" return exception
     */
    public function serialize(): string;
    /**
     * Serialize this DTO into json, in preview to add more format in the future
     *
     * @param string $serialized
     * @param string $format
     *
     * @return self return instance of self::class with $serialized parameters
     */
    public function unserialize(string|\Stringable $serialized, string|\Stringable $format): self;

    /**
     * Get the Reflexion Class Shortname
     * @return string
     */
    public function getShortName(): string;

    /**
     * Get the Reflexion Class Shortname to automate things when mounted
     * @param string $shortName
     * @return self
     */
    public function setShortName(string $shortName): self;

}