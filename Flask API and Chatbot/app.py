from flask import Flask, jsonify, request
from flask_cors import CORS,cross_origin
import workml as nik


app = Flask(__name__)
CORS(app, supports_credentials=True)

@app.route('/hello/')
@cross_origin()
def hello():
    uinp = request.args.get('input')
    response = nik.get_response(uinp)
    return jsonify({'message':f'{response}'})

if __name__ == '__main__':
    app.run(debug=True)