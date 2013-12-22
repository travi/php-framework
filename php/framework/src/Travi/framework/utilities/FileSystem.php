<?php

namespace Travi\framework\utilities;

use Travi\framework\http\Request;

//TODO: should cache any information requested from the filesystem (possibly additional info)
//        to make subsequent calls faster
class FileSystem
{
    const PATH_TO_STYLE_SHEETS = '/resources/css/';
    const PATH_TO_SHARED_STYLE_SHEETS = '/resources/thirdparty/travi-styles/css/';
    const PATH_TO_SHARED = '/resources/thirdparty/travi-styles/';
    const PATH_TO_SHARED_THIRDPARTY = '/resources/shared/thirdparty/';
    const PAGE_STYLE_SHEET_DIR = 'pages/';
    const CSS_EXT = '.css';

    const WRITING_ONLY = 'w';
    const READING_ONLY = 'r';

    /** @var Request */
    private $request;
    private $sitePath;
    private $sharedPath;

    /**
     * @param $pathToTemplate
     * @return bool
     */
    public function pageTemplateExists($pathToTemplate)
    {
        return $this->fileExists($this->sitePath . '/app/view/pages/' . $pathToTemplate);
    }

    public function styleSheetExists($sheet)
    {
        if (strstr($sheet, self::PATH_TO_STYLE_SHEETS)) {
            $pathToSheet = $this->sitePath . '/doc_root' . $sheet;
        } elseif (strstr($sheet, self::PATH_TO_SHARED)) {
            $pathToSheet = $this->sitePath . '/doc_root/resources' . substr($sheet, strlen('/resources'));
        } elseif (strstr($sheet, '//')) {
            return false;
        } else {
            $pathToSheet = $this->sitePath . '/doc_root' . self::PATH_TO_STYLE_SHEETS . $sheet;
        }
        return $this->fileExists($pathToSheet);
    }

    /**
     * @return bool|string
     */
    public function getPageStyleByConvention()
    {
        $pathToSheetByConvention = self::PAGE_STYLE_SHEET_DIR
            . $this->request->getController() . '/'
            . $this->request->getAction()
            . self::CSS_EXT;

        if ($this->styleSheetExists($pathToSheetByConvention)) {
            return self::PATH_TO_STYLE_SHEETS . $pathToSheetByConvention;
        } else {
            return false;
        }
    }

    public function getLastModifiedTimeFor($file)
    {
        return filemtime($file);
    }

    public function parseYamlFileAt($pathToFile)
    {
        return \Spyc::YAMLLoad($pathToFile);
    }

    public function fileExists($file)
    {
        return file_exists($file);
    }

    public function createFile($fileName, $pathToFile)
    {
        $fileHandle = fopen($pathToFile . $fileName, self::WRITING_ONLY) or die('cant open file');
        fclose($fileHandle);
    }

    public function readFile($fileName, $pathToFile)
    {
        $file = $pathToFile . $fileName;

        $fileHandle = fopen($file, self::READING_ONLY) or die('cant open file');
        $fileContents = fread($fileHandle, filesize($file));
        fclose($fileHandle);

        return $fileContents;
    }

    public function getLinesFromFile($fileName, $pathToFile)
    {
        $pwFile = $this->sitePath . $pathToFile . $fileName;

        if (file_exists($pwFile) && is_readable($pwFile)) {
            if ($pwFileHandle = fopen($pwFile, 'r')) {
                $lines = file($pwFile);
                fclose($pwFileHandle);
                return $lines;
            } else {
                throw new \Exception("couldn't open password file");
            }
        } else {
            throw new \Exception("password file doesn't exist at " . $pwFile . " or is not readable");
        }
    }

    public function writeToFile($fileName, $pathToFile, $contents)
    {
        $fileHandle = fopen($pathToFile . $fileName, self::WRITING_ONLY) or die('cant open file');

        fwrite($fileHandle, $contents);

        fclose($fileHandle);
    }

    /**
     * @PdInject request
     * @param $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setSitePath($sitePath)
    {
        $this->sitePath = $sitePath;
    }

    public function setSharedPath($path)
    {
        $this->sharedPath = $path;
    }

    public function getSharedPath()
    {
        return $this->sharedPath;
    }
}
