<?php

class StackTest extends PHPUnit_Framework_TestCase {
    private function validate_xsd($filepath, $version, $success=TRUE) {
        include './vars.php';
        include './example.settings.php';
        $_SESSION['uploadedfilepath'] = $filepath; 
        ob_start();
        include './pages/validate-xsd.php';
        $out = ob_get_contents();
        ob_end_clean();
        echo "\n";
        $text = preg_replace('/\s+/', ' ', strip_tags($out));
        $this->assertContains("version $version", $text);
        if ($success) {
            $this->assertContains('Status Success', $text);
        }
        return $text;
    }
    public function test101() {
        $version = '1.01';
        $this->validate_xsd('tests/66.xml', $version);
        $this->validate_xsd('tests/activity_schema_FAIL.xml', $version, FALSE);
        $this->validate_xsd('tests/activity_schema_PASS.xml', $version);
        $this->validate_xsd('tests/activity_schema_title_FAIL.xml', $version, FALSE);
        // TODO: well_formed
    }
}
?>
