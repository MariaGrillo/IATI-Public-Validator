# IATI-Public Validator

[![Build Status](https://travis-ci.org/IATI/IATI-Public-Validator.svg?branch=master)](https://travis-ci.org/IATI/IATI-Public-Validator)
[![License: MIT](https://img.shields.io/badge/license-AGPLv3-blue.svg)](https://github.com/IATI/IATI-Public-Validator/blob/master/LICENSE.md)


## Introduction

This branch contains a new version the IATI Public Validator. The current version can be accessed at: http://validator.iatistandard.org/

The aim of the application is to help people check a given  file for complience against the [IATI Standard](http://www.iatistandard.org/).

A new version is needed in order to validate against the IATI Rulesets as well as to offer some content checking (aginst allowed codelist values, etc). The plan is to build a modular application adding tests as we go.


## Development plan
Sprint 1 (three weeks to Wednesday 20th July) will focus on

* Python Flask project set-up
* UI & API functionality to:
  * Input XML by POST (API) or paste (UI)
  * Test for well-formed XML
  * Test for validation against the IATI activity schema

For reference, a sample API output is contained within `sample_api_output.json`.

Detailed sprint planning is available in the [sprint 1 Google sheet](https://docs.google.com/spreadsheets/d/1yGL0MC6p7Ul9EeWNctXqnL8fehkt94OaMa2GVEnsrfI/edit?usp=sharing).


## Requirements

* python-virtualenv
* libxml2-dev


## Technology overview

The planned implementation is to use Python Flask with the [Flask-RESTful]( http://flask-restful-cn.readthedocs.io) extension. Schema validation will be done using the [lxml library](http://lxml.de/).


## Installation

```
# Clone this repository and enter into it
git clone 
cd IATI-Public-Validator

# Create a virtual environment (recommended)
virtualenv pyenv
source pyenv/bin/activate

# Install python depencies
pip install -r requirements.txt

# Download all versions of the IATI Schemas 
./get_iati_schemas.sh
```

[More to follow]


## Running a local development version

[To follow]


## About file upload size

[To follow]


## Tests

There will be application tests in the `tests` folder. These are run using [Pytest](http://pytest.org/latest/):

Individual XML files that should pass or fail various validator tests are found in the `tests/xml/` directory


## Upgrades

To upgrade the application you need to update the files, and sometimes you will need to update the IATI Schema.

To upgrade the files, if you use git, a git pull on the appropriate directory will update your files. Alternatively, re-install using the installation instructions.

To make sure you have the latest schema files, run `./get_iati_schemas.sh`
