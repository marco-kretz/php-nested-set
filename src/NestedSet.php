<?php

namespace MarcoKretz\NestedSet;

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
     * @return null|Node
     */
    public function addRoot(Node $node) : ?Node
    {
        if (empty($this->root) && empty($this->nodes)) {
            $node->setLeft(1);
            $node->setRight(2);
            $node->setParent(null);

            $this->root = $node;
            $this->nodes[] = $node;

            return $node;
        }

        return null;
    }

    /**
     * Adds a new node under the given parent Node.
     *
     * @param Node $parent
     * @param Node $node
     *
     * @return null|Node
     */
    public function addNode(Node $parent, Node $node): ?Node
    {
        if (empty($this->root) || !$this->exists($parent) || $this->exists($node)) {
            return null;
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
     * @param string $name The node's name
     *
     * @return null|Node Node found, else null
     */
    public function getNode(string $name): ?Node
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
     *
     * @return null|Node Removed node on success, else null
     */
    public function removeNode(Node $node): ?Node
    {
        if (!$this->exists($node)) {
            return null;
        }

        // Remove $node with all its sub-nodes
        $nodesToRemove = array_merge([$node], $this->getSubNodes($node));
        $this->nodes = array_diff($this->nodes, $nodesToRemove);

        // Recalculate left and right values
        $width = $node->getRight() - $node->getLeft() + 1;
        foreach ($this->nodes as $currentNode) {
            if ($currentNode instanceof Node) {
                if ($currentNode->getLeft() > $node->getRight()) {
                    $currentNode->setLeft($currentNode->getLeft() - $width);
                }
                if ($currentNode->getRight() > $node->getRight()) {
                    $currentNode->setRight($currentNode->getRight() - $width);
                }
            }
        }

        return $node;
    }

    /**
     * Get all sub-nodes for a given node.
     * The array indices are the actual indices in the NestedSet nodes-array. Reindex possible.
     *
     * @param Node $parent
     * @param bool $reindex Wether to reindex the array or not
     *
     * @return null|array
     */
    public function getSubNodes(Node $parent, bool $reindex = false): ?array
    {
        if (!$this->exists($parent)) {
            return null;
        }

        $subNodes = [];

        foreach ($this->nodes as $index => $node) {
            if ($node instanceof Node) {
                if ($node->getLeft() > $parent->getLeft() && $node->getRight() < $parent->getRight()) {
                    $subNodes[$index] = $node;
                }
            }
        }

        return ($reindex ? array_values($subNodes) : $subNodes);
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
     * Check if a node already exists.
     *
     * @param Node $node
     *
     * @return bool
     */
    public function exists(Node $node): bool
    {
        foreach ($this->nodes as $currentNode) {
            if ($currentNode instanceof Node && $currentNode->getName() === $node->getName()) {
                return true;
            }
        }

        return false;
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
