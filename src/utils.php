<?php

function siteURL() {
  $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
  $protocol = $isHttps ? "https://" : "http://";
  $domainName = $_SERVER['HTTP_HOST'];
  return $protocol . $domainName;
}

function calculateAbsoluteScore($response) {
  return round(100 * $response / 6.0);
}

function sendEmailTo(
    $renderer, $mailerBuilder, $logger,
    $toEmail, $toName, $subject, $bodyTemplate, $bodyTemplateVariables) {

  $emailBody = $renderer->fetch($bodyTemplate, $bodyTemplateVariables);

  $mailer = call_user_func($mailerBuilder);
  $mailer->addEmbeddedImage(__DIR__ . '/../public/img/iel/logo-email.png', 'logo-email', 'logo-email.png');
  $mailer->addEmbeddedImage(__DIR__ . '/../public/img/iel/logo-iel.png', 'logo-iel', 'logo-iel.png');
  $mailer->addAddress($toEmail, $toName);
  $mailer->Subject = $subject;
  $mailer->Body = $emailBody;

  $logger->debug("Enviando email para: {$toEmail} ({$toName})");
  if (!$mailer->send()) {
    $logger->error("Erro ao enviar email: {$mailer->ErrorInfo}");
  }
}
