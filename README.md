Dynamic DNS Server
==================

Upload this script to any PHP-enabled webspace and let FritzBox call it. *It will work with other DynDNS-Clients as well.*

Usage
-----
* Upload everything to your webspace
* (you may want to hide data.json and ip.template.html from your webroot)
* Update the config section in update.php
* Update your Dynamic DNS FritzBox settings
* If you want to broadcast the new IP to other services (e.g. your domain registrar or whatever) look for @todo and implement it here

Domain Registrar APIs (@todo)
-----------------------------
* https://www.inwx.de/de/download/file/api-current.zip
* http://blog.philippklaus.de/blog/2011/05/31/access-the-internetworx-xml-rpc-api-via-python/
* https://github.com/pklaus/python-inwx-xmlrpc
* http://patrick.oberdorf.net/2011/10/07/inwx-de-als-dyndns/

FritzBox 7360 Settings
----------------------

Name         | Value
------------ | -------------
Update-URL   | [http://example.com/update.php?ip4addr=&lt;ipaddr&gt;&ip6addr=&lt;ip6addr&gt;&user=&lt;username&gt;&password=&lt;pass&gt;&domain=&lt;domain&gt;](http://example.com)
Domain       | anything you want, but make sure its a valid URL, e.g. www.example.com
User         | username from your config (in update.php)
Password     | password from your config (in update.php)

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


