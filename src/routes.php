<?php

require __DIR__ . '/models/form_submit.php';
require __DIR__ . '/models/question_response.php';

// Routes

/*----------------------------------------------------------------------------*
 * Index route. Shows form.                                                   *
 *----------------------------------------------------------------------------*/

$app->get('/', function ($request, $response, $args) {
  return $this->renderer->render($response, 'index.phtml');
});

/*----------------------------------------------------------------------------*
 * Report route. Shows report to an form submission.                          *
 *----------------------------------------------------------------------------*/
$app->get('/relatorio[/{url}]', function($request, $response, $args) {
  // Error if we haven't any ID
  if (!isset($args['url'])) {
    return $this->renderer->render($response, 'relatorio-nao-encontrado.phtml', []);
  }

  // Initialize DB
  $this->db;

  // Seach DB for new submits
  $form = Model::factory('FormSubmit')
    ->where('url', $args['url'])
    ->find_one();

  // Error if we found nothing
  if ($form == null) {
    return $this->renderer->render($response, 'relatorio-nao-encontrado.phtml', []);
  }

  // Render report
  return $this->renderer->render($response, 'relatorio.phtml', []);
});


/*----------------------------------------------------------------------------*
 * Form submission route.                                                     *
 *----------------------------------------------------------------------------*/
$app->post('/relatorio', function ($request, $response, $args) {
  // Get request body
  $parsedBody = $request->getParsedBody();

  // Initialize transaction
  $this->db->beginTransaction();

  // Create form entity
  $form = Model::factory('FormSubmit')->create();
  $form->url = uniqid('');
  $form->created_at = time();
  $form->save();

  // For each response
  for ($i = 1 ; $i <= 25; $i++) {
    $fieldName = "q$i";

    // Error if the response
    if (!isset($parsedBody[$fieldName])) {
      $this->db->rollBack();
      $errorArgs = [];
      return $this->renderer->render($response, 'index.phtml', $errorArgs);
    }

    // Create response to this question
    $res = Model::factory('QuestionResponse')->create();
    $res->question_number = $i;
    $res->form_submit_id = $form->id;
    $res->response = $parsedBody[$fieldName];
    $res->save();
  }

  // Commit
  $this->db->commit();

  // Send email
  $name = $parsedBody['q21'];
  $email = $parsedBody['q22'];
  $basePath = siteURL();
  $emailBody = $this->renderer->fetch('template-email.phtml', [
    'name' => $name,
    'formUrl' => "{$basePath}/relatorio/{$form->url}"
  ]);

  $mailer = $this->mailer;
  $mailer->addAddress($email, $name);
  $mailer->Subject = 'Conheça seu nível de maturidade em gestão da inovação';
  $mailer->Body = $emailBody;

  if (!$mailer->send()) {
    $this->logger->error("Erro ao enviar email: {$mailer->ErrorInfo}");
  }

  // Redirect to report URL
  return $response
    ->withStatus(302)
    ->withHeader('Location', "/relatorio/{$form->url}");
});
