<?php

require_once dirname(__FILE__) . '/../../../src/photos/PicasaService.php';
require_once dirname(__FILE__).'/../../../src/photos/Photo.php';
require_once dirname(__FILE__).'/../../../src/http/RestClient.php';

class PicasaServiceTest extends PHPUnit_Framework_TestCase
{
    private $responseFromRestClient;

    const SOME_USER_ID = 'someUserId';
    private $restClient;

    /** @var PicasaService */
    private $picasaWeb;

    protected function setUp()
    {
        $this->picasaWeb = new PicasaService();
        $this->restClient = $this->getMock('RestClient');

        $this->picasaWeb->setServiceUser(self::SOME_USER_ID);

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

    public function testProperListOfAlbumsReturnedFromPicasaWebData()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . self::SOME_USER_ID
            );
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue("something"));

        $this->picasaWeb->setRestClient($this->restClient);

        $albums = $this->picasaWeb->getAlbums();

        $this->assertNotNull($albums);
    }

    public function testProperPhotoObjectsCreatedFromPicasaWebData()
    {
        $anyAlbumId = 'someAlbumId';
        $anyInt = 32;

        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $this->restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . self::SOME_USER_ID
                . '/albumid/'
                . $anyAlbumId
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . $anyInt
            );

        $this->picasaWeb->setRestClient($this->restClient);
        $this->picasaWeb->setAlbum($anyAlbumId);

        $photos = $this->picasaWeb->getPhotos($anyInt);
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
        $anyAlbumId = 'someAlbumId';
        $anyInt = 32;

        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $this->restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . self::SOME_USER_ID
                . '/albumid/'
                . $anyAlbumId
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . $anyInt . 'c'
            );

        $this->picasaWeb->setRestClient($this->restClient);
        $this->picasaWeb->setAlbum($anyAlbumId);

        $this->picasaWeb->getPhotos($anyInt, true);
    }

    public function testProperKeyUsedWhenThumbsShouldNotBeCropped()
    {
        $anyUser = self::SOME_USER_ID;
        $anyAlbumId = 'someAlbumId';
        $anyInt = 32;

        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $this->restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . $anyUser
                . '/albumid/'
                . $anyAlbumId
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . $anyInt . 'u'
            );

        $this->picasaWeb->setRestClient($this->restClient);
        $this->picasaWeb->setAlbum($anyAlbumId);
        $this->picasaWeb->setServiceUser($anyUser);

        $this->picasaWeb->getPhotos($anyInt, false);
    }
}