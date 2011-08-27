<?php

class PicasaWeb {
    /** @var RestClient */
    private $restClient;

    public function getPhotos() {
        $this->restClient->execute();
        $responseBody = $this->restClient->getResponseBody();

        return $this->createPhotoListFrom($responseBody);
    }

    public function createPhotoListFrom($responseBody)
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

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }
}
