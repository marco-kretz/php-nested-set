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
     * Adds a new Node under the given parent Node.
     *
     * @param Node $parent
     * @param Node $node
     *
     * @return Node
     *
     * @throws NoRootDefinedException
     * @throws NodeNotInSetException
     */
    public function addNode(Node $parent, Node $node)
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
     * Get all sub Nodes of a given Node, ignoring level.
     *
     * @param Node $parent
     *
     * @return array
     */
    public function getSubNodes(Node $parent)
    {
        $subNodes = [];

        foreach ($this->nodes as $node) {
            if ($node instanceof Node) {
                if ($node->getLeft() > $parent->getLeft() && $node->getRight() < $parent->getRight()) {
                    array_push($subNodes, $node);
                }
            }
        }

        return $subNodes;
    }

    /**
     *  Clear NestedSet, removing all Nodes.
     */
    public function clear()
    {
        $this->nodes = [];
    }

    /**
     * @return string
     */
    public function __toString()
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
