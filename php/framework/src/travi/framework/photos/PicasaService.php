<?php

namespace travi\framework\photos;

use travi\framework\exception\ServiceCallFailedException;
use travi\framework\http\RestClient;
use travi\framework\marshallers\PicasaUnmarshaller;

class PicasaService
{
    const PICASA_URI = 'http://picasaweb.google.com/data/feed/api/user/';
    /* 1-indexed */
    const OFFSET_QUERY_PARAM    = 'start-index';
    const COUNT_QUERY_PARAM     = 'max-results';
    const THUMBSIZE_QUERY_PARAM = 'thumbsize';
    const UNCROPPED_KEY         = 'u';
    const CROPPED_KEY           = 'c';

    const MAX_WIDTH_KEY   = 'w';
    const MAX_SIZE_KEY    = 's';
    const MAX_HEIGHT_KEY  = 'h';
    const MPEG_VIDEO_TYPE = 'video/mpeg4';
    const MOBILE_SIZE     = 360;
    const VIDEO_MEDIUM    = 'video';
    const STANDARD_SIZE   = 720;
    const HIGH_DEF_SIZE   = 1080;

    /** @var RestClient */
    private $restClient;
    private $googleUser;
    private $album;

    /** @var  PicasaUnmarshaller */
    private $picasaUnmarshaller;

    /**
     * @throws ServiceCallFailedException
     * @throws \Exception
     * @return array Album
     */
    public function getAlbums()
    {
        $this->restClient->setEndpoint(
            self::PICASA_URI
            . $this->googleUser
        );
        $this->restClient->execute();

        if (200 !== $this->restClient->getStatusCode()) {
            throw new ServiceCallFailedException();
        }

        $responseBody = $this->restClient->getResponseBody();

        return $this->createAlbumListFrom($responseBody);
    }

    public function getAlbum($options)
    {
        $this->setEndpoint($options);

        $this->restClient->execute();

        if (200 !== $this->restClient->getStatusCode()) {
            throw new ServiceCallFailedException();
        }

        $responseBody = $this->restClient->getResponseBody();

        return $this->picasaUnmarshaller->toAlbum($responseBody, $options);
    }

    public function getPhotos($options)
    {
        $this->setEndpoint($options);

        $this->restClient->execute();

        if (200 !== $this->restClient->getStatusCode()) {
            throw new ServiceCallFailedException();
        }

        $responseBody = $this->restClient->getResponseBody();

        return $this->picasaUnmarshaller->toMediaList($responseBody, $options);
    }

    private function setEndpoint($options)
    {
        $endPoint = self::PICASA_URI
                . $this->googleUser
                . '/albumid/'
                . $options['albumId']
                . '?'
                . self::THUMBSIZE_QUERY_PARAM . '=' . $options['thumbnail']['size']
                . $this->getCropThumbnailKey($options['thumbnail']['crop'])
                . '&imgmax=1600';

        if (isset($options['offset'])) {
            $endPoint .= '&' . self::OFFSET_QUERY_PARAM . '=' . (intval($options['offset']) + 1);
        }

        if (isset($options['count'])) {
            $endPoint .= '&' . self::COUNT_QUERY_PARAM . '=' . $options['count'];
        }

        $this->restClient->setEndpoint($endPoint);
    }

    private function createAlbumListFrom($responseBody)
    {
        try {
            $xml = new \SimpleXMLElement($responseBody);
        } catch (\Exception $e) {
            throw new ServiceCallFailedException();
        }
        $namespaces = $xml->getNamespaces(true);

        $albums = array();
        foreach ($xml->entry as $entry) {
            $ns_gphoto = $entry->children($namespaces['gphoto']);
            $link_attr = $entry->link[1]->attributes();

            /** @var $album Album */
            $album = new Album();
            $album->setId((string) $ns_gphoto->id);
            $album->setTitle((string) $entry->title);
            $album->setUrl((string) $link_attr['href']);

            $album->setThumbnail($this->setThumbDetails($entry));

            array_push($albums, $album);
        }

        return $albums;
    }

    private function setThumbDetails($entry)
    {
        $entryNamespaces = $entry->getNamespaces(true);
        $ns_media        = $entry->children($entryNamespaces['media']);
        $thumb_attr      = $ns_media->group->thumbnail[0]->attributes();

        $thumbnail = new Thumbnail();
        $thumbnail->setUrl((string) $thumb_attr['url']);
        return $thumbnail;
    }

    public function getCropThumbnailKey($cropThumb)
    {
        if ($cropThumb === true) {
            return self::CROPPED_KEY;
        } else {
            return self::UNCROPPED_KEY;
        }
    }

    public function setServiceUser($user)
    {
        $this->googleUser = $user;
    }

    /**
     * @PdInject new:travi\framework\http\RestClient
     * @param $restClient
     * @return void
     */
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @PdInject new:travi\framework\marshallers\PicasaUnmarshaller
     * @param $picasaUnmarshaller PicasaUnmarshaller
     */
    public function setUnmarshaller($picasaUnmarshaller)
    {
        $this->picasaUnmarshaller = $picasaUnmarshaller;
    }
}
