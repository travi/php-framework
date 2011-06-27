<?php
require_once('contentObject.class.php');

class PreviewWindow extends ContentObject
{
    var $url;
    var $text;
    var $field;

    function PreviewWindow()
    {
        $this->addJavaScript('/reusable/js/windows_js/javascripts/prototype.js');
        $this->addJavaScript('/reusable/js/windows_js/javascripts/window.js');
        $this->addJavaScript('/reusable/js/windows_js/previewURL.js');

        $this->addStyleSheet('/reusable/js/windows_js/themes/default.css');
        $this->addStyleSheet('/reusable/js/windows_js/themes/alphacube.css');
    }
    function setURL($url)
    {
        $this->url = $url;
    }
    function setLinkText($text)
    {
        $this->text = $text;
    }
    function setField($field)
    {
        $this->field = $field;
    }
    function __toString()
    {
        $link = '<a href="#" onclick="';
        if (isset($this->field)) {
            $link .= "previewUrlFromTextField('".$this->field."')";
        } else if (isset($this->url)) {
            $link .= "previewURL('".$this->url."')";
        }
        $link .= '">'.$this->text.'</a>';

        return $link;
    }
}
