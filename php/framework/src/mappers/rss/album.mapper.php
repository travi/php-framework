<?php

class AlbumRssMapper
{

    /**
     * @param $album Album
     * @return FeedItem
     */
    public function mapToFeedItem($album)
    {
        $feedItem = new FeedItem();

        $feedItem->title = $album->getTitle();
        $feedItem->category = $album->getCategory();

        $description = '<ul>';
        /** @var $photo Photo */
        foreach ($album->getPhotos() as $photo) {
            $description .= '<li>' . $photo->getThumbnail()->getUrl() . '</li>';
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
}