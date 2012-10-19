Dynamic DNS Server
==================

Upload this script to any PHP-enabled webspace and let FritzBox call it. It will work with other DynDNS-Clients as well.


FritzBox 7360 Settings
----------------------

Name         | Value
------------ | -------------
Update-URL   | <http://example.com/update.php?ip4addr=<ipaddr>&ip6addr=<ip6addr>&user=<username>&password=<pass>&domain=<domain>>
Domain       | anything you want, but make sure its a valid URL, e.g. www.example.com
User         | our username from your config (in update.php)
Password     | your password from your config (in update.php)

Example URL calls
-----------------
* `http://example.com/update.php?user=XXX&password=XXX&ip4addr=0.0.0.0&ip6addr=0:0:0:0:0:0:0:0`
* `http://example.com/update.php?user=XXX&password=XXX&ip4addr=0.0.0.0`
* `http://example.com/update.php?user=XXX&password=XXX&ip6addr=0:0:0:0:0:0:0:0`
* `http://example.com/update.php?user=XXX&password=XXX&reset=1`
* `http://example.com/ip.html (if IP_HTML_PAGE is set)`

License
-------
Copyright 2012 Matthias Kadenbach  
Released under the MIT license


