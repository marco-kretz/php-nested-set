# PHP - NestedSet

[![Build Status](https://travis-ci.com/marco-kretz/php-nested-set.svg?branch=master)](https://travis-ci.com/marco-kretz/php-nested-set)

This is my own implementation of the ["Nested Set Model"](https://en.wikipedia.org/wiki/Nested_set_model).

It has not database connection, yet. Will implement a mysql-synchronization feature soon!

## Requirements

- composer
- PHP >= 7.1

## Node

A Node represents a single node (or container) within the NestedSet. It's uniquely identified by a name.
It can safely be extended (e.g. by a payload attribute) and will still work with the NestedSet.

There is also an extended `Node` called `PayloadNode` which can hold arbitrary, serializable data.

### Usage

```php
$myNode = new Node('myNode');
$anotherNode = new Node('anotherNode);
```

## NestedSet

The heart of this library. It manages a NestedSet-Model and uses `Node` as nodes (containers).
A NestedSet is also an Iterator, so you can simply iterate over it with Nodes as values.
Furthermore it's possible to serialize single Nodes or the whole NestedSet.


### Usage

```PHP
$ns = new NestedSet();

// Define nodes
$rootNode = new Node('root');
$childNode1 = new Node('child1');
$childNode2 = new Node('child2');

// Set root node
$ns->addRoot($rootNode);

// Add nodes
$ns->addNode($rootNode, $childNode1);
$ns->addNode($rootNode, $childNode2);

print($ns);

// Remove node
$ns->removeNode($childNode2);

print($ns);

// Retrieve sub-nodes
$rootChildren = $ns->getSubNodes($rootNode);

for ($rootChildren as $rootChild) {
    print($rootChild);
}

// Iterate over NestedSet
for ($ns as $index => $node) {
    print($node);
}

// Serialization
$serialized = serialize($ns);
$nsCopy = unserialize($serialized);

print($ns === $nsCopy); // true
```

## Testing

`composer test` or simply `phpunit`

## Todo

- Add database sync
- more?
