sipgate API Class for Zend Framework 1.x

(c) 2013 Tobias Niepel

A basic implementation of the sipgate REST API
API Information: http://www.live.sipgate.de/api/rest


Installation
-------------
Just copy the "library" directory into your Zend Framework 1.x installation directory.


Usage
------
Just instanciate Sipgate_API in your Controller/Module/Whatever. The Zend AutoLoader should do all the magic of requiring the class.

e.g.:

my $api = new Sipgate_API("myUsername","myPassword");

This instanciates a new sipgate API Class and authenticates with the given username and password.


Any questions?
---------------
More detailed information can be found in the class itself.

