tunedin
=======

Code sample

Aim of this challenge is to test your overall PHP engineering skills. You should create modern, object-orientated
application programming interface (API) with clear separation of concerns between model and controller. When
working on the challenge, don’t forget about scalability and code-quality (code documentation, tests).
You are not expected to consider data caching.

Constraints:

 - Application should work out-of-the box on any Unix machine with PHP version 5.5.4 and standard PHP
modules. No additional software may be required (although ready-to-use external libraries may be included
in the package).

 - On initialize request, the API should download, extract and locally save the copy of IMDB movies/shows text
file. This is the only remote background connection it may perform.

 - On all other requests the API should use the local copy of the IMDB file to perform requested actions, or
report an error if IMDB file wasn’t downloaded yet.

 - Output of the API, even in case of failure, should be JSON.

 - All required non-standard php.ini configuration directives should be included in the bootstrap file.

