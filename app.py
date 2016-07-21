from flask import Flask, jsonify, redirect
from flask_restful import Resource, Api, reqparse
from validate import Validate_IATI_XML

app = Flask(__name__)
api = Api(app)


class Validate_Raw_XML(Resource):
    def get(self):
        """
        #FIXME This will return an error - post method only
        """
        return jsonify({'error': 'Raw XML data can be validated using a POST request.'})

    def post(self):
        """
        Post method to receive a raw IATI XML string and run the validation process.

        #TODO Add a Validate_IATI_XML.get_response() method to return the data, rather than 
              building on the fly.

        """

        # Set-up parser
        parser = reqparse.RequestParser()
        parser.add_argument('xml', type=str, help='IATI XML string to be validated')
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
    #TODO API documentation page to be rendered here
    For now, refirect to the API examples on github
    """
    return redirect(
        "https://github.com/IATI/IATI-Public-Validator/blob/81-validator-rewrite/temp/api-examples.md", 
        code=302
        )

if __name__ == '__main__':
    app.run(debug=True)
