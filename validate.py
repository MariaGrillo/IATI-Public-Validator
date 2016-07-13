import datetime
from lxml import etree
import re
import sys


class Validate_IATI_XML():
    # Set object defaults
    status_overall = None # Overall status of the test itself
    status = {
        'status_well_formed_xml': "Not checked"
    } # Output for the status of each test
    xml = "" # XML input string
    iati_version = {
        'version_tested': None,
        'type': None
        } # IATI version that self.xml will be validated against
    iati_etree = None # lxml etree object
    errors = [] # Empty list for any errors that are found
    warnings = []
    start_time = None
    completed_time = None

    
    def __init__(self, xml=None, iati_version=None):
        self.start_time = datetime.datetime.now()
        if xml is not None:
            self.xml = xml

        # Perform well-formed check
        if self.validate_well_formed(): # Will set self.iati_etree if successful
            # Continue to validate if the data is well formed
            
            if iati_version is None:
                version = self.get_version()
                self.iati_version['version_tested'] = version['version']
                self.iati_version['type'] = version['type']
            else:
                self.iati_version['version_tested'] = iati_version
                self.iati_version['type'] = "Input"

        # Set final output data, based on results
        self.completed_time = datetime.datetime.now()
        self.status_overall = 'Pass' if all(
            [True if v=='Pass' else False for v in self.status.values()]
            ) else 'Fail'
        return

    
    def get_version(self):
        """
        Attept to get the version number specified in iati-activities/@version. 
        If no version present, set as the latest version number.
        Returns:
          Dict containing data with the version found
        """
        detected_version = self.iati_etree.xpath('//@version')
        out = {}
        if detected_version:
            out['version'] = detected_version[0]
            out['type'] = "Detected"
        else:
            out['version'] = self.get_latest_version()
            out['type'] = "Version not found, set to latest version."
        return out

    
    def get_latest_version(self):
        # FIXME - get latest version number from the Version codelist
        return '2.02'

    
    def validate(self):
        well_formed = Validate_well_formed(self.xml)
        return True

    
    def get_metadata(self):
        return {
            'file_size_bytes': sys.getsizeof(self.xml),
            'began': self.start_time,
            'completed': self.completed_time,
            'version': self.iati_version,
        }


    def validate_well_formed(self):
        """
        Specific methods for validating that an XML file can be read and contains no syntax errors
        Returns:
          True -- if successfully set-up an etree object
          False -- if failed
        """
        try:
            self.iati_etree = etree.fromstring(self.xml)
            self.status['status_well_formed_xml'] = "Pass"
            return True
        except etree.XMLSyntaxError as exception_obj:
            # exception_obj = XMLSyntaxError('Premature end of data in tag reporting-org line 2, line 6, column 1',)
            self.status['status_well_formed_xml'] = "Fail"
            self.errors.append(self.well_formed_error_handler(exception_obj))
            return False

    
    def well_formed_error_handler(self, exception_obj):
        """
        Return a dict. Uses regex to capture line numbers from the exception message.
        Input:
          exception_obj -- An exception object. 
          Example: "Premature end of data in tag iati-activity line 3, line 93, column 1"
        Returns:
          A dict containing lineno, error type, xml context and detailed error message
        """

        """
        NOTE: 
        Raw libxml logs are available using exception_obj.error_log.filter_from_level(etree.ErrorLevels.FATAL)
        However the data from these appears to be more confusing in some cases than the overall error message returned in the exception.
        """

        # Capture data using regex
        iati_identifier = "" #TODO: Could add regex: Before last <iati-activity element, capture text witin <iati-identifier>...</iati-identifier>
        element = re.findall(r'tag\s(\S+)', str(exception_obj))
        line_number = re.findall(r'line\s(\d+)', str(exception_obj))
        xml_context = "" #TODO: Could add regex: get opening tag of name 'element' and closing tag at second line and column number
        
        return {
            'type': 'well_formed_error',
            'iati-identifier': None,
            'narrative': str(exception_obj),
            'element': element[0] if element else None,
            'line_number': line_number[0] if line_number else None,
            'xml_context': None
            }
