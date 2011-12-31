<?php

class JsonRendererTest extends PHPUnit_Framework_TestCase
{
    public function testObjectSerializedToJson()
    {
        $jsonRenderer = new JsonRenderer();

        $album = new Album();
        $album->setTitle('someTitle');
        $photo = new Photo();
        $photo->setOriginal('someUrl');
        $album->setPhotos(array($photo));

        $json = $jsonRenderer->format(array($album));

        $this->assertEquals(
            '{"title":"someTitle","photos":{"original":"someUrl","url":"someUrl"}}',
            $json
        );
    }
}