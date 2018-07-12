<?php

namespace MarcoKretz\NestedSet;

/**
 * Node that can carry arbitrary, serializable data (objects).
 *
 * @author Marco Kretz <mk@marco-kretz.de>
 */
class PayloadNode extends Node
{
    /**
     * @var \Serializable
     */
    private $payload;

    /**
     * @param string        $name
     * @param \Serializable $payload
     */
    public function __construct(string $name, \Serializable $payload)
    {
        parent::__construct($name);
        $this->payload = $payload;
    }

    /**
     * @return \Serializable
     */
    public function getPayload(): \Serializable
    {
        return $this->payload;
    }

    /**
     * @param \Serializable $payload
     */
    public function setPayload(\Serializable $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * String representation of object
     *
     * @return string the string representation of the object or null
     */
    public function serialize(): string
    {
        return serialize([
            $this->name,
            $this->parent,
            $this->left,
            $this->right,
            $this->payload,
        ]);
    }

    /**
     * Constructs the object
     *
     * @param string $serialized <p>
     *
     * @return void
     */
    public function unserialize($serialized): void
    {
        list(
            $this->name,
            $this->parent,
            $this->left,
            $this->right,
            $this->payload
            ) = unserialize($serialized);
    }
}
