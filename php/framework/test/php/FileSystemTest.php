<?php
 
class FileSystemTest extends PHPUnit_Framework_TestCase
{
    const ANY_STYLE_SHEET = 'something';
    const PATH_TO_SITE = "/path/to/site";
    const PATH_TO_SHARED_DEPS = "/path/to/shared/deps";

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
            $this->fileSystem->styleSheetExists(FileSystem::PATH_TO_SHARED_STYLE_SHEETS . self::ANY_STYLE_SHEET)
        );
    }

    public function testExternalSheetsReturnFalse()
    {
        $this->markTestIncomplete();
    }
}

class FileSystemShunt extends FileSystem
{
    public function fileExists($file)
    {
        switch ($file) {
        case FileSystemTest::PATH_TO_SITE . "/doc_root/resources/css/" . FileSystemTest::ANY_STYLE_SHEET:
            return true;
        case FileSystemTest::PATH_TO_SHARED_DEPS . "/client/css/" . FileSystemTest::ANY_STYLE_SHEET:
            return true;
        default:
            echo $file;
            return false;
        }
    }
}
