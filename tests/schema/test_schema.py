from validate import Validate_IATI_XML
import os


def test_schema_pass_simple():
	xml = """
<iati-activities version="2.01">
  <iati-activity> <!-- at least 1 -->
    <iati-identifier></iati-identifier>  <!-- 1 and only 1-->
    <reporting-org type="xx" ref="xx"><narrative>Organisation name</narrative></reporting-org> <!-- 1 and only 1-->
    <title> <!-- 1 and only 1-->
      <narrative>Xxxxxxx</narrative> <!-- At least 1-->
    </title>
    <description>
      <narrative>Xxxxxxx</narrative> <!-- At least 1-->
    </description>
    <participating-org role="xx"></participating-org> <!-- At least 1-->
    <!--<other-identifier></other-identifier>-->
    <activity-status code="xx"/> <!-- 1 and only 1-->
    <activity-date type="xx" iso-date="2013-11-27"/><!-- At least 1 --> <!--Narative allowed-->
    <activity-date type="xx" iso-date="2013-11-27"><!-- At least 1 --> <!--Narative allowed-->
      <narrative>Some stuff here</narrative>
    </activity-date>
  </iati-activity>
</iati-activities>
	"""

	validator = Validate_IATI_XML(xml)
	assert validator.status['status_schema'] == "Pass"


def test_wellformed_pass_complex():
	with open("{}/activity_schema_PASS.xml".format(os.path.dirname(os.path.abspath(__file__))), 'r', encoding="utf-16") as xmlfile:
		validator = Validate_IATI_XML(xmlfile.read())
	assert validator.status['status_schema'] == "Pass"


def test_schema_fail_simple():
	xml = """
<iati-activities generated-datetime="2014-09-10T07:15:37Z" version="2.01" linked-data-default="http://data.example.org/">
  
  <!--iati-activity starts-->
  <iati-activity xml:lang="en" default-currency="USD" last-updated-datetime="2014-09-10T07:15:37Z" linked-data-uri="http://data.example.org/123456789" hierarchy="1">
    
    <!--iati-identifier starts-->
    <iati-identifier>AA-AAA-123456789-ABC123</iati-identifier>
    <!--iati-identifier ends-->

    <!-- No reporting-org element, or any other of the 2.x required elements) -->
  
  </iati-activity>
</iati-activities>
	"""

	validator = Validate_IATI_XML(xml)
	assert validator.status_overall == "Fail" # If at least one test fails, overall test status should be fail.
	assert validator.status['status_schema'] == "Fail"


def test_wellformed_fail_complex():
	with open("{}/activity_schema_FAIL.xml".format(os.path.dirname(os.path.abspath(__file__))), 'r') as xmlfile:
		validator = Validate_IATI_XML(xmlfile.read())

	assert validator.status_overall == "Fail" # If at least one test fails, overall test status should be fail.
	assert validator.status['status_schema'] == "Fail"
