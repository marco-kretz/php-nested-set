<?php

require __DIR__.'/../vendor/autoload.php';

$ns = new \MarcoKretz\NestedSet\NestedSet();

$r = new \MarcoKretz\NestedSet\Node('root');
$c1 = new \MarcoKretz\NestedSet\Node('c1');
$c2 = new \MarcoKretz\NestedSet\Node('c2');

$ns->addRoot($r);
$ns->addNode($r, $c1);
$ns->addNode($r, $c2);
