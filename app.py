from flask import Flask, jsonify
from flask_restful import Resource, Api, reqparse

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
        #FIXME ADD COMMENT HERE

        """

        # Set-up parser
        parser = reqparse.RequestParser()
        parser.add_argument('xml', type=str, help='IATI Data to be validated')
        args = parser.parse_args()

        # Get XML and return
        xml = str(args['xml'])
        return jsonify({'post': xml})

api.add_resource(Validate_Raw_XML, '/api/validate/xml')


@app.route('/api/docs')
def docs():
    """
    #FIXME API documentation page to be rendered here
    """
    return "Documentation site"

if __name__ == '__main__':
    app.run(debug=True)
