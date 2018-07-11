<?php

namespace MarcoKretz\NestedSet;

use MarcoKretz\NestedSet\Exceptions\NodeNotInSetException;
use MarcoKretz\NestedSet\Exceptions\NoRootDefinedException;

/**
 * Implementation of the "Nested Set Model"
 *
 * @author Marco Kretz <mk@marco-kretz.de>
 */
class NestedSet
{
    /**
     * @var Node
     */
    private $root;

    /**
     * @var array
     */
    private $nodes = [];

    /**
     * @param Node $node
     *
     * @return Node
     */
    public function addRoot(Node $node) : Node
    {
        if (empty($this->root) && empty($this->nodes)) {
            $node->setLeft(1);
            $node->setRight(2);
            $node->setParent(null);

            $this->root = $node;
            $this->nodes[] = $node;
        }

        return $node;
    }

    /**
     * Adds a new node under the given parent Node.
     *
     * @param Node $parent
     * @param Node $node
     *
     * @return Node
     *
     * @throws NoRootDefinedException
     * @throws NodeNotInSetException
     */
    public function addNode(Node $parent, Node $node): Node
    {
        if (empty($this->root)) {
            throw new NoRootDefinedException('First add a root via addRoot()!');
        }

        if (!in_array($parent, $this->nodes)) {
            throw new NodeNotInSetException('Add the parent via addNode() first!');
        }

        foreach ($this->nodes as $currentNode) {
            if ($currentNode instanceof Node && $currentNode !== $parent) {
                if ($currentNode->getLeft() > $parent->getRight()) {
                    $currentNode->setLeft($currentNode->getLeft() + 2);
                }
                if ($currentNode->getRight() > $parent->getRight()) {
                    $currentNode->setRight($currentNode->getRight() + 2);
                }
            }
        }

        $parent->setRight($parent->getRight() + 2);
        $node->setLeft($parent->getRight() - 2);
        $node->setRight($parent->getRight() - 1);
        $node->setParent($parent);

        $this->nodes[] = $node;

        return $node;
    }

    /**
     * Get a single node by its name.
     *
     * @param string $name
     *
     * @return null|Node
     */
    public function getNode(string $name): Node
    {
        foreach ($this->nodes as $node) {
            if ($node instanceof Node && $node->getName() === $name) {
                return $node;
            }
        }

        return null;
    }

    /**
     * Remove a node including its sub-nodes.
     *
     * @param Node $node
     */
    public function removeNode(Node $node)
    {
        // TODO: Implement :)
    }

    /**
     * Get all sub Nodes of a given Node, ignoring level.
     *
     * @param Node $parent
     *
     * @return array
     */
    public function getSubNodes(Node $parent): array
    {
        $subNodes = [];

        foreach ($this->nodes as $node) {
            if ($node instanceof Node) {
                if ($node->getLeft() > $parent->getLeft() && $node->getRight() < $parent->getRight()) {
                    $subNodes[] = $node;
                }
            }
        }

        return $subNodes;
    }

    /**
     *  Clear NestedSet, removing all nodes.
     */
    public function clear(): void
    {
        unset($this->root);
        unset($this->nodes);

        $this->root = null;
        $this->nodes = [];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $s = '';
        foreach ($this->nodes as $node) {
            if ($node instanceof Node) {
                $s .= (string) $node.PHP_EOL;
            }
        }

        return $s;
    }
}
