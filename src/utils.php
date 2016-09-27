<?php

function siteURL() {
  $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
  $protocol = $isHttps ? "https://" : "http://";
  $domainName = $_SERVER['HTTP_HOST'];
  return $protocol . $domainName;
}
