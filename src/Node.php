<?php

namespace MarcoKretz\NestedSet;

/**
 * Represents a single node (container) within a NestedSet.
 *
 * @author Marco Kretz <mk@marco-kretz.de>
 */
class Node implements \Serializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Node
     */
    protected $parent;

    /**
     * @var int
     */
    protected $left;

    /**
     * @var int
     */
    protected $right;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Node
     */
    public function getParent(): Node
    {
        return $this->parent;
    }

    /**
     * @param Node $parent
     */
    public function setParent($parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * @param int $left
     */
    public function setLeft(int $left)
    {
        $this->left = $left;
    }

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->right;
    }

    /**
     * @param int $right
     */
    public function setRight(int $right)
    {
        $this->right = $right;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{ name: $this->name, left: $this->left, right: $this->right }";
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
            $this->right
            ) = unserialize($serialized);
    }
}
