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

    /** @var  PicasaUnmarshaller */
    private $picasaUnmarshaller;

    /**
     * @throws ServiceCallFailedException
     * @throws \Exception
     * @return array Album
     */
    public function getAlbums()
    {
        return $this->picasaUnmarshaller->toAlbumList($this->getFromPicasa(self::PICASA_URI . $this->googleUser));
    }

    public function getAlbum($options)
    {
        return $this->picasaUnmarshaller->toAlbum(
            $this->getFromPicasa($this->buildEndpoint($options)),
            $options
        );
    }

    public function getPhotos($options)
    {
        return $this->picasaUnmarshaller->toMediaList(
            $this->getFromPicasa($this->buildEndpoint($options)),
            $options
        );
    }

    private function buildEndpoint($options)
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

        return $endPoint;
    }

    private function getCropThumbnailKey($cropThumb)
    {
        if ($cropThumb === true) {
            return self::CROPPED_KEY;
        } else {
            return self::UNCROPPED_KEY;
        }
    }

    /**
     * @param $endpoint
     * @return null
     * @throws ServiceCallFailedException
     * @throws \Exception
     */
    private function getFromPicasa($endpoint)
    {
        $this->restClient->setEndpoint($endpoint);
        $this->restClient->execute();

        if (200 !== $this->restClient->getStatusCode()) {
            throw new ServiceCallFailedException();
        }

        return $this->restClient->getResponseBody();
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
