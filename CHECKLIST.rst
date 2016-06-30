IATI Public Validator Checklist
===============================

[To be reviewed after the rewrite detailed in the README.md file.]


We should know which code is 'ours'
-----------------------------------

This is a core IATI software project


All code should have a lead person identified
---------------------------------------------

Dale Potter dale.potter@devinit.org


Our projects/code should be appropriately branded
-------------------------------------------------

This has some IATI branding but could be reviewed. It is using a Twitter Bootstrap theme.


Our code/projects should be in version control and present links to issue trackers and source code
--------------------------------------------------------------------------------------------------

This is in GitHub. Live code has links to issuer tracker, source code, and to IATI support


Each piece of code should have a document(!), a roadmap, and estimate of resources, and a licence
-------------------------------------------------------------------------------------------------

| Licence is present  AGPLv3, this is the document, 
| No real roadmap, or estimate of resources.


We should be confident that updates to our code will not break existing functionality
-------------------------------------------------------------------------------------

| Some tests are available, the GitHub repository is linked up with Travis
| PHPUnit test is the framework.


Our code should be on our servers - we should be able to monitor the performance of those servers
-------------------------------------------------------------------------------------------------

| Code is on our servers, we have monitoring in place (Google analytics) but no human process
| Server load is not assessed - we could do some testing


It should make sense in the way people access our tools/code
------------------------------------------------------------

Yes, this on our domain: http://validator.iatistandard.org/


We should know how our code is being used - logs!
-------------------------------------------------

| The application has some additional logging:
| How many uploads, links pastes.
| Some idea about size of files tested.

Google Analytics is also used.


Our code will need to adapt with schema changes and changes to external systems upon which it relies
----------------------------------------------------------------------------------------------------

| This application does need to react to changes to the schema. 
| Instructions on what to do when upgrading are given in the README.md file.
| In future it may need to react to changes in codelists and rulesets.



Developers should be able to find useful resources and help easily
------------------------------------------------------------------

This project has a CONTRIBUTING.rst file


We should be able to communicate with the users of our code
-----------------------------------------------------------

The tool has a notification area on the front page


Users should be able to communicate with us about our code
----------------------------------------------------------

This is ok. Support tab is present, GitHub offers another route


We should protect our users privacy
-----------------------------------

| Cookies disclaimer is in the footer. 
| Submitted files should be deleted after 3 days, provided the cron job is running


We should be clear about how we work with contractors
-----------------------------------------------------

Not applicable currently

If our code works with IATI data, have we considered how it will work as the IATI datasets grow, both in terms of individual file size and as a corpus
------------------------------------------------------------------------------------------------------------------------------------------------------

The main issue for this application is the Size of IATI data/files. Currently we set a 10MB upload limit in the application.
Over time we may need to revise/review upload limits.
We also clear out uploaded files after 3 days. If use increased significantly we may need more hard disk space, or to clean out 
more regularly. We keep files for three days so people can share short term permalinks to results data.


Our code should be secure
-------------------------

| We think it is. XML injection has been considered. 
| PHP user input has been considered and sanitised.


We should know that our code is working properly
------------------------------------------------

There is a cron job required on this code. This removes uploaded files that are three days old.
