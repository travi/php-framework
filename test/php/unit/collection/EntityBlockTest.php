<?php

use travi\framework\collection\EntityBlock;
use travi\framework\view\objects\LinkView;

class EntityBlockTest extends PHPUnit_Framework_TestCase
{
    const ANY_PREFIX = 'some path prefix';

    const ANY_ID = 1234;

    /** @var  EntityBlock */
    private $entityBlock;

    public function setUp()
    {
        $this->entityBlock = new EntityBlock(self::ANY_ID, self::ANY_PREFIX);
    }

    public function testThatDefaultConstructionIncludesEditActionAndSelfLink()
    {
        $this->assertEquals(self::ANY_ID, $this->entityBlock->id);

        $primaryActions = $this->entityBlock->getPrimaryActions();
        $this->assertEquals(
            new LinkView('Edit', self::ANY_PREFIX . self::ANY_ID . '/edit'),
            $primaryActions['edit']
        );

        $this->assertEquals(self::ANY_PREFIX . self::ANY_ID, $this->entityBlock->selfLink);
    }

    public function testThatRemoveAddedToPrimaryActionsProperly()
    {
        $this->entityBlock->addRemoveAction();

        $primaryActions = $this->entityBlock->getPrimaryActions();
        $this->assertEquals(
            new LinkView('Remove', self::ANY_PREFIX . self::ANY_ID . '/remove'),
            $primaryActions['remove']
        );
    }
}