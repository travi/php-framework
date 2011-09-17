<?php
 
class FileSystem
{
    const PATH_TO_PAGE_STYLE_SHEETS = '/resources/css/pages/';
    const CSS_EXT = '.css';

    /** @var Request */
    private $request;
    private $sitePath;

    /**
     * @return bool|string
     */
    public function getPageStyleByConvention()
    {
        $pathToSheetRelativeToDocRoot = self::PATH_TO_PAGE_STYLE_SHEETS
              . $this->request->getController() . '/'
              . $this->request->getAction()
              . self::CSS_EXT;

        $fullPathToSheet = $this->sitePath . '/doc_root' . $pathToSheetRelativeToDocRoot;

        if (file_exists($fullPathToSheet)) {
            return $pathToSheetRelativeToDocRoot;
        } else {
            return false;
        }
    }

    /**
     * @PdInject request
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
