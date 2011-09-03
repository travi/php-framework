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

    public function getPhotos($thumbSize, $cropThumb = '')
    {
        $this->setCropThumbnail($cropThumb);

        $this->restClient->setEndpoint(
            self::PICASA_URI
            . $this->googleUser
            . '/albumid/'
            . $this->album
            . '?'
            . self::THUMBSIZE_QUERY_PARAM . '=' . $thumbSize . $this->thumbnailCropKey
        );
        $this->restClient->execute();
        $responseBody = $this->restClient->getResponseBody();

        return $this->createPhotoListFrom($responseBody);
    }

    private function createAlbumListFrom($responseBody)
    {
        $xml = new SimpleXMLElement($responseBody);
        $namespaces = $xml->getNamespaces(true);

        $albums = array();
        foreach ($xml->entry as $entry) {
            $ns_media = $entry->children($namespaces['media']);
            $link_attr = $entry->link[1]->attributes();

            $thumb_attr = $ns_media->group->thumbnail[0]->attributes();

            /** @var $album Album */
            $album = new Album();
            $album->setTitle((string) $entry->title);
            $album->setUrl((string) $link_attr['href']);
            $album->setThumbnail((string) $thumb_attr['url']);

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
            $photo->setOriginal((string)$entry->content['src']);
            $photo->setThumbnail((string) $thumb_attr['url']);

            $license = new License();
            $license->setId((int) $license_attr['id']);
            $license->setName((string) $license_attr['name']);
            $license->setUrl((string) $license_attr['url']);
            $photo->setLicense($license);

            array_push($photos, $photo);
        }
        return $photos;
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
