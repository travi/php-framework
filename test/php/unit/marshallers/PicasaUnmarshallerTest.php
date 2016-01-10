<?php

use travi\framework\marshallers\PicasaUnmarshaller;
use travi\framework\photos\Album;
use travi\framework\photos\Media;
use travi\framework\photos\Photo;
use travi\framework\photos\Video;

class PicasaUnmarshallerTest extends PHPUnit_Framework_TestCase
{
    /** @var  PicasaUnmarshaller */
    private $picasaUnmarshaller;

    private $albumResponseFromRestClient;
    private $albumListResponseFromRestClient;

    public function setUp()
    {
        $this->picasaUnmarshaller = new PicasaUnmarshaller();

        $this->albumListResponseFromRestClient = file_get_contents(__DIR__ . '/../photos/picasaAlbumsExample.xml');
        $this->albumResponseFromRestClient = file_get_contents(__DIR__ . '/../photos/picasaExample.xml');
    }

    public function testThatAlbumXmlUnmarshalledToAlbum()
    {
        $album = $this->picasaUnmarshaller->toAlbum(
            $this->albumResponseFromRestClient,
            array()
        );

        $this->assertEquals('Andrea & I', $album->getTitle());
        $this->assertEquals(5504, $album->getId());
        $this->assertEquals(
            'https://lh4.googleusercontent.com/-Ii1cVigA49I/TGSYUwDNbPE/AAAAAAAAGV4/EZ6QKMtAhIU/s160-c/AndreaI.jpg',
            $album->getThumbnail()->getUrl()
        );

        $this->assertPhotoListBuiltCorrectly($album->getPhotos(), false);

        $this->assertEquals(3, $album->getTotalPhotoCount());
    }

    public function testThatMediaListXmlUnmarshalledToMediaList()
    {
        $mediaList = $this->picasaUnmarshaller->toMediaList(
            $this->albumResponseFromRestClient,
            array(
                'preview' => array(
                    'width' => 600
                )
            )
        );

        $this->assertPhotoListBuiltCorrectly($mediaList, true);
    }

    public function testThatAlbumListXmlUnmarshalledToAlbumList()
    {
        $albums = $this->picasaUnmarshaller->toAlbumList($this->albumListResponseFromRestClient);

        /** @var Album $firstAlbum */
        $firstAlbum = $albums[0];
        $this->assertEquals(5575, $firstAlbum->getId());
        $this->assertEquals("Steamboat 2011", $firstAlbum->getTitle());
        $this->assertEquals(
            "https://picasaweb.google.com/107098889836094611170/Steamboat2011",
            $firstAlbum->getUrl()
        );
        $this->assertEquals(
            "https://lh5.googleusercontent.com/-ePrl_rE_oWs/TV9JLtXszbE/AAAAAAAAHEY/"
            . "JAYLTmv0rqI/s160-c/Steamboat2011.jpg",
            $firstAlbum->getThumbnail()->getUrl()
        );
    }

    private function assertNonEmptyArray($list)
    {
        $this->assertTrue(is_array($list), 'Not an Array');
        $this->assertFalse(empty($list), 'Expected empty array not to be empty');
    }

    /**
     * @param $mediaList Media[]
     * @param $previewDefined
     */
    private function assertPhotoListBuiltCorrectly($mediaList, $previewDefined)
    {
        $this->assertNonEmptyArray($mediaList);
        $this->assertEquals(4, count($mediaList));

        $this->assertPhotoDetailsDefinedProperly($mediaList[0], $previewDefined);
        $this->assertVideoDetailsDefinedProperly($mediaList[3], $previewDefined);
    }

    /**
     * @param $photo Photo
     * @param $previewDefined
     */
    private function assertPhotoDetailsDefinedProperly($photo, $previewDefined)
    {
        $this->assertEquals(
            "https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/s1600/IMG_1245.JPG",
            $photo->getOriginal()
        );
        $this->assertProperDetailsDefinedFor(
            $photo,
            "This is such a great pic, isn't it?",
            $previewDefined,
            'https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/w600/IMG_1245.JPG',
            'https://lh4.googleusercontent.com/-ODK_V5lONjo/TGSYV24YDWI/AAAAAAAAF7I/x08IKQbCNjw/s270-c/IMG_1245.JPG'
        );
    }

    /**
     * @param $video Video
     * @param $previewDefined
     */
    private function assertVideoDetailsDefinedProperly($video, $previewDefined)
    {
        $this->assertProperDetailsDefinedFor(
            $video,
            'Tug of war while I try to wipe wax from the truck',
            $previewDefined,
            'https://lh6.googleusercontent.com/-t6-EYd0XUmQ/T-c1bx2HuvI/AAAAAAAAJpg/N1bST9ObDQE/w600/VID_20120610_200029.m4v.jpg',
            'https://lh6.googleusercontent.com/-t6-EYd0XUmQ/T-c1bx2HuvI/AAAAAAAAJpg/N1bST9ObDQE/s75-c/VID_20120610_200029.m4v.jpg'
        );

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

        $this->assertEquals(608, $video->getWidth());
        $this->assertEquals(1080, $video->getHeight());
    }

    /**
     * @param $media Media
     * @param $caption
     * @param $previewDefined
     * @param $preview
     * @param $thumbnail
     */
    private function assertProperDetailsDefinedFor($media, $caption, $previewDefined, $preview, $thumbnail)
    {
        $this->assertEquals($caption, $media->getCaption());
        $this->assertEquals($thumbnail, $media->getThumbnail()->getUrl());

        if (!empty($previewDefined)) {
            $this->assertEquals($preview, $media->getPreview());
        } else {
            $this->assertNull($media->getPreview());
        }

        $this->assertEquals(3, $media->getLicense()->getId());
    }
}
