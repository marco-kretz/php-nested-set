<?php

namespace MarcoKretz\NestedSet;

/**
 * Represents a single node (container) within a NestedSet.
 *
 * @author Marco Kretz <mk@marco-kretz.de>
 */
class Node
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Node
     */
    private $parent;

    /**
     * @var int
     */
    private $left;

    /**
     * @var int
     */
    private $right;

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
}
