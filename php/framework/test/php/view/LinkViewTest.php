<?php

namespace view;


use PHPUnit_Framework_TestCase;
use travi\framework\view\objects\LinkView;

class LinkViewTest extends PHPUnit_Framework_TestCase
{
    public function testThatTemplateIsSetCorrectly()
    {
        $linkView = new LinkView('foo', 'bar');

        $this->assertEquals('components/link.tpl', $linkView->getTemplate());
    }
}