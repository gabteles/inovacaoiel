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

  $form = Model::factory('FormSubmit')->create();
  $form->url = uniqid('');
  $form->created_at = time();
  $form->save();

  for ($i = 1 ; $i < 2; $i++) {
    $fieldName = "q$i";

    if (!isset($parsedBody[$fieldName])) {
      $this->db->rollBack();
      return $this->renderer->render($response, 'index.phtml');
    }

    $response_value = $parsedBody[$fieldName];

    $res = Model::factory('QuestionResponse')->create();
    $res->question_number = $i;
    $res->form_submit_id = $form->id;
    $res->response = $response_value;
    $res->save();
  }

  $this->db->commit();

  return $this->renderer->render($response, 'relatorio.phtml', []);
});
