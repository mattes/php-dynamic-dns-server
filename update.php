<?php
/*!
 * PHP Dynamic DNS Server v0.9
 * https://github.com/mattes/
 *
 * Copyright 2012 Matthias Kadenbach
 * Released under the MIT license
 *
 * Examples:
 * http://example.com/update.php?user=XXX&password=XXX&ip4addr=0.0.0.0&ip6addr=0:0:0:0:0:0:0:0
 * http://example.com/update.php?user=XXX&password=XXX&ip4addr=0.0.0.0
 * http://example.com/update.php?user=XXX&password=XXX&ip6addr=0:0:0:0:0:0:0:0 
 * http://example.com/update.php?user=XXX&password=XXX&reset=1
 *
 * http://example.com/ip.html (if IP_HTML_PAGE is set)
 *
 * Using this script with FritzBox (7360) and Dynamic DNS:
 * Update-URL: http://example.com/update.php?ip4addr=<ipaddr>&ip6addr=<ip6addr>&user=<username>&password=<pass>&domain=<domain>
 * Domain: anything you want, but make sure its a valid URL, e.g. www.example.com
 * User: your username from your config below
 * Password: your password from your config below
 *
*/

// -------------------------------
// --- CONFIG --------------------
// -------------------------------

// set error reporting
// E_ALL for debug, 0 for production
error_reporting(E_ALL); 

// root directory with trailing slash!
define("ROOT", dirname(__FILE__) . "/"); 

// data file in json format, you may want to hide it from webroot
// make file writeable!
define("DATA_FILE_PATH", ROOT . "data.json");

// create a html page with the current ip address
// set to false to disable, make file writeable!
define("IP_HTML_PAGE", ROOT . "ip.html"); 

// set an username
define("USERNAME", "aladin"); 

// set a secure password
define("PASSWORD", "magic"); 

// lock user after number of failed login attempts
// if user got locked empty data.json manually
define("MAX_FAILED_LOGINS_BEFORE_LOCK", 5);

// use ip version 4 or 6?
define("USE_IPV", "4"); 

// -------------------------------
// -------------------------------

// Set the HTTP response code
if(!function_exists("http_response_code")) {
  function http_response_code($response_code = 200) {
    header(':', true, $response_code);
    return $response_code;
  }
}

// helper to check used IP version
function ipv($v) {
  return $v == USE_IPV;
}

// send http status code and return text
function send_status_and_exit($code, $text) {
  // see https://ssl.tiggerswelt.net/wiki/ddns/informationen_fuer_entwickler for return codes. 
  // no idea where this is really specified...  
  http_response_code($code);
  echo $text;
  exit();
}

// -------------------------------

// check if files are writeable
if(!is_writable(DATA_FILE_PATH) || !is_writable(IP_HTML_PAGE)) send_status_and_exit(500, "911 server is unable to write to files");

// load and restore values from data file
$data = json_decode(@file_get_contents(DATA_FILE_PATH), true);
if(!$data) {
 $data = array("current_ip4addr" => null, "current_ip6addr" => null, "last_update_timestamp" => null, "failed_logins" => 0);
} 

// check failed logins and if username or password are not correct
if($data["failed_logins"] > MAX_FAILED_LOGINS_BEFORE_LOCK || empty($_GET["user"]) || $_GET["user"] != USERNAME || empty($_GET["password"]) || $_GET["password"] != PASSWORD) {
  $data["failed_logins"]++;

  // write changes to file
  if(!file_put_contents(DATA_FILE_PATH, json_encode($data))) send_status_and_exit(500, "911 server is unable to write data file");  
  
  send_status_and_exit(401, "badauth");
}

// if login was successful reset failed logins counter (lazily, because other errors may break process before save)
$data["failed_logins"] = 0;

// reset ips?
if(!empty($_GET["reset"])) {
  $_GET["ip4addr"] = "0.0.0.0";
  $_GET["ip6addr"] = "0:0:0:0:0:0:0:0";
}
  
// get ip4 and ip6 address
$ip4addr = filter_var((!empty($_GET["ip4addr"]) ? $_GET["ip4addr"] : null), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
$ip6addr = filter_var((!empty($_GET["ip6addr"]) ? $_GET["ip6addr"] : null), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

// if ip is invalid  
if(!$ip4addr && ipv(4)) send_status_and_exit(500, "911 ip4addr invalid");
if(!$ip6addr && ipv(6)) send_status_and_exit(500, "911 ip6addr invalid");

// if ip didnt change
if($ip4addr == $data["current_ip4addr"] && ipv(4)) send_status_and_exit(200, "nochg " . $ip4addr);
if($ip6addr == $data["current_ip6addr"] && ipv(6)) send_status_and_exit(200, "nochg " . $ip6addr);

// fritzbox sends $_GET["domain"], too. ignore it.

// write changes to data file
$data["last_update_timestamp"] = time();
$data["current_ip4addr"] = $ip4addr;
$data["current_ip6addr"] = $ip6addr;
if(!@file_put_contents(DATA_FILE_PATH, json_encode($data))) send_status_and_exit(500, "911 server is unable to write data file");

// create ip html page
if(IP_HTML_PAGE) {
  $html = @file_get_contents(ROOT . "ip.template.html");
  if($html === false) send_status_and_exit(500, "911 server is unable to read ip.template.html");
  
  if(ipv(4)) $html = str_replace(array("%IP%"), array($ip4addr), $html);
  if(ipv(6)) $html = str_replace(array("%IP%"), array($ip6addr), $html);
  
  if(!@file_put_contents(IP_HTML_PAGE, $html)) send_status_and_exit(500, "911 server is unable to create ip html page");
}

// ready to broadcast the ip somewhere else ...

// @todo


// success!
if(ipv(4)) send_status_and_exit(200, "good " . $ip4addr);
if(ipv(6)) send_status_and_exit(200, "good " . $ip6addr);