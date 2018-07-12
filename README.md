# PHP - NestedSet

[![Build Status](https://travis-ci.com/marco-kretz/php-nested-set.svg?branch=master)](https://travis-ci.com/marco-kretz/php-nested-set)

This is my own implementation of the ["Nested Set Model"](https://en.wikipedia.org/wiki/Nested_set_model).

It has not database connection, yet. Will implement a mysql-synchronization feature soon!

## Usage

```PHP
$ns = new NestedSet();

$rootNode = new Node('root');
$childNode1 = new Node('child1');
$childNode2 = new Node('child2');

$ns->addRoot($rootNode); // addNode will throw an exception if no root is defined!
$ns->addNode($rootNode, $childNode1); // addNode will throw an exception if the given parent was not added
$ns->addNode($rootNode, $childNode2);

print($ns);

$rootChildren = $ns->getSubNodes($rootNode);

for ($rootChildren as $rootChild) {
    print($rootChild);
}
```

## Todo

- Add database sync
- more?
