<?php

require_once dirname(__FILE__) . '/../../../src/photos/PicasaService.php';
require_once dirname(__FILE__).'/../../../src/photos/Photo.php';
require_once dirname(__FILE__).'/../../../src/photos/Thumbnail.php';
require_once dirname(__FILE__).'/../../../src/http/RestClient.php';

class PicasaServiceTest extends PHPUnit_Framework_TestCase
{
    private $responseFromRestClient;

    const SOME_USER_ID = 'someUserId';
    const ANY_INT = 42;

    private $restClient;
    const ANY_ALBUM_ID = 'someAlbumId';

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
            ->will($this->returnValue(file_get_contents(dirname(__FILE__).'/picasaAlbumsExample.xml')));

        $this->picasaWeb->setRestClient($this->restClient);

        $albums = $this->picasaWeb->getAlbums();
        /** @var $firstAlbum Album */
        $firstAlbum = $albums[0];

        $this->assertNonEmptyArray($albums);
        $this->assertType('Album', $firstAlbum);
        $this->assertEquals("Steamboat 2011", $firstAlbum->getTitle());
        $this->assertEquals(
            "https://picasaweb.google.com/107098889836094611170/Steamboat2011",
            $firstAlbum->getUrl()
        );
        $this->assertEquals('5575255329214352817', $firstAlbum->getId());

        $thumbnail = new Thumbnail();
        $thumbnail->setUrl(
            "https://lh5.googleusercontent.com/-ePrl_rE_oWs/TV9JLtXszbE/AAAAAAAAHEY/"
            . "JAYLTmv0rqI/s160-c/Steamboat2011.jpg"
        );
        $this->assertEquals($thumbnail, $firstAlbum->getThumbnail());
    }

    public function testProperPhotoObjectsCreatedFromPicasaWebData()
    {
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $this->restClient->expects($this->once())
            ->method('setEndpoint')
            ->with(
                PicasaService::PICASA_URI
                . self::SOME_USER_ID
                . '/albumid/'
                . self::ANY_ALBUM_ID
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . self::ANY_INT . PicasaService::UNCROPPED_KEY
            );

        $this->picasaWeb->setRestClient($this->restClient);

        $photos = $this->picasaWeb->getPhotos(
            array(
                'albumId' => self::ANY_ALBUM_ID,
                'thumbnail' => array(
                    'size' => self::ANY_INT
                ),
                'preview' => array(
                    'width' => 600
                )
            )
        );
        /** @var $firstPhoto Photo */
        $firstPhoto = $photos[0];

        $this->assertNonEmptyArray($photos);
        $this->assertEquals(3, count($photos));

        $this->assertType('Photo', $firstPhoto);
        $this->assertEquals(
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/IMG_1245.JPG",
            $firstPhoto->getOriginal()
        );
        $this->assertEquals(
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/s600/IMG_1245.JPG",
            $firstPhoto->getPreview()
        );
        $thumbnail = new Thumbnail();
        $thumbnail->setUrl(
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/"
            . "AAAAAAAAF7I/x08IKQbCNjw/s270-c/IMG_1245.JPG"
        );
        $this->assertEquals($thumbnail, $firstPhoto->getThumbnail());

        $license = new License();
        $license->setUrl("http://creativecommons.org/licenses/by-nc-nd/3.0");
        $license->setName('Attribution-Noncommercial-No Derivative');
        $license->setId(3);
        $this->assertEquals($license, $firstPhoto->getLicense());
    }

    public function testGetAlbumReturnsAlbumDetailsWithPhotoList() {
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $this->restClient->expects($this->once())
            ->method('setEndpoint');
        $this->picasaWeb->setRestClient($this->restClient);

        /** @var $album Album */
        $album = $this->picasaWeb->getAlbum(
            array(
                'albumId' => self::ANY_ALBUM_ID,
                'thumbnail' => array(
                    'size' => self::ANY_INT,
                    'crop' => true
                )
            )
        );

        $this->assertNotNull($album);
        $this->assertType('Album', $album);
        $this->assertNonEmptyArray($album->getPhotos());

        $this->assertEquals('Andrea & I', $album->getTitle());
    }

    public function testProperKeyUsedWhenThumbsShouldBeCropped()
    {
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
                . self::ANY_ALBUM_ID
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . self::ANY_INT . PicasaService::CROPPED_KEY
            );

        $this->picasaWeb->setRestClient($this->restClient);

        $this->picasaWeb->getPhotos(
            array(
                'albumId' => self::ANY_ALBUM_ID,
                'thumbnail' => array(
                    'size' => self::ANY_INT,
                    'crop' => true
                )
            )
        );
    }

    public function testProperKeyUsedWhenThumbsShouldNotBeCropped()
    {
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
                . self::ANY_ALBUM_ID
                . '?'
                . PicasaService::THUMBSIZE_QUERY_PARAM . '=' . self::ANY_INT . PicasaService::UNCROPPED_KEY
            );

        $this->picasaWeb->setRestClient($this->restClient);
        $this->picasaWeb->setServiceUser(self::SOME_USER_ID);

        $this->picasaWeb->getPhotos(
            array(
                'albumId' => self::ANY_ALBUM_ID,
                'thumbnail' => array(
                    'size' => self::ANY_INT,
                    'crop' => false
                )
            )
        );
    }

    private function assertNonEmptyArray($albums)
    {
        $this->assertNotNull($albums);
        $this->assertTrue(is_array($albums));
        $this->assertFalse(empty($albums));
    }
}
