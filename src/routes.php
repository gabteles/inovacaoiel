<?php

require __DIR__ . '/models/form_submit.php';
require __DIR__ . '/models/question_response.php';

// Routes

$app->get('/', function ($request, $response, $args) {
  return $this->renderer->render($response, 'index.phtml');
});

$app->post('/relatorio', function ($request, $response, $args) {
  $parsedBody = $request->getParsedBody();

  $this->db->beginTransaction();

  $this->logger->debug("Creating FORM: ");
  $form = Model::factory('FormSubmit')->create();
  $form->url = uniqid('');
  $form->created_at = time();
  $form->save();
  $this->logger->debug("Form {$form->id}");

  for ($i = 1 ; $i <= 25; $i++) {
    $fieldName = "q$i";
    $this->logger->debug("Processing $fieldName");

    if (!isset($parsedBody[$fieldName])) {
      $this->logger->debug("Field $fieldName not found");
      $this->db->rollBack();
      return $this->renderer->render($response, 'index.phtml');
    }

    $response_value = $parsedBody[$fieldName];

    $this->logger->debug("Creating Question Response: ");
    $res = Model::factory('QuestionResponse')->create();
    $res->question_number = $i;
    $res->form_submit_id = $form->id;
    $res->response = $response_value;
    $res->save();
    $this->logger->debug("Question Response {$res->id}");
  }

  $this->db->commit();

  return $this->renderer->render($response, 'relatorio.phtml', []);
});
