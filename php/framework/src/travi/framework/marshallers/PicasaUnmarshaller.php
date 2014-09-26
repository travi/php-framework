<?php

namespace travi\framework\marshallers;

use travi\framework\photos\Album;
use travi\framework\photos\License;
use travi\framework\photos\Media;
use travi\framework\photos\Photo;
use travi\framework\photos\PicasaService;
use travi\framework\photos\Thumbnail;
use travi\framework\photos\Video;

class PicasaUnmarshaller
{

    public function toAlbum($xml, $options)
    {
        $album = new Album();

        $xmlElement = new \SimpleXMLElement($xml);
        $namespaces = $xmlElement->getNamespaces(true);

        $googlePhotoNamespace = $xmlElement->children($namespaces['gphoto']);
        $album->setId((string) $googlePhotoNamespace->id);

        $album->setTitle((string) $xmlElement->title);

        $album->setThumbnail($this->setAlbumThumbnailDetails($xmlElement));

        $album->setPhotos($this->buildPhotoListFrom($xmlElement, $options, $namespaces));

        $album->setTotalPhotoCount((int) $googlePhotoNamespace->numphotos);

        return $album;
    }

    public function toMediaList($xml, $options)
    {
        $xmlElement = new \SimpleXMLElement($xml);

        $namespaces = $xmlElement->getNamespaces(true);

        return $this->buildPhotoListFrom($xmlElement, $options, $namespaces);
    }

    public function toAlbumList($xml)
    {
        $albums = array();

        $xmlElement = new \SimpleXMLElement($xml);

        $namespaces = $xmlElement->getNamespaces(true);

        foreach ($xmlElement->entry as $entry) {
            $googlePhotoNamespace = $entry->children($namespaces['gphoto']);
            $linkAttributes       = $entry->link[1]->attributes();

            $album = new Album();

            $album->setId((string) $googlePhotoNamespace->id);
            $album->setTitle((string) $entry->title);
            $album->setUrl((string) $linkAttributes['href']);

            $album->setThumbnail($this->setThumbDetails($entry));

            array_push($albums, $album);
        }

        return $albums;
    }

    private function setAlbumThumbnailDetails($responseXml)
    {
        $thumbnail = new Thumbnail();
        $thumbnail->setUrl((string) $responseXml->icon);
        return $thumbnail;
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
        array_splice($urlParts, -1, 0, PicasaService::MAX_WIDTH_KEY . $width);

        return implode('/', $urlParts);
    }

    /**
     * @param $entry \SimpleXMLElement
     * @return Thumbnail
     */
    private function setThumbDetails($entry)
    {
        $thumbnail = new Thumbnail();

        $entryNamespaces = $entry->getNamespaces(true);
        $ns_media        = $entry->children($entryNamespaces['media']);
        $thumb_attr      = $ns_media->group->thumbnail[0]->attributes();

        $thumbnail->setUrl((string) $thumb_attr['url']);

        return $thumbnail;
    }

    /**
     * @param $xmlElement
     * @param $options
     * @param $namespaces
     * @return array
     */
    private function buildPhotoListFrom($xmlElement, $options, $namespaces)
    {
        $mediaList = array();

        foreach ($xmlElement->entry as $entry) {
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

            $googlePhotoNamespace = $entry->children($namespaces['gphoto']);
            $media->setLicense($this->setLicenseDetails($googlePhotoNamespace));

            $media->setCaption((string) $entry->summary[0]);

            array_push($mediaList, $media);
        }
        return $mediaList;
    }

    /**
     * @param $googlePhotoNamespace
     * @return License
     */
    private function setLicenseDetails($googlePhotoNamespace)
    {
        $license = new License();

        $license_attr = $googlePhotoNamespace->license->attributes();

        $license->setId((int) $license_attr['id']);
        $license->setName((string) $license_attr['name']);
        $license->setUrl((string) $license_attr['url']);

        return $license;
    }

    private function isVideo($entry, $namespaces)
    {
        $ns_media = $entry->children($namespaces['media']);

        $versions = $ns_media->group->content;

        foreach ($versions as $version) {
            $attributes = $version->attributes();
            if (PicasaService::VIDEO_MEDIUM === (string) $attributes->medium) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $video Video
     * @param $entry \SimpleXMLElement
     * @param $namespaces
     */
    private function setVideoVersions($video, $entry, $namespaces)
    {
        $ns_media = $entry->children($namespaces['media']);

        $versions = $ns_media->group->content;

        foreach ($versions as $version) {
            $attributes = $version->attributes();
            if (PicasaService::VIDEO_MEDIUM === (string) $attributes->medium
                && PicasaService::MPEG_VIDEO_TYPE === (string) $attributes->type
            ) {
                $width  = (int) $attributes->width;
                $height = (int) $attributes->height;

                $video->setWidth($width);
                $video->setHeight($height);

                if (PicasaService::MOBILE_SIZE === $width || PicasaService::MOBILE_SIZE === $height) {
                    $video->setMobile((string) $attributes->url);
                }

                if (PicasaService::STANDARD_SIZE === $width || PicasaService::STANDARD_SIZE === $height) {
                    $video->setStandard((string) $attributes->url);
                }

                if (PicasaService::HIGH_DEF_SIZE === $width || PicasaService::HIGH_DEF_SIZE === $height) {
                    $video->setHighDef((string) $attributes->url);
                }
            }
        }
    }
}