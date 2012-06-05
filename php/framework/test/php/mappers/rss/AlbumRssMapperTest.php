<?php
require_once dirname(__FILE__).'/../../../../../../php/thirdparty/FeedCreator/include/feedcreator.class.php';
require_once dirname(__FILE__).'/../../../../src/mappers/rss/album.mapper.php';

class AlbumRssMapperTest extends PHPUnit_Framework_TestCase
{
    const SOME_TITLE = 'some title';
    const SOME_CATEGORY = 'some category';
    const URL_1 = 'url 1';
    const URL_2 = 'url 2';

    /** @var AlbumRssMapper */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new AlbumRssMapper();
    }

    public function testAlbumMappedToFeedItem()
    {
        $thumb1 = new Thumbnail();
        $thumb1->setUrl(self::URL_1);
        $thumb2 = new Thumbnail();
        $thumb2->setUrl(self::URL_2);

        $photo1 = new Photo();
        $photo1->setThumbnail($thumb1);
        $photo2 = new Photo();
        $photo2->setThumbnail($thumb2);

        $album = new Album();
        $album->setTitle(self::SOME_TITLE);
        $album->setCategory(self::SOME_CATEGORY);
        $album->setPhotos(array($photo1, $photo2));

        /** @var $feedItem FeedItem */
        $feedItem = $this->mapper->mapToFeedItem($album);

        $this->assertType('FeedItem', $feedItem);
        $this->assertEquals(self::SOME_TITLE, $feedItem->title);
        $this->assertEquals(self::SOME_CATEGORY, $feedItem->category);
        $this->assertEquals(
            '<ul><li>' . self::URL_1 . '</li><li>' . self::URL_2 . '</li></ul>',
            $feedItem->description
        );
    }

    public function testMapListToFeedItems()
    {
        $albums = array(new Album(), new Album());

        $feedItems = $this->mapper->mapListToFeedItems($albums);

        $this->assertEquals(sizeof($albums), sizeof($feedItems));
    }
}