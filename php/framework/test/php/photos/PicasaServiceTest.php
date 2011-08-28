<?php

require_once dirname(__FILE__) . '/../../../src/photos/PicasaService.php';
require_once dirname(__FILE__).'/../../../src/photos/Photo.php';
require_once dirname(__FILE__).'/../../../src/http/RestClient.php';

class PicasaServiceTest extends PHPUnit_Framework_TestCase
{
    private $responseFromRestClient;

    protected function setUp()
    {
        $this->responseFromRestClient = file_get_contents(dirname(__FILE__).'/picasaExample.xml');

    }

    public function testApiUriDefinedProperly()
    {
        $this->assertSame(PicasaService::PICASA_URI, "https://picasaweb.google.com/data/feed/api/user/");
    }
    public function testThumbSizeParamDefinedProperly()
    {
        $this->assertSame(PicasaService::THUMBSIZE_QUERY_PARAM, "thumbsize");
    }
    public function testCroppedKeyDefinedProperly()
    {
        $this->assertSame(PicasaService::CROPPED_KEY, "c");
    }
    public function testUncroppedKeyDefinedProperly()
    {
        $this->assertSame(PicasaService::UNCROPPED_KEY, "u");
    }

    public function testProperPhotoObjectsCreatedFromPicasaWebData()
    {
        $anyUser = 'someUserId';
        $anyAlbumId = 'someAlbumId';
        $anyInt = 32;

        $restClient = $this->getMock('RestClient');
        $restClient->expects($this->once())
            ->method('execute');
        $restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . $anyUser
                . '/albumid/'
                . $anyAlbumId
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . $anyInt
            );

        $picasaWeb = new PicasaService();
        $picasaWeb->setRestClient($restClient);
        $picasaWeb->setAlbum($anyAlbumId);
        $picasaWeb->setServiceUser($anyUser);

        $photos = $picasaWeb->getPhotos($anyInt);
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
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/"
            . "AAAAAAAAF7I/x08IKQbCNjw/s270-c/IMG_1245.JPG",
            $firstPhoto->getThumbnail()
        );

        $license = new License();
        $license->setUrl("http://creativecommons.org/licenses/by-nc-nd/3.0");
        $license->setName('Attribution-Noncommercial-No Derivative');
        $license->setId(3);
        $this->assertEquals($license, $firstPhoto->getLicense());
    }

    public function testProperKeyUsedWhenThumbsShouldBeCropped()
    {
        $anyUser = 'someUserId';
        $anyAlbumId = 'someAlbumId';
        $anyInt = 32;

        $restClient = $this->getMock('RestClient');
        $restClient->expects($this->once())
            ->method('execute');
        $restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . $anyUser
                . '/albumid/'
                . $anyAlbumId
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . $anyInt . 'c'
            );

        $picasaWeb = new PicasaService();
        $picasaWeb->setRestClient($restClient);
        $picasaWeb->setAlbum($anyAlbumId);
        $picasaWeb->setServiceUser($anyUser);

        $picasaWeb->getPhotos($anyInt, true);
    }

    public function testProperKeyUsedWhenThumbsShouldNotBeCropped()
    {
        $anyUser = 'someUserId';
        $anyAlbumId = 'someAlbumId';
        $anyInt = 32;

        $restClient = $this->getMock('RestClient');
        $restClient->expects($this->once())
            ->method('execute');
        $restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . $anyUser
                . '/albumid/'
                . $anyAlbumId
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . $anyInt . 'u'
            );

        $picasaWeb = new PicasaService();
        $picasaWeb->setRestClient($restClient);
        $picasaWeb->setAlbum($anyAlbumId);
        $picasaWeb->setServiceUser($anyUser);

        $picasaWeb->getPhotos($anyInt, false);
    }
}
