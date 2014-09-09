<?php

namespace travi\framework\photos;

use travi\framework\exception\ServiceCallFailedException;
use travi\framework\http\RestClient;

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
        $album = new Album();
        $this->setEndpoint($options);

        $this->restClient->execute();

        if (200 !== $this->restClient->getStatusCode()) {
            throw new ServiceCallFailedException();
        }

        $responseBody = $this->restClient->getResponseBody();
        $album->setPhotos($this->createPhotoListFrom($responseBody, $options));

        try {
            $responseXml = new \SimpleXMLElement($responseBody);
        } catch (\Exception $e) {
            throw new ServiceCallFailedException();
        }
        $namespaces = $responseXml->getNamespaces(true);

        $album->setTitle((string) $responseXml->title);
        $ns_gphoto = $responseXml->children($namespaces['gphoto']);
        $album->setId((string) $ns_gphoto->id);
        $album->setTotalPhotoCount((int) $ns_gphoto->numphotos);

        $album->setThumbnail($this->setAlbumThumbnailDetails($responseXml));

        return $album;
    }

    public function getPhotos($options)
    {
        $this->setEndpoint($options);

        $this->restClient->execute();

        if (200 !== $this->restClient->getStatusCode()) {
            throw new ServiceCallFailedException();
        }

        $responseBody = $this->restClient->getResponseBody();

        return $this->createPhotoListFrom($responseBody, $options);
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


    private function createPhotoListFrom($responseBody, $options)
    {
        $mediaList = array();

        try {
            $xml = new \SimpleXMLElement($responseBody);
        } catch (\Exception $e) {
            throw new ServiceCallFailedException();
        }

        $namespaces = $xml->getNamespaces(true);

        foreach ($xml->entry as $entry) {
            $ns_gphoto = $entry->children($namespaces['gphoto']);

            $license_attr = $ns_gphoto->license->attributes();

            $originalUrl = (string) $entry->content['src'];

            if ($this->isVideo($entry, $namespaces)) {
                $media = new Video();
                $this->setVideoVersions($media, $entry, $namespaces);
            } else {
                $media = new Photo();
                $media->setOriginal($originalUrl);
            }

            if (isset($options['preview'])) {
                $media->setPreview($this->defineImageWidth($originalUrl, $options['preview']['width']));
            }

            $media->setThumbnail($this->setThumbDetails($entry));
            $media->setCaption((string) $entry->summary[0]);

            $license = new License();
            $license->setId((int) $license_attr['id']);
            $license->setName((string) $license_attr['name']);
            $license->setUrl((string) $license_attr['url']);
            $media->setLicense($license);

            array_push($mediaList, $media);
        }
        return $mediaList;
    }

    private function defineImageWidth($originalUrl, $width)
    {
        $urlParts = explode('/', $originalUrl);

        foreach ($urlParts as $key => $part) {
            if (preg_match('/^s[0-9]+/', $part)) {
                unset($urlParts[$key]);
            }
        }

        /*
         * should add configuration for:
         *      cropped to square (add -c to crop)
         *      also maybe an option to define height (h)
         *          or maxsize (s) (height or width, whichever is higher)
         *          instead of just width
         */
        array_splice($urlParts, -1, 0, self::MAX_WIDTH_KEY . $width);

        return implode('/', $urlParts);
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

    private function setAlbumThumbnailDetails($responseXml)
    {
        $thumbnail = new Thumbnail();
        $thumbnail->setUrl((string) $responseXml->icon);
        return $thumbnail;
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

    public function setServiceUser($user)
    {
        $this->googleUser = $user;
    }

    public function getCropThumbnailKey($cropThumb)
    {
        if ($cropThumb === true) {
            return self::CROPPED_KEY;
        } else {
            return self::UNCROPPED_KEY;
        }
    }

    private function isVideo($entry, $namespaces)
    {
        $ns_media = $entry->children($namespaces['media']);

        $versions = $ns_media->group->content;

        foreach ($versions as $version) {
            $attributes = $version->attributes();
            if (self::VIDEO_MEDIUM === (string) $attributes->medium) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $video Video
     * @param $entry
     * @param $namespaces
     */
    private function setVideoVersions($video, $entry, $namespaces)
    {
        $ns_media = $entry->children($namespaces['media']);

        $versions = $ns_media->group->content;

        foreach ($versions as $version) {
            $attributes = $version->attributes();
            if (self::VIDEO_MEDIUM === (string) $attributes->medium
                && self::MPEG_VIDEO_TYPE === (string) $attributes->type
            ) {
                $width  = (int) $attributes->width;
                $height = (int) $attributes->height;

                $video->setWidth($width);
                $video->setHeight($height);

                if (self::MOBILE_SIZE === $width || self::MOBILE_SIZE === $height) {
                    $video->setMobile((string) $attributes->url);
                }

                if (self::STANDARD_SIZE === $width || self::STANDARD_SIZE === $height) {
                    $video->setStandard((string) $attributes->url);
                }

                if (self::HIGH_DEF_SIZE === $width || self::HIGH_DEF_SIZE === $height) {
                    $video->setHighDef((string) $attributes->url);
                }
            }
        }
    }
}
