from flask import Flask, jsonify, redirect
from flask_restful import Resource, Api, reqparse
from validate import Validate_IATI_XML

app = Flask(__name__)
api = Api(app)

# API output dictionary
api_output = {
    'metadata': {
        'temporary_share_link_ui': 'http://validator.iatistandard.org/result/1467121050',
        'began': '2016-06-30T18:25:43.511Z', 
        'completed': '2016-06-30T18:25:43.511Z',
        'file_size_bytes': '2048',
        'type': 'paste',
        'version': {
            'version_tested': '2.02', 
            'type': 'detected'
        },
        'temporary_share_link_api': 'http://validator.iatistandard.org/api/result/1467121050'
    },
    'status': {
        'status_detail': {
            'status_schema': 'fail', 
            'status_content_check': 'not checked', 
            'status_rulesets': 'not checked', 
            'status_well_formed_xml': 'pass'
        }, 'overall_status': 'fail'}, 
    'error_count': '2',
    'errors': [
        {
        'iati-identifier': 'AA-AAA-123456789-ABC123',
        'type': 'schema_error',
        'line_number': '50',
        'element': 'sector', 
        'narrative': "Element 'sector': The attribute 'code' is required but missing.", 
        'xml_context': '<sector>'
        }, 
        {
        'iati-identifier': 'AA-AAA-123456789-ABC123', 
        'type': 'schema_error', 
        'line_number': '54', 
        'element': 'value', 
        'narrative': "Element 'value': The attribute 'value-date' is required but missing.", 
        'xml_context': '<value>'
        }
        ]
    }

class Validate_Raw_XML(Resource):
    def get(self):
        """
        #FIXME This will return an error - post method only
        """
        return jsonify({'error': 'Raw XML data can be validated using a POST request.'})

    def post(self):
        """
        #FIXME ADD COMMENT HERE

        """

        # Set-up parser
        parser = reqparse.RequestParser()
        parser.add_argument('xml', type=str, help='IATI Data to be validated')
        args = parser.parse_args()

        # Get XML and store validation object
        xml = str(args['xml'])
        validator = Validate_IATI_XML(xml)
        
        # Return result
        return jsonify({
            'metadata': validator.get_metadata(),
            'status': {
                'status_overall': validator.status_overall,
                'status_detail': validator.status
                },
            'error_count': len(validator.errors),
            'error_detail': validator.errors
            })

api.add_resource(Validate_Raw_XML, '/api/validate/xml')


@app.route('/api/docs')
def docs():
    """
    #FIXME API documentation page to be rendered here
    For now, refirect to the API examples on github
    """
    return redirect("https://github.com/IATI/IATI-Public-Validator/blob/81-validator-rewrite/temp/api-examples.md", code=302)

if __name__ == '__main__':
    app.run(debug=True)
