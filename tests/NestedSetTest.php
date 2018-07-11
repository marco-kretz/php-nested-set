<?php

use MarcoKretz\NestedSet\Exceptions\NoRootDefinedException;
use MarcoKretz\NestedSet\NestedSet;
use MarcoKretz\NestedSet\Node;
use PHPUnit\Framework\TestCase;

final class NestedSetTest extends TestCase
{
    /**
     * @var NestedSet
     */
    private $nestedSet;

    public function setUp()
    {
        $this->nestedSet = new NestedSet();
    }

    public function tearDown()
    {
        unset($this->nestedSet);
    }

    public function testCanAddRoot()
    {
        $rootNode = new Node('root');
        $testNode = $this->nestedSet->addRoot($rootNode);

        $this->assertEquals($rootNode, $testNode);
    }

    public function testCanAddOneChildToRoot()
    {
        $rootNode = new Node('root');
        $childNode = new Node('child');
        $this->nestedSet->addRoot($rootNode);
        $this->nestedSet->addNode($rootNode, $childNode);

        $this->assertEquals(1, $rootNode->getLeft());
        $this->assertEquals(2, $childNode->getLeft());
        $this->assertEquals(3, $childNode->getRight());
        $this->assertEquals(4, $rootNode->getRight());
    }

    public function testCanAddSecondChildToRoot()
    {
        $rootNode = new Node('root');
        $firstChildNode = new Node('child1');
        $secondChildNode = new Node('child2');

        $this->nestedSet->addRoot($rootNode);
        $this->nestedSet->addNode($rootNode, $firstChildNode);
        $this->nestedSet->addNode($rootNode, $secondChildNode);

        $this->assertEquals(1, $rootNode->getLeft());
        $this->assertEquals(2, $firstChildNode->getLeft());
        $this->assertEquals(3, $firstChildNode->getRight());
        $this->assertEquals(4, $secondChildNode->getLeft());
        $this->assertEquals(5, $secondChildNode->getRight());
        $this->assertEquals(6, $rootNode->getRight());
    }

    public function testCanAddThirdChildToRoot()
    {
        $nestedSet = new NestedSet();
        $rootNode = new Node('root');
        $firstChildNode = new Node('child1');
        $secondChildNode = new Node('child2');
        $thirdChildNode = new Node('child3');

        $nestedSet->addRoot($rootNode);
        $nestedSet->addNode($rootNode, $firstChildNode);
        $nestedSet->addNode($rootNode, $secondChildNode);
        $nestedSet->addNode($rootNode, $thirdChildNode);

        $this->assertEquals(1, $rootNode->getLeft());
        $this->assertEquals(2, $firstChildNode->getLeft());
        $this->assertEquals(3, $firstChildNode->getRight());
        $this->assertEquals(4, $secondChildNode->getLeft());
        $this->assertEquals(5, $secondChildNode->getRight());
        $this->assertEquals(6, $thirdChildNode->getLeft());
        $this->assertEquals(7, $thirdChildNode->getRight());
        $this->assertEquals(8, $rootNode->getRight());
    }

    public function testAddNodeToChild()
    {
        $rootNode = new Node('root');
        $childNode = new Node('child');
        $subChildNode = new Node('subChild');

        $this->nestedSet->addRoot($rootNode);
        $this->nestedSet->addNode($rootNode, $childNode);
        $this->nestedSet->addNode($childNode, $subChildNode);

        $this->assertEquals(1, $rootNode->getLeft());
        $this->assertEquals(2, $childNode->getLeft());
        $this->assertEquals(3, $subChildNode->getLeft());
        $this->assertEquals(4, $subChildNode->getRight());
        $this->assertEquals(5, $childNode->getRight());
        $this->assertEquals(6, $rootNode->getRight());
    }

    public function testBigNestedSet()
    {
        $rootNode = new Node('root');
        $this->nestedSet->addRoot($rootNode);

        for ($i = 0; $i < 5; $i++) {
            $levelOneNode = new Node("level1_c$i");
            $this->nestedSet->addNode($rootNode, $levelOneNode);

            for ($j = 0; $j < 3; $j++) {
                $levelTwoNode = new Node("level2_c$i.$j");
                $this->nestedSet->addNode($levelOneNode, $levelTwoNode);
            }
        }

        $this->assertEquals(42, $rootNode->getRight());
    }

    public function testGetSubNodesSimple()
    {
        $rootNode = new Node('root');
        $childNode = new Node('child');

        $this->nestedSet->addRoot($rootNode);
        $this->nestedSet->addNode($rootNode, $childNode);

        $subNodes = $this->nestedSet->getSubNodes($rootNode);

        $this->assertEquals(1, count($subNodes));
        $this->assertEquals($childNode, $subNodes[0]);
    }

    public function testThrowsNoRootDefinedException()
    {
        $this->expectException(NoRootDefinedException::class);
        $this->nestedSet->addNode(new Node('n1'), new Node('n2'));
    }

    public function testThrowsNodeNotInSetException()
    {
        $rootNode = new Node('root');
        $this->nestedSet->addRoot($rootNode);

        $this->expectException(\MarcoKretz\NestedSet\Exceptions\NodeNotInSetException::class);

        $this->nestedSet->addNode(new Node('n1'), new Node('n2'));
    }
}
