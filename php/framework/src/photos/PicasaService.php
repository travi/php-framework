<?php
require_once dirname(__FILE__) . '/../http/RestClient.php';
require_once 'Album.php';

class PicasaService
{
    const PICASA_URI = 'https://picasaweb.google.com/data/feed/api/user/';
    const THUMBSIZE_QUERY_PARAM = 'thumbsize';
    const UNCROPPED_KEY = 'u';
    const CROPPED_KEY = 'c';

    /** @var RestClient */
    private $restClient;
    private $googleUser;
    private $album;
    private $thumbnailCropKey;

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

    public function getAlbum($albumId, $thumbSize, $cropThumb = '')
    {
        $this->setAlbum($albumId);
        $this->setCropThumbnail($cropThumb);

        $album = new Album();
        $this->setEndpoint($thumbSize);
        $this->restClient->execute();
        $responseBody = $this->restClient->getResponseBody();
        $album->setPhotos($this->createPhotoListFrom($responseBody));

        $responseXml = new SimpleXMLElement($responseBody);

        $album->setTitle((string) $responseXml->title);

        return $album;
    }

    public function getPhotos($thumbSize, $cropThumb = '')
    {
        $this->setCropThumbnail($cropThumb);

        $this->setEndpoint($thumbSize);
        $this->restClient->execute();
        $responseBody = $this->restClient->getResponseBody();

        return $this->createPhotoListFrom($responseBody);
    }

    public function setEndpoint($thumbSize)
    {
        $this->restClient->setEndpoint(
            self::PICASA_URI
                . $this->googleUser
                . '/albumid/'
                . $this->album
                . '?'
                . self::THUMBSIZE_QUERY_PARAM . '=' . $thumbSize . $this->thumbnailCropKey
        );
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


    private function createPhotoListFrom($responseBody)
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

            $photo->setPreview($this->adjustPreviewSize($originalUrl));

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

    private function adjustPreviewSize($originalUrl)
    {
        $urlParts = explode('/', $originalUrl);

        /*
         * some configurations this should take:
         *      size (600 here)
         *      cropped to square (add -c to crop)
         */
        array_splice($urlParts, -1, 0, 's600');

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

    public function setAlbum($album)
    {
        $this->album = $album;
    }

    public function setCropThumbnail($cropThumb)
    {
        if ($cropThumb === true) {
            $this->thumbnailCropKey = self::CROPPED_KEY;
        } elseif ($cropThumb === false) {
            $this->thumbnailCropKey = self::UNCROPPED_KEY;
        }
    }
}
