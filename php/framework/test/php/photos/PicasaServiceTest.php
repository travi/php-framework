<?php

use Travi\framework\http\RestClient,
    Travi\framework\photos\PicasaService,
    Travi\framework\photos\Album,
    Travi\framework\photos\Thumbnail,
    Travi\framework\photos\Photo,
    Travi\framework\photos\Video,
    Travi\framework\photos\License;

class PicasaServiceTest extends PHPUnit_Framework_TestCase
{
    private $responseFromRestClient;

    const SOME_USER_ID = 'someUserId';
    const ANY_INT = 42;

    private $restClient;
    const ANY_ALBUM_ID = 'someAlbumId';
    const SOME_SIZE = 14;

    /** @var PicasaService */
    private $picasaWeb;

    protected function setUp()
    {
        $this->picasaWeb = new PicasaService();
        $this->restClient = $this->getMock('Travi\\framework\\http\\RestClient');

        $this->picasaWeb->setServiceUser(self::SOME_USER_ID);
        $this->picasaWeb->setRestClient($this->restClient);

        $this->responseFromRestClient = file_get_contents(dirname(__FILE__).'/picasaExample.xml');

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
        $this->assertInstanceOf('Travi\\framework\\photos\\Album', $firstAlbum);
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
        $this->assertEquals(4, count($photos));

        $this->assertInstanceOf('Travi\\framework\\photos\\Photo', $firstPhoto);
        $this->assertEquals(
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/" .
            "AAAAAAAAF7I/x08IKQbCNjw/s1600/IMG_1245.JPG",
            $firstPhoto->getOriginal()
        );
        $this->assertCommonDetailsSetCorrectly(
            $firstPhoto,
            array(
                'preview' => 'https://lh4.googleusercontent.com/-ODK_V5lONjo/' .
                    'TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/w600/IMG_1245.JPG',
                'thumbnail' => 'https://lh4.googleusercontent.com/-ODK_V5lONjo/' .
                    'TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/s270-c/IMG_1245.JPG',
                'caption' => "This is such a great pic, isn't it?"
            )
        );
    }

    public function testGetAlbumReturnsAlbumDetailsWithPhotoList()
    {
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));
        $this->restClient->expects($this->once())
            ->method('setEndpoint');

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
        $this->assertInstanceOf('Travi\\framework\\photos\\Album', $album);
        $this->assertNonEmptyArray($album->getPhotos());

        $this->assertEquals('Andrea & I', $album->getTitle());
        $this->assertEquals(5504, $album->getId());
        $this->assertEquals(
            'https://lh4.googleusercontent.com/-Ii1cVigA49I/TGSYUwDNbPE' .
            '/AAAAAAAAGV4/EZ6QKMtAhIU/s160-c/AndreaI.jpg',
            $album->getThumbnail()->getUrl()
        );
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

    public function testTotalPhotosCountSetOnAlbumObject()
    {
        $this->restClient->expects($this->once())
            ->method('execute');
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));

        $this->picasaWeb->setRestClient($this->restClient);

        /** @var $album Album */
        $album = $this->picasaWeb->getAlbum(
            array(
                'albumId' => self::ANY_ALBUM_ID,
                'thumbnail' => array(
                    'size' => self::ANY_INT,
                    'crop' => false
                ),
                'count' => self::SOME_SIZE
            )
        );

        $this->assertEquals(3, $album->getTotalPhotoCount());
    }

    public function testVideoObjectUsedForVideoEntry()
    {
        $this->restClient->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue($this->responseFromRestClient));

        $mediaList = $this->picasaWeb->getPhotos(
            array(
                'preview' => array(
                    'width' => 600
                )
            )
        );

        /** @var $video Video */
        $video = $mediaList[3];
        $this->assertInstanceOf('Travi\\framework\\photos\\Video', $video);

        $this->assertEquals(
            'http://redirector.googlevideo.com/videoplayback?id=6b97ab834cad4bf8&itag=18&source=picasa&' .
            'cmo=sensitive_content%3Dyes&ip=0.0.0.0&ipbits=0&expire=1343752561&sparams=id,itag,' .
            'source,ip,ipbits,expire&signature=92F4A8A9CF326902E30CF6B669F3DD715CD095E3.' .
            'CF2450B7783E34EBEC77D75753CDB2A8AEF62531&key=lh1',
            $video->getMobile()
        );

        $this->assertEquals(
            'http://redirector.googlevideo.com/videoplayback?id=6b97ab834cad4bf8&itag=22&source=picasa' .
            '&cmo=sensitive_content%3Dyes&ip=0.0.0.0&ipbits=0&expire=1343752561&sparams=id,itag,source,ip,' .
            'ipbits,expire&signature=B3929D86A776C91DB28BED29E20827C3674884A.' .
            '98E20D0F8AA7DF52270C160554221BD711A21BD6&key=lh1',
            $video->getStandard()
        );

        $this->assertEquals(
            'http://redirector.googlevideo.com/videoplayback?id=6b97ab834cad4bf8&itag=37&source=picasa' .
            '&cmo=sensitive_content%3Dyes&ip=0.0.0.0&ipbits=0&expire=1343752561&sparams=id,itag,source,' .
            'ip,ipbits,expire&signature=98A3F79B5F29F8F3988BCB03AFE999821C211F7E.' .
            '460E76C2EFBBE65A04226A1CB0EA8175976F9835&key=lh1',
            $video->getHighDef()
        );

        $this->assertCommonDetailsSetCorrectly(
            $video,
            array(
                'preview' => 'https://lh6.googleusercontent.com/-t6-EYd0XUmQ/T-c1bx2HuvI/' .
                'AAAAAAAAJpg/N1bST9ObDQE/w600/VID_20120610_200029.m4v.jpg',
                'thumbnail' => 'https://lh6.googleusercontent.com/-t6-EYd0XUmQ/T-c1bx2HuvI/' .
                'AAAAAAAAJpg/N1bST9ObDQE/s75-c/VID_20120610_200029.m4v.jpg',
                'caption' => 'Tug of war while I try to wipe wax from the truck'
            )
        );

        $this->assertEquals(608, $video->getWidth());
        $this->assertEquals(1080, $video->getHeight());
    }

    //probably a better way to handle this but better than how it is now
    public function testNullObjectReturnedWhenInvalidResponseWhenAlbumRequested()
    {
        $album = $this->picasaWeb->getAlbum();

        $this->assertNotNull($album);
        $this->assertEquals(new Album(), $album);
    }

    public function testEmptyListReturnedWhenInvalidResponseWhenPhotosWereRequested()
    {
        $mediaList = $this->picasaWeb->getPhotos();

        $this->assertNotNull($mediaList);
        $this->assertEquals(array(), $mediaList);
    }

    private function assertNonEmptyArray($albums)
    {
        $this->assertNotNull($albums);
        $this->assertTrue(is_array($albums));
        $this->assertFalse(empty($albums));
    }

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
