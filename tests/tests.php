<?php

class ValidatorTest extends PHPUnit_Framework_TestCase {
    private function well_formed($filepath, $success=TRUE) {
        include './vars.php';
        include './example.settings.php';
        $_SESSION['uploadedfilepath'] = $filepath; 
        $_SESSION['wellformed'] = FALSE;
        $testing_file_name = 'test';
        ob_start();
        include './pages/front.php';
        $out = ob_get_contents();
        ob_end_clean();
        
        if ($success) {
            $this->assertContains('This is a well formed xml file', $out);
            $this->assertNotContains('This is not a well-formed xml file', $out);
        }
        else {
            $this->assertNotContains('This is a well formed xml file', $out);
            $this->assertContains('This is not a well-formed xml file', $out);
        }
    }
    private function validate_xsd($filepath, $version, $success=TRUE) {
        include './vars.php';
        //global $iati_versions;
        include './example.settings.php';
        $_SESSION['uploadedfilepath'] = $filepath; 
        ob_start();
        include './pages/validate-xsd.php';
        $out = ob_get_contents();
        ob_end_clean();

        //$text = preg_replace('/\s+/', ' ', strip_tags($out));
        $this->assertContains("version $version", $out);
        if ($success) {
            $this->assertContains('This file validates', $out);
            $this->assertNotContains('This file does NOT validate', $out);
        }
        else {
            $this->assertNotContains('This file validates', $out);
            $this->assertContains('This file does NOT validate', $out);
        }
    }
     private function valid_version($filepath, $version, $success=TRUE) {
        include './vars.php';
        //global $iati_versions;
        include './example.settings.php';
        $_SESSION['uploadedfilepath'] = $filepath; 
        ob_start();
        include './pages/validate-xsd.php';
        $out = ob_get_contents();
        ob_end_clean();

        //$text = preg_replace('/\s+/', ' ', strip_tags($out));
        $this->assertContains("version $version", $out);
        if ($success) {
            $this->assertContains('which is a recognised version of the schema', $out);
            $this->assertNotContains('You should use a recognised version of the schema', $out);
        }
        else {
            $this->assertNotContains('which is a recognised version of the schema', $out);
            $this->assertContains('You should use a recognised version of the schema', $out);
        }
    }
    private function version1_independent($version) {
        $this->validate_xsd('./tests/xml/stub.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/66.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/activity_schema_FAIL.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/activity_schema_PASS.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/anyuri.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/anyuri_FAIL.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/organisation.xml', $version, TRUE); //Organisation file valid for 1.03,1.02,1.01
        $this->validate_xsd('./tests/xml/two-iati-activities.xml', $version, FALSE);
        $this->valid_version('./tests/xml/wrong_version_declared.xml', $version, FALSE);
        $this->valid_version('./tests/xml/versions/101not102.xml', $version, TRUE);
        $this->valid_version('./tests/xml/versions/102not101.xml', $version, TRUE);
        $this->valid_version('./tests/xml/versions/103not102.xml', $version, TRUE);
        $this->valid_version('./tests/xml/versions/104not103.xml', $version, TRUE);
        $this->valid_version('./tests/xml/versions/105not103.xml', $version, TRUE);
    }
    private function version2_independent($version) {
        $this->valid_version('./tests/xml/versions/201not1x.xml', $version, TRUE);
    }
    public function test101() {
        $version = '1.01';
        $this->version1_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, FALSE);
    }
    public function test102() {
        $version = '1.02';
        $this->version1_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, FALSE);
    }
    public function test103() {
        $version = '1.03';
        $this->version1_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, FALSE);
    }
    public function test104() {
        $version = '1.04';
        $this->version1_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, FALSE);
    }
    public function test105() {
        $version = '1.05';
        $this->version1_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, FALSE);
    }
    public function test201() {
        $version = '2.01';
        $this->version2_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, TRUE);
    }
    public function test202() {
        $version = '2.02';
        $this->version2_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, FALSE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/202not201.xml', $version, TRUE);
    }
    public function testAuto() {
        $version = 'auto';
        $this->version1_independent($version);
        $this->version2_independent($version);
        $this->validate_xsd('./tests/xml/versions/101not102.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/102not101.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/103not102.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/104not103.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/105not103.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/201not1x.xml', $version, TRUE);
        $this->validate_xsd('./tests/xml/versions/202not201.xml', $version, TRUE);
    }
    public function test_well_formed() {
        $this->well_formed('./tests/xml/well_formed_PASS.xml', TRUE);
        $this->well_formed('./tests/xml/well_formed_FAIL.xml', FALSE);
    }
    
    //compliance1 test
    //$this->validate_xsd('./tests/xml/activity_schema_title_FAIL.xml', $version, FALSE);

    public function version_sanity($version) {
        include './vars.php';
        $this->assertContains($current_version, $iati_versions);
    }
}
?>
