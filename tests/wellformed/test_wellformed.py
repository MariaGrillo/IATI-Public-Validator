import os

from validate import Validate_IATI_XML


def get_abs_path(rel_fname):
	script_dir = os.path.dirname(__file__) # Absolute dir the script is in
	return os.path.join(script_dir, rel_fname)


def test_wellformed_pass_simple():
	xml = """
<iati-activities generated-datetime="2014-09-10T07:15:37Z" version="2.01" linked-data-default="http://data.example.org/">
  
  <!--iati-activity starts-->
  <iati-activity xml:lang="en" default-currency="USD" last-updated-datetime="2014-09-10T07:15:37Z" linked-data-uri="http://data.example.org/123456789" hierarchy="1">
    
    <!--iati-identifier starts-->
    <iati-identifier>AA-AAA-123456789-ABC123</iati-identifier>
    <!--iati-identifier ends-->
  
  </iati-activity>
</iati-activities>
	"""

	validator = Validate_IATI_XML(xml)
	assert validator.status_overall == "Pass"
	assert validator.status['status_well_formed_xml'] == "Pass"


def test_wellformed_pass_complex():
	with open(get_abs_path("well_formed_PASS.xml"), 'r') as xmlfile:
		validator = Validate_IATI_XML(xmlfile.read())

	assert validator.status_overall == "Pass"
	assert validator.status['status_well_formed_xml'] == "Pass"


def test_wellformed_fail_simple():
	xml = """
<iati-activities generated-datetime="2014-09-10T07:15:37Z" version="2.01" linked-data-default="http://data.example.org/">
  
  <!--iati-activity starts-->
  <iati-activity xml:lang="en" default-currency="USD" last-updated-datetime="2014-09-10T07:15:37Z" linked-data-uri="http://data.example.org/123456789" hierarchy="1">
    
    <!--iati-identifier starts-->
    <iati-identifier>AA-AAA-123456789-ABC123
    <!--MISSING iati-identifier end tag-->
  
  </iati-activity>
</iati-activities>
	"""

	validator = Validate_IATI_XML(xml)
	assert validator.status_overall == "Fail"
	assert validator.status['status_well_formed_xml'] == "Fail"


def test_wellformed_fail_complex():
	with open(get_abs_path("well_formed_FAIL.xml"), 'r') as xmlfile:
		validator = Validate_IATI_XML(xmlfile.read())

	assert validator.status_overall == "Fail"
	assert validator.status['status_well_formed_xml'] == "Fail"
