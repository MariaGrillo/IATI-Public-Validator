IATI-Public Validator
=====================

This is an application to help people check any random file for IATI complience.

the plan is to build a modular application adding tests as we go.

Basic functions are:
Test for well formedness
Test for validation against the iati schema

Quick Start
-----------

Checkout the files to your webserver.
You will need to add an 'upload' directory to the root of the project and make it writable by your webserver

IATI Schema
-----------
Currently the application is a bit inconsistent in the way it refrences the schema. Sometimes it uses the remote URL at others it links to downloaded files.
Sorry about that!

Cron (Tidy Up)
--------------
The upload directory will store files people upload to the service.
The file example.remove_files.php when run will remove files older than a specified time.
You should edit this file to set the path to your upload directory, and alter the time period.

How it works
------------

Once a file is uploaded or pulled from the web, the path to the file (and file details) are saved in session variable.
This then allows us to perform various tests on that file.
The index.php file controls all page views. 
Each test is contained in it's own 'page' within the pages/ directory.
Which page gets called is controlled by the $_GET variables passed by the URL. These are sanitised by an array of allowed values at the top of index.php

Tests
-----
The test/ directory contains a number of XML files that will pass or fail the various tests
We don't have any application tests in place.


