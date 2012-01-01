<?php
require_once dirname(__FILE__) . '/../http/RestClient.php';
require_once 'Album.php';

class PicasaService
{
    const PICASA_URI = 'https://picasaweb.google.com/data/feed/api/user/';
    /* 1-indexed */
    const OFFSET_QUERY_PARAM = 'start-index';
    const COUNT_QUERY_PARAM = 'max-results';
    const THUMBSIZE_QUERY_PARAM = 'thumbsize';
    const UNCROPPED_KEY = 'u';
    const CROPPED_KEY = 'c';

    /** @var RestClient */
    private $restClient;
    private $googleUser;
    private $album;

    /**
     * @return array Album
     */
    public function getAlbums()
    {

        $this->restClient->setEndpoint(
            self::PICASA_URI
            . $this->googleUser
        );
        $this->restClient->execute();
        $responseBody = $this->restClient->getResponseBody();

        return $this->createAlbumListFrom($responseBody);
    }

    public function getAlbum($options)
    {
        $album = new Album();
        $this->setEndpoint($options);

        $this->restClient->execute();
        $responseBody = $this->restClient->getResponseBody();
        $album->setPhotos($this->createPhotoListFrom($responseBody, $options));

        $responseXml = new SimpleXMLElement($responseBody);
        $namespaces = $responseXml->getNamespaces(true);

        $album->setTitle((string) $responseXml->title);
        $album->setTotalPhotoCount((int) $responseXml->children($namespaces['gphoto'])->numphotos);

        return $album;
    }

    public function getPhotos($options)
    {
        $this->setEndpoint($options);

        $this->restClient->execute();
        $responseBody = $this->restClient->getResponseBody();

        return $this->createPhotoListFrom($responseBody, $options);
    }

    private function setEndpoint($options = array())
    {
        $endPoint = self::PICASA_URI
                . $this->googleUser
                . '/albumid/'
                . $options['albumId']
                . '?'
                . self::THUMBSIZE_QUERY_PARAM . '=' . $options['thumbnail']['size']
                . $this->getCropThumbnailKey($options['thumbnail']['crop']);

        if ($options['offset']) {
            $endPoint .= '&' . self::OFFSET_QUERY_PARAM . '=' . (intval($options['offset']) + 1);
        }

        if ($options['count']) {
            $endPoint .= '&' . self::COUNT_QUERY_PARAM . '=' . $options['count'];
        }

        $this->restClient->setEndpoint($endPoint);
    }

    private function createAlbumListFrom($responseBody)
    {
        $xml = new SimpleXMLElement($responseBody);
        $namespaces = $xml->getNamespaces(true);

        $albums = array();
        foreach ($xml->entry as $entry) {
            $ns_gphoto = $entry->children($namespaces['gphoto']);
            $ns_media = $entry->children($namespaces['media']);
            $link_attr = $entry->link[1]->attributes();

            $thumb_attr = $ns_media->group->thumbnail[0]->attributes();

            /** @var $album Album */
            $album = new Album();
            $album->setid((int)$ns_gphoto->id);
            $album->setTitle((string) $entry->title);
            $album->setUrl((string) $link_attr['href']);

            $thumbnail = $this->setThumbDetails($thumb_attr);
            $album->setThumbnail($thumbnail);

            array_push($albums, $album);
        }

        return $albums;
    }


    private function createPhotoListFrom($responseBody, $options)
    {
        $xml = new SimpleXMLElement($responseBody);
        $namespaces = $xml->getNamespaces(true);

        $photos = array();
        foreach ($xml->entry as $entry) {
            $ns_gphoto = $entry->children($namespaces['gphoto']);
            $ns_media = $entry->children($namespaces['media']);

            $thumb_attr = $ns_media->group->thumbnail[0]->attributes();
            $license_attr = $ns_gphoto->license->attributes();

            /** @var $photo Photo */
            $photo = new Photo();
            $originalUrl = (string)$entry->content['src'];
            $photo->setOriginal($originalUrl);

            if ($options['preview']) {
                $photo->setPreview($this->defineImageSize($originalUrl, $options['preview']['width']));
            }

            $thumbnail = $this->setThumbDetails($thumb_attr);
            $photo->setThumbnail($thumbnail);

            $license = new License();
            $license->setId((int) $license_attr['id']);
            $license->setName((string) $license_attr['name']);
            $license->setUrl((string) $license_attr['url']);
            $photo->setLicense($license);

            array_push($photos, $photo);
        }
        return $photos;
    }

    private function defineImageSize($originalUrl, $size)
    {
        $urlParts = explode('/', $originalUrl);

        /*
         * should add configuration for:
         *      cropped to square (add -c to crop)
         */
        array_splice($urlParts, -1, 0, 's' . $size);

        return implode('/', $urlParts);
    }

    private function setThumbDetails($thumb_attr)
    {
        $thumbnail = new Thumbnail();
        $thumbnail->setUrl((string)$thumb_attr['url']);
        return $thumbnail;
    }

    /**
     * @PdInject new:RestClient
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
}
