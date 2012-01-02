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

    const MAX_WIDTH_KEY = 'w';
    const MAX_SIZE_KEY = 's';
    const MAX_HEIGHT_KEY = 'h';

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
        $ns_gphoto = $responseXml->children($namespaces['gphoto']);
        $album->setid((int) $ns_gphoto->id);
        $album->setTotalPhotoCount((int) $ns_gphoto->numphotos);

        $album->setThumbnail($this->setThumbDetails($responseXml->entry[0]));

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
                . $this->getCropThumbnailKey($options['thumbnail']['crop'])
                . '&imgmax=1600';

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
            $link_attr = $entry->link[1]->attributes();

            /** @var $album Album */
            $album = new Album();
            $album->setid((int)$ns_gphoto->id);
            $album->setTitle((string) $entry->title);
            $album->setUrl((string) $link_attr['href']);

            $album->setThumbnail($this->setThumbDetails($entry));

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

            $license_attr = $ns_gphoto->license->attributes();

            /** @var $photo Photo */
            $photo = new Photo();
            $originalUrl = (string)$entry->content['src'];
            $photo->setOriginal($originalUrl);

            if ($options['preview']) {
                $photo->setPreview($this->defineImageWidth($originalUrl, $options['preview']['width']));
            }

            $photo->setThumbnail($this->setThumbDetails($entry));

            $license = new License();
            $license->setId((int) $license_attr['id']);
            $license->setName((string) $license_attr['name']);
            $license->setUrl((string) $license_attr['url']);
            $photo->setLicense($license);

            array_push($photos, $photo);
        }
        return $photos;
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
        $ns_media = $entry->children($entryNamespaces['media']);
        $thumb_attr = $ns_media->group->thumbnail[0]->attributes();

        $thumbnail = new Thumbnail();
        $thumbnail->setUrl((string) $thumb_attr['url']);
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
