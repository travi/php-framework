<?php

//TODO: should cache any information requested from the filesystem (possibly additional info)
//        to make subsequent calls faster
class FileSystem
{
    const PATH_TO_STYLE_SHEETS = '/resources/css/';
    const PAGE_STYLE_SHEET_DIR = 'pages/';
    const CSS_EXT = '.css';

    /** @var Request */
    private $request;
    private $sitePath;

    public function styleSheetExists($sheet)
    {
        if (strstr($sheet, self::PATH_TO_STYLE_SHEETS)) {
            $pathToSheet = $this->sitePath . '/doc_root' . $sheet;
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

    public function parseYamlFileAt($pathToFile)
    {
        return Spyc::YAMLLoad($pathToFile);
    }

    private function fileExists($file)
    {
        return file_exists($file);
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
}
