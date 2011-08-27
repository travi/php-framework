<?php

require_once dirname(__FILE__).'/../../../src/photos/PicasaWeb.php';
require_once dirname(__FILE__).'/../../../src/photos/Photo.php';
require_once dirname(__FILE__).'/../../../src/http/RestClient.php';

class PicasaWebTest extends PHPUnit_Framework_TestCase
{
    public function testProperPhotoObjectsCreatedFromPicasaWebData()
    {
        $response = file_get_contents('picasaExample.xml');

        $restClient = $this->getMock('RestClient');
        $restClient->expects($this->once())
            ->method('execute');
        $restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($response));

        $picasaWeb = new PicasaWeb();
        $picasaWeb->setRestClient($restClient);

        $photos = $picasaWeb->getPhotos();
        /** @var $firstPhoto Photo */
        $firstPhoto = $photos[0];

        $this->assertNotNull($photos);
        $this->assertTrue(is_array($photos));
        $this->assertEquals(3, count($photos));
        $this->assertFalse(empty($photos));

        $this->assertType('Photo', $firstPhoto);
        $this->assertEquals(
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/IMG_1245.JPG",
            $firstPhoto->getOriginal()
        );
        $this->assertEquals(
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/s270-c/IMG_1245.JPG",
            $firstPhoto->getThumbnail()
        );

        $license = new License();
        $license->setUrl("http://creativecommons.org/licenses/by-nc-nd/3.0");
        $license->setName('Attribution-Noncommercial-No Derivative');
        $license->setId(3);
        $this->assertEquals($license, $firstPhoto->getLicense());
    }
}
