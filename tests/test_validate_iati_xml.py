from validate import Validate_IATI_XML


def test_get_version_detected_or_inputted():
    xml = """
<iati-activities generated-datetime="2014-09-10T07:15:37Z" version="2.01" linked-data-default="http://data.example.org/">
</iati-activities>
    """

    validator = Validate_IATI_XML(xml)
    assert validator.iati_version == {
        "version_tested": "2.01", 
        "type": "Detected"
        }

    validator = Validate_IATI_XML(xml, iati_version="1.05")
    assert validator.iati_version == {
        "version_tested": "1.05", 
        "type": "Input"
        }


def test_get_version_not_set():
    xml = """
<iati-activities generated-datetime="2014-09-10T07:15:37Z" linked-data-default="http://data.example.org/">
</iati-activities>
    """

    validator = Validate_IATI_XML(xml)
    assert validator.iati_version == {
        "version_tested": "2.02", 
        "type": "Version not found, set to latest version."
        }


def test_get_metadata():
    """
    Check 4 elements returned from the validator.get_metadata() method
    """

    xml = """
<iati-activities generated-datetime="2014-09-10T07:15:37Z" linked-data-default="http://data.example.org/">
</iati-activities>
    """

    validator = Validate_IATI_XML(xml)
    assert len(validator.get_metadata()) == 4
