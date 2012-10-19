Dynamic DNS Server
==================

Upload this script to any webspace and let your FritzBox call it. It will work with other DynDNS-Clients as well.

FritzBox 7360 Settings:

Name | Value
------------ | -------------
Update-URL | http://example.com/update.php?ip4addr=<ipaddr>&ip6addr=<ip6addr>&user=<username>&password=<pass>&domain=<domain>
Domain | anything you want, but make sure its a valid URL, e.g. www.example.com
User | our username from your config (in update.php)
Password | your password from your config (in update.php)


