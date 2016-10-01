<?php

function siteURL() {
  $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
  $protocol = $isHttps ? "https://" : "http://";
  $domainName = $_SERVER['HTTP_HOST'];
  return $protocol . $domainName;
}

function calculateAbsoluteScore($questions, $questionNumber) {
  $response = $questions->where('question_number', $questionNumber)->findOne()->response;
  return round(100 * $response / 6.0);
}

function emailImage($path) {
  $realPath = __DIR__ . '/../' . $path;
  $data = base64_encode(file_get_contents($realPath));
  $mime = mime_content_type($realPath);
  return "data:{$mime};base64,{$data}";
}
