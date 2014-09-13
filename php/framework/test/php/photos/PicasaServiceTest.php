<?php

use travi\framework\http\RestClient,
    travi\framework\photos\PicasaService,
    travi\framework\photos\Album,
    travi\framework\photos\Thumbnail,
    travi\framework\photos\Photo,
    travi\framework\photos\Video,
    travi\framework\photos\License;
use travi\framework\marshallers\PicasaUnmarshaller;
use travi\framework\photos\Media;

class PicasaServiceTest extends PHPUnit_Framework_TestCase
{
    private $responseFromRestClient;

    const SOME_USER_ID = 'someUserId';
    const ANY_INT = 42;

    private $restClient;
    const ANY_ALBUM_ID = 'someAlbumId';
    const SOME_SIZE = 14;

    private $mediaList;

    private $defaultOptions = array(
        'albumId' => self::ANY_ALBUM_ID,
        'thumbnail' => array(
            'size' => self::ANY_INT,
            'crop' => true
        )
    );

    /** @var PicasaService */
    private $picasaWeb;

    /** @var  PicasaUnmarshaller */
    private $picasaUnmarshaller;

    protected function setUp()
    {
        $this->restClient = $this->getMock('travi\\framework\\http\\RestClient');
        $this->picasaUnmarshaller = $this->getMock('travi\\framework\\marshallers\\PicasaUnmarshaller');

        $this->picasaWeb = new PicasaService();

        $this->picasaWeb->setServiceUser(self::SOME_USER_ID);
        $this->picasaWeb->setRestClient($this->restClient);
        $this->picasaWeb->setUnmarshaller($this->picasaUnmarshaller);

        $this->responseFromRestClient = file_get_contents(dirname(__FILE__).'/picasaExample.xml');

        $this->mediaList = array(new Photo(), new Video());
    }

    public function testApiUriDefinedProperly()
    {
        $this->assertSame(PicasaService::PICASA_URI, "http://picasaweb.google.com/data/feed/api/user/");
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
            ->method('getStatusCode')
            ->will($this->returnValue(200));
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

        $albums = $this->picasaWeb->getAlbums();
        /** @var $firstAlbum Album */
        $firstAlbum = $albums[0];

        $this->assertNonEmptyArray($albums);
        $this->assertInstanceOf('travi\\framework\\photos\\Album', $firstAlbum);
        $this->assertEquals("Steamboat 2011", $firstAlbum->getTitle());
        $this->assertEquals(
            "https://picasaweb.google.com/107098889836094611170/Steamboat2011",
            $firstAlbum->getUrl()
        );
        $this->assertEquals(5575, $firstAlbum->getId());

        $thumbnail = new Thumbnail();
        $thumbnail->setUrl(
            "https://lh5.googleusercontent.com/-ePrl_rE_oWs/TV9JLtXszbE/AAAAAAAAHEY/"
            . "JAYLTmv0rqI/s160-c/Steamboat2011.jpg"
        );
        $this->assertEquals($thumbnail, $firstAlbum->getThumbnail());
    }

    public function testProperPhotoObjectsCreatedFromPicasaWebData()
    {
        $options = array(
            'albumId' => self::ANY_ALBUM_ID,
            'thumbnail' => array(
                'size' => self::ANY_INT,
                'crop' => false
            ),
            'preview' => array(
                'width' => 600
            )
        );

        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
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
                . '&imgmax=1600'
            );
        $this->picasaUnmarshaller->expects($this->once())
            ->method('toMediaList')
            ->with($this->responseFromRestClient, $options)
            ->will($this->returnValue($this->mediaList));

        $photos = $this->picasaWeb->getPhotos($options);

        $this->assertSame($this->mediaList, $photos);
    }

    public function testGetAlbumReturnsAlbumDetailsWithPhotoList()
    {
        $options = array(
            'albumId' => self::ANY_ALBUM_ID,
            'thumbnail' => array(
                'size' => self::ANY_INT,
                'crop' => true
            )
        );
        $expectedAlbum = new Album();

        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $this->restClient->expects($this->once())
            ->method('setEndpoint');
        $this->picasaUnmarshaller->expects($this->once())
            ->method('toAlbum')
            ->with($this->responseFromRestClient, $options)
            ->will($this->returnValue($expectedAlbum));


        /** @var $album Album */
        $album = $this->picasaWeb->getAlbum($options);

        $this->assertSame($expectedAlbum, $album);
    }

    public function testProperKeyUsedWhenThumbsShouldBeCropped()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
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
                . '&imgmax=1600'
            );

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
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
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
                . '&imgmax=1600'
            );

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

    public function testOffsetConfigPassedToPicasa()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
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
                . '&imgmax=1600'
                . '&' . PicasaService::OFFSET_QUERY_PARAM . '=' . (self::SOME_SIZE + 1)
            );

        $this->picasaWeb->getPhotos(
            array(
                'albumId' => self::ANY_ALBUM_ID,
                'thumbnail' => array(
                    'size' => self::ANY_INT,
                    'crop' => false
                ),
                'offset' => self::SOME_SIZE
            )
        );
    }

    public function testCountConfigPassedToPicasaAfterAdjustingForOneIndexing()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
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
                . '&imgmax=1600'
                . '&' . PicasaService::COUNT_QUERY_PARAM . '=' . self::SOME_SIZE
            );

        $this->picasaWeb->getPhotos(
            array(
                'albumId' => self::ANY_ALBUM_ID,
                'thumbnail' => array(
                    'size' => self::ANY_INT,
                    'crop' => false
                ),
                'count' => self::SOME_SIZE
            )
        );
    }
    
    /**
     * @expectedException travi\framework\exception\ServiceCallFailedException
     */
    public function testExceptionThrownWhenInvalidResponseFromWhenAlbumRequested()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(500));

        $this->picasaWeb->getAlbum($this->defaultOptions);
    }

    /**
     * @expectedException travi\framework\exception\ServiceCallFailedException
     */
    public function testExceptionThrownWhenInvalidResponseWhenPhotosWereRequested()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(500));

        $this->picasaWeb->getPhotos($this->defaultOptions);
    }

    /**
     * @expectedException travi\framework\exception\ServiceCallFailedException
     */
    public function testExceptionThrownWhenInvalidResponseWhenAlbumssWereRequested()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(500));

        $this->picasaWeb->getAlbums();
    }

    private function assertNonEmptyArray($albums)
    {
        $this->assertNotNull($albums);
        $this->assertTrue(is_array($albums));
        $this->assertFalse(empty($albums));
    }

    /**
     * @param $media Media
     * @param array $details
     */
    public function assertCommonDetailsSetCorrectly($media, $details = array())
    {
        $this->assertEquals(
            $details['preview'],
            $media->getPreview()
        );
        $thumbnail = new Thumbnail();
        $thumbnail->setUrl($details['thumbnail']);
        $this->assertEquals($thumbnail, $media->getThumbnail());
        $this->assertEquals($details['caption'], $media->getCaption());

        $license = new License();
        $license->setUrl("http://creativecommons.org/licenses/by-nc-nd/3.0");
        $license->setName('Attribution-Noncommercial-No Derivative');
        $license->setId(3);
        $this->assertEquals($license, $media->getLicense());
    }
}
