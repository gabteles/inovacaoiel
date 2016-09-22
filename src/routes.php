<?php
// Routes

$app->get('/', function ($request, $response, $args) {
  return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/relatorio', function ($request, $response, $args) {
  return $this->renderer->render($response, 'relatorio.phtml', $args);
});
