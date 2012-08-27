<?php

namespace Travi\framework\content\navigation;

use Travi\framework\content\ContentObject,
    Travi\framework\DependantObject;

class NavigationObject extends ContentObject
{
    /** @var array */
    private $sections = array();
    /** @var string */
    private $sectionTemplate;

    public function addSection($title, $content = '')
    {
        $this->setSection($title, $content);
    }

    public function setSection($title, $content = '')
    {
        $this->sections[$title] = $content;
    }

    public function setSectionTemplate($template)
    {
        $this->sectionTemplate = $template;
    }

    public function getSection($title)
    {
        return $this->sections[$title];
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function getSectionTemplate()
    {
        return $this->sectionTemplate;
    }

    public function addSectionContent($title, $content)
    {
        $this->setSection($title, $content);
    }

    public function addSectionContentLinks($title, $items = array())
    {
        $this->setSection($title, $items);
    }
    public function getDependencies()
    {
        foreach ($this->sections as $section) {
            if (is_a($section, 'DependantObject')) {
                /** @var $section DependantObject */
                $this->addDependencies($section->getDependencies());
            }
        }
        return parent::getDependencies();
    }
}
