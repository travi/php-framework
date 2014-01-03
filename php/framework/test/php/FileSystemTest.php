<?php

use travi\framework\utilities\FileSystem;

class FileSystemTest extends PHPUnit_Framework_TestCase
{
    const ANY_STYLE_SHEET = 'something';
    const PATH_TO_SITE = "/path/to/site";
    const PATH_TO_SHARED_DEPS = "/path/to/shared/deps";
    const PATH_TO_PAGE_TEMPLATE = 'path/to/page/template/';

    /** @var FileSystem */
    private $fileSystem;

    public function setUp()
    {
        $this->fileSystem = new FileSystemShunt();
        $this->fileSystem->setSitePath(self::PATH_TO_SITE);
        $this->fileSystem->setSharedPath(self::PATH_TO_SHARED_DEPS);
    }

    public function testExistingStyleSheetRelativeToSiteSheetDirReturnsTrue()
    {
        $this->assertTrue($this->fileSystem->styleSheetExists(self::ANY_STYLE_SHEET));
    }

    public function testExistingStyleSheetRelativeToDocRootReturnsTrue()
    {
        $this->assertTrue(
            $this->fileSystem->styleSheetExists(FileSystem::PATH_TO_STYLE_SHEETS . self::ANY_STYLE_SHEET)
        );
    }

    public function testExistingSharedStyleSheetReturnsTrue()
    {
        $this->assertTrue(
            $this->fileSystem->styleSheetExists(
                FileSystem::PATH_TO_SHARED_STYLE_SHEETS . self::ANY_STYLE_SHEET
            )
        );
    }

    public function testExistingPageTemplateFound()
    {
        $this->assertTrue(
            $this->fileSystem->pageTemplateExists(self::PATH_TO_PAGE_TEMPLATE)
        );
    }

    public function testExternalSheetsReturnFalse()
    {
        $this->assertFalse($this->fileSystem->styleSheetExists('http://somesheet'));
        $this->assertFalse($this->fileSystem->styleSheetExists('https://somesheet'));
        $this->assertFalse($this->fileSystem->styleSheetExists('//somesheet'));
    }
}

class FileSystemShunt extends FileSystem
{
    public function fileExists($file)
    {
        switch ($file) {
        case FileSystemTest::PATH_TO_SITE . "/doc_root/resources/css/" . FileSystemTest::ANY_STYLE_SHEET:
            return true;
        case FileSystemTest::PATH_TO_SITE . "/doc_root/resources/thirdparty/travi-styles/css/" . FileSystemTest::ANY_STYLE_SHEET:
            return true;
        case FileSystemTest::PATH_TO_SITE . '/app/view/pages/' . FileSystemTest::PATH_TO_PAGE_TEMPLATE:
            return true;
        default:
            return false;
        }
    }
}
