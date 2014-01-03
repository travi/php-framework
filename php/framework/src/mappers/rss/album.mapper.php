<?php

use travi\framework\http\Request;
use travi\framework\photos\Album;
use travi\framework\photos\Photo;

class AlbumRssMapper
{
    /** @var Request */
    private $request;

    /**
     * @param $album Album
     * @return FeedItem
     */
    public function mapToFeedItem($album)
    {
        $feedItem = new FeedItem();

        $feedItem->title = $album->getTitle();
        $feedItem->category = $album->getCategory();
        $feedItem->link = 'http://' . $this->request->getHost() . '/gallery/?album=' . $album->getId();

        $description = '<ul>';
        /** @var $photo Photo */
        foreach ($album->getPhotos() as $photo) {
            $description .= '<li><img src="' . $photo->getThumbnail()->getUrl() . '"/></li>';
        }
        $description .= '</ul>';

        $feedItem->description = $description;

        return $feedItem;
    }

    public function mapListToFeedItems($albums)
    {
        $feedItems = array();

        foreach ($albums as $album) {
            array_push($feedItems, $this->mapToFeedItem($album));
        }

        return $feedItems;
    }

    /**
     * @param $request
     * @PdInject request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }
}