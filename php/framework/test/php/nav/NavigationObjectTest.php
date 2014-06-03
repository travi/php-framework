<?php

use travi\framework\navigation\NavigationObject;

class NavigationObjectTest extends PHPUnit_Framework_TestCase
{
    /** @var NavigationObject */
    protected $object;

    protected function setUp()
    {
        $this->object = new NavigationObject;
    }

    public function testAddSection()
    {
        $title = 'title';
        $content = 'content';

        $this->object->addSection($title, $content);

        $this->assertSame(array($title => $content), $this->object->getSections());
    }

    public function testSetSection()
    {
        $title = 'title';
        $content = 'content';

        $this->object->setSection($title, $content);

        $this->assertSame(array($title => $content), $this->object->getSections());
    }

    public function testGetSection()
    {
        $title = 'title';
        $content = 'content';

        $this->object->setSection($title, $content);

        $this->assertSame($content, $this->object->getSection($title));
    }

    public function testAddSectionContent()
    {
        $title = 'title';
        $content = 'content';

        $this->object->addSectionContent($title, $content);

        $this->assertSame(array($title => $content), $this->object->getSections());
    }

    public function testAddSectionContentLinks()
    {
        $title = 'title';
        $links = array('link1', 'link2');

        $this->object->addSectionContentLinks($title, $links);

        $this->assertSame(array($title => $links), $this->object->getSections());
    }

    public function testSectionTemplate()
    {
        $template = 'template';

        $this->object->setSectionTemplate($template);
        $this->assertSame($template, $this->object->getSectionTemplate());
    }
}
?>
