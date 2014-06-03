<?php

use travi\framework\content\ContentObject;

class BaseList extends ContentObject
{
    var $items = array();
    var $surroundingTag;
    var $itemTag;

    function buildLink($link = array(), $class="")
    {
        if (isset($link['text']) && isset($link['link'])) {
            $html = '<a href="'.$link['link'].'"';
            if (!empty($class)) {
                $html .= ' class="indentUrl"';
            }
            if (!empty($link['confirmation'])) {
                $html .= ' onclick="if (confirm(\'' . $link['confirmation'] .
                         '\')) return true; else return false;"';
            }
            $html .= '>'.$link['text'].'</a>';
        } else if (isset($link['text'])) {
            $html = $link['text'];
        } else {
            $html = $link;
        }

        return $html;
    }
    function listItems($links = array())
    {
        foreach ($links as $item) {
            if (is_array($item)) {
                $list .= "
                <$this->itemTag>".$this->buildLink($item)."</$this->itemTag>";
            }
        }

        return $list;
    }
    function __toString()
    {
        $list = "
                <$this->surroundingTag>";

        foreach ($this->items as $heading => $links) {
            if (is_object($links) && is_a($links, 'ContentObject')) {
                $list .= "
                <$this->itemTag>".$links."</$this->itemTag>";
                $this->checkDependencies($links);
            } elseif (isset($links['text']) && isset($links['link'])) {
                $list .= "
                <$this->itemTag>".$this->buildLink($links)."</$this->itemTag>";
            } else {
                $list .= "
                    <$this->itemTag>";
                if (isset($links['link'])) {
                    $list .= '<a href="'.$links['link'].'">';
                }
                $list .= "$heading";
                if (isset($links['link'])) {
                    $list .= '</a>';
                }
                $list .= "
                        <$this->surroundingTag>";
                $list .= $this->listItems($links);
                $list .= "
                        </$this->surroundingTag>
                    </$this->itemTag>";
            }
        }

        $list .= "
                </$this->surroundingTag>";

        return $list;
    }
}

class UnorderedList extends BaseList
{
    function UnorderedList($items = array())
    {
        $this->items = $items;
        $this->surroundingTag = 'ul';
        $this->itemTag = 'li';
    }
}

class DefinitionList extends BaseList
{
    function DefinitionList($items = array())
    {
        $this->items = $items;
        $this->surroundingTag = 'dl';
        $this->itemTag = 'dd';
    }
    function __toString()
    {
        $list = "
            <$this->surroundingTag>";

        foreach ($this->items as $heading => $links) {
            if (isset($links['text']) && isset($links['link'])) {
                $list .= "
                <dt>".$this->buildLink($links)."</dt>";
            } else {
                $list .= "
                <dt>$heading</dt>";
                    $list .= $this->listItems($links);
            }
        }

        $list .= "
            </$this->surroundingTag>";

        return $list;
    }
}

/////////////////////////////////////
//		Expandable Lists		   //
/////////////////////////////////////

class ExpandableList extends BaseList
{
    function __toString()
    {
        $nav = '';

        foreach ($this->items as $heading => $links) {
            $secName = strtolower($heading);
            $secName = str_replace(' ', '_', $secName);

            if (is_a($this, 'ExpandableSpan')) {
                $nav .= '
            <p>';
            }

            if (isset($links['text']) && isset($links['link'])) {
                $nav .= "
                <$this->surroundingTag>".$this->buildLink($links)."</$this->surroundingTag>";
            } else {
                $nav .= '
                <'.$this->surroundingTag.' id="m_' . $secName . '_closed">
                    <a href="javascript:hide(\'m_' . $secName . '_closed\');show(\'m_' . $secName
                        . '_open\',\'m_'.$secName.'\');">
                        '.$heading.' <img src="/reusable/images/down_arrow.gif" border="0" alt="arrow" />
                    </a>
                </'.$this->surroundingTag.'>
                <'.$this->surroundingTag.' id="m_' . $secName . '_open" style="display: none">
                    <a href="javascript:hide(\'m_' . $secName . '_open\',\'m_' . $secName
                        . '\');show(\'m_'.$secName.'_closed\');">
                        '.$heading.' <img src="/reusable/images/up_arrow.gif" border="0" alt="arrow" />
                    </a>
                </'.$this->surroundingTag.'>
                <span id="m_'.$secName.'" style="display: none;">';

                foreach ($links as $link) {
                    $nav .= '
                    <'.$this->itemTag.'>'.$this->buildLink($link, 'indentUrl');

                    if ($this->itemTag != 'br /') {
                        $nav .= '</'.$this->itemTag.'>';
                    }
                }
                $nav .= '
                </span>';
            }

            if (is_a($this, 'ExpandableSpan')) {
                $nav .= '
            </p>';
            }
        }

        return $nav;
    }
}

class ExpandableDl extends ExpandableList
{
    function ExpandableDl($items = array())
    {
        $this->items = $items;
        $this->surroundingTag = 'dt';
        $this->itemTag = 'dd';
        $this->addJavaScript('/reusable/js/formatting.js');
    }
    function __toString()
    {
        $list = '
        <dl>';
        $list .= parent::__toString();
        $list .= '
        </dl>';

        return $list;
    }
}

class ExpandableDiv extends ExpandableList
{
    function ExpandableDiv($items = array())
    {
        $this->items = $items;
        $this->surroundingTag = 'div';
        $this->itemTag = 'br /';
        $this->addJavaScript('/reusable/js/formatting.js');
    }
}

class ExpandableSpan extends ExpandableList
{
    function ExpandableSpan($items = array())
    {
        $this->items = $items;
        $this->surroundingTag = 'span';
        $this->itemTag = 'br /';
        $this->addJavaScript('/reusable/js/formatting.js');
    }
}
