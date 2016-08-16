# IATI-Public Validator

[![Build Status](https://travis-ci.org/IATI/IATI-Public-Validator.svg?branch=master)](https://travis-ci.org/IATI/IATI-Public-Validator)
[![License: MIT](https://img.shields.io/badge/license-AGPLv3-blue.svg)](https://github.com/IATI/IATI-Public-Validator/blob/master/LICENSE.md)


## Introduction

This branch contains a new version the IATI Public Validator. The current (development) version can be accessed at: http://dev.validator.iatistandard.org/api/docs

The aim of the application is to help people check a given file for complience against the [IATI Standard](http://www.iatistandard.org/).

A new version is needed in order to validate against the IATI Rulesets as well as to offer some content checking (aginst allowed codelist values, etc). The plan is to build a modular application adding tests as we go.


## Development plan
Sprint 2 (three weeks to Friday 26th August) will focus on

* Adding API documentation
* Adding a point-and-click user interface to test for well-formed XML and schema validation
* Adding logging

For reference, current API examples are in `temp/api-examples.md`. An example API output is contained within `temp/sample_api_output.json`.

Detailed sprint planning is available in the [sprint 2 Google sheet](https://docs.google.com/spreadsheets/d/10XzACbHT4UvRljrnmT0AJsjYuif3w0Lz3QcKFERxcj0/edit?usp=sharing).


## Requirements

The following are [lxml dependencies](http://lxml.de/installation.html#requirements):

* libxml2-dev
* libxslt-dev
* python-dev

Some systems may also require:

* libxslt1-dev
* python3-dev
* zlib1g-dev

Recommended: 

* python-virtualenv


## Technology overview

The planned implementation is to use Python Flask with the [Flask-RESTful]( http://flask-restful-cn.readthedocs.io) extension. Schema validation will be done using the [lxml library](http://lxml.de/).


## Installation

```
# Clone this repository and enter into it
git clone https://github.com/IATI/IATI-Public-Validator.git
cd IATI-Public-Validator

# Switch to the development branch
git checkout 81-validator-rewrite

# Create a virtual environment using python 3 (recommended)
virtualenv -p python3 pyenv
source pyenv/bin/activate

# Install python depencies
pip install -r requirements.txt

# Download all versions of the IATI Schemas 
./get_iati_schemas.sh

```


### Running a local development version

```
# Run the server in development mode
python app.py

# API requests can be made to http://127.0.0.1:5000/
```


### Deploying to a server

The application can be deployed using [Apache](https://www.digitalocean.com/community/tutorials/how-to-deploy-a-flask-application-on-an-ubuntu-vps), [Nginx](http://vladikk.com/2013/09/12/serving-flask-with-nginx-on-ubuntu/), [Heroku](https://community.nitrous.io/tutorials/deploying-a-flask-application-to-heroku) or other server software.

The official instance is currently deployed using Apache. A wsgi file `deploy.wsgi` is provided for your convenience, although the installation paths may require editing.


## Sample API requests

See `temp/api-examples.md` for details.


## About file upload size

[To follow]


## Tests

There will be application tests in the `tests` folder. These are run using [Pytest](http://pytest.org/latest/):

Individual XML files that should pass or fail various validator tests are found in the `tests/xml/` directory

To run the tests, ensure you have run `pip install -r requirements_dev.txt`. Pytest can then be run using a python path to enable relative imports to be imported correctly:

```
PYTHONPATH=. py.test --cov .
```


## Upgrades

To upgrade the application you need to update the files, and sometimes you will need to update the IATI Schema.

To upgrade the files, if you use git, a git pull on the appropriate directory will update your files. Alternatively, re-install using the installation instructions.

To make sure you have the latest schema files, run `./get_iati_schemas.sh`
