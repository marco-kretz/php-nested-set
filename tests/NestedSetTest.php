<?php

use MarcoKretz\NestedSet\NestedSet;
use MarcoKretz\NestedSet\Node;
use PHPUnit\Framework\TestCase;

final class NestedSetTest extends TestCase
{
    /**
     * @var NestedSet
     */
    private $nestedSet;

    /**
     * We need a NestedSet instance for every test method.
     */
    public function setUp()
    {
        $this->nestedSet = new NestedSet();
    }

    /**
     * Safely remove the NestedSet used for the current test method.
     */
    public function tearDown()
    {
        unset($this->nestedSet);
    }

    /**
     * Test, if we can add a root node.
     */
    public function testCanAddRoot()
    {
        $rootNode = new Node('root');
        $testNode = $this->nestedSet->addRoot($rootNode);

        $this->assertEquals($rootNode, $testNode);
    }

    /**
     * Test, if we can add a single child node to the root node.
     */
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

    /**
     * Test, if we can add two child nodes to the root node.
     */
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

    /**
     * Test, if we can add three child nodes to the root node.
     */
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

    /**
     * Test, if we can add a child node to another child node.
     */
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

    /**
     * Let's get big and test a 21-elements sized set.
     */
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

    /**
     * Test, if we can get subnodes within a simple structure.
     */
    public function testGetSubNodesSimple()
    {
        $rootNode = new Node('root');
        $childNode = new Node('child');

        $this->nestedSet->addRoot($rootNode);
        $this->nestedSet->addNode($rootNode, $childNode);

        $subNodes = $this->nestedSet->getSubNodes($rootNode);

        $this->assertEquals(1, count($subNodes));
        $this->assertEquals($childNode, array_pop($subNodes));
    }

    /**
     * Test, if adding a node to a non-existing node returns null.
     */
    public function testAddingNodeToNonExistingParentNoRoot()
    {
        $this->assertNull($this->nestedSet->addNode(new Node('n1'), new Node('n2')));
    }

    /**
     * Test, if an exception is thrown when trying to add a node to an not yet added node.
     */
    public function testAddingNodeToNonExistingParentWithRoot()
    {
        $rootNode = new Node('root');
        $this->nestedSet->addRoot($rootNode);

        $this->assertNull($this->nestedSet->addNode(new Node('n1'), new Node('n2')));
    }

    /**
     * Test, if we can remove a simple node.
     */
    public function testRemoveSimpleNode()
    {
        $rootNode = new Node('root');
        $this->nestedSet->addRoot($rootNode);

        $childNode = new Node('child');
        $this->nestedSet->addNode($rootNode, $childNode);

        $this->assertEquals(4, $rootNode->getRight());

        $removedNode = $this->nestedSet->removeNode($childNode);

        $this->assertEquals($childNode, $removedNode);
        $this->assertEquals(2, $rootNode->getRight());
    }

    /**
     * Test, if we can remove multiple node.
     */
    public function testRemoveNodes()
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

            if (3 === $i) {
                $this->nestedSet->removeNode($levelOneNode);
            }
        }

        $this->assertEquals(34, $rootNode->getRight());
    }

    /**
     * Testm if we can iterate over a simple NestedSet.
     */
    public function testIteratorSimple()
    {
        $rootNode = new Node('0');
        $this->nestedSet->addRoot($rootNode);

        $childNode = new Node('1');
        $this->nestedSet->addNode($rootNode, $childNode);

        $childNodeTwo = new Node('2');
        $this->nestedSet->addNode($rootNode, $childNodeTwo);

        foreach ($this->nestedSet as $index => $node) {
            $this->assertTrue($node instanceof Node);
            $this->assertEquals((string) $index, $node->getName());
        }
    }
}
