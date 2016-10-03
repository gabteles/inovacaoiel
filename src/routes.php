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

  $responses = [];

  for ($i = 4; $i <= 20; $i++) {
    $responses[$i] = $form->responses()->where('question_number', $i)->findOne()->response;
  }

  //=======================================
  // SECTION A
  //=======================================
  $xAxisSum = 0;
  $yAxisSum = 0;

  for ($i = 7; $i <= 16; $i++) {
    $yAxisSum += $responses[$i];
  }

  for ($i = 17; $i <= 20; $i++) {
    $xAxisSum += $responses[$i];
  }

  $xAxisValue = (100 * $xAxisSum / 24.0);
  $yAxisValue = (100 * $yAxisSum / 60.0);

  if (($xAxisValue < 20) || ($yAxisValue < 20)) {
    $generalInnovationScore = 1;
  } elseif (($xAxisValue < 40) || ($yAxisValue < 40)) {
    $generalInnovationScore = 2;
  } elseif (($xAxisValue < 60) || ($yAxisValue < 60)) {
    $generalInnovationScore = 3;
  } elseif (($xAxisValue < 80) || ($yAxisValue < 80)) {
    $generalInnovationScore = 4;
  } else {
    $generalInnovationScore = 5;
  }


  //=======================================
  // SECTION B
  //=======================================

  $priorityMatrix = [
    [[1,0,0,0], [0,1,1,0], [0,0,1,0], [0,0,0,1]],
    [[1,0,0,0], [1,0,0,1], [0,0,1,1], [0,1,1,0]],
    [[1,0,0,1], [0,1,1,0], [0,0,0,1], [0,1,1,0]]
  ];

  $innovationPriorities = [0,0,0,0];

  for ($i = 4; $i <= 6; $i++) {
    $res = $responses[$i];

    for ($j = 0 ; $j < 4; $j++) {
      $innovationPriorities[$j] += $priorityMatrix[$i - 4][$res][$j];
    }
  }

  $innovationPriorityIndex = 0;
  for ($i = 0; $i < 4; $i++) {
    if ($innovationPriorities[$i] > $innovationPriorities[$innovationPriorityIndex]) {
      $innovationPriorityIndex = $i;
    }
  }


  $innovationTypesScores = [];
  $innovationTypesHighestScore = 0;
  $innovationTypesHighestScoreIndex = 0;

  for ($i = 17; $i <= 20; $i++) {
    $j = $i - 17;
    $innovationTypesScores[$j] = calculateAbsoluteScore($responses[$i]);

    if ($innovationTypesScores[$j] > $innovationTypesHighestScore) {
      $innovationTypesHighestScore = $innovationTypesScores[$j];
      $innovationTypesHighestScoreIndex = $j;
    }
  }

  //=======================================
  // SECTION C
  //=======================================

  $innovativenessScores = [];
  $highestInnovativenessScore = 0;
  $highestInnovativenessScoreIndex = 0;

  for ($i = 7; $i <= 16; $i++) {
    $j = $i - 7;
    $innovativenessScores[$j] = calculateAbsoluteScore($responses[$i]);

    if ($innovativenessScores[$j] > $highestInnovativenessScore) {
      $highestInnovativenessScore = $innovativenessScores[$j];
      $highestInnovativenessScoreIndex = $j;
    }
  }

  // Render report
  return $this->renderer->render($response, 'relatorio.phtml', [
    'generalInnovationScore' => $generalInnovationScore,
    'xAxisValue' => $xAxisValue,
    'yAxisValue' => $yAxisValue,

    'innovationPriorityIndex' => $innovationPriorityIndex,

    'innovationTypesScores' => $innovationTypesScores,
    'innovationTypesHighestScore' => $innovationTypesHighestScore,
    'innovationTypesHighestScoreIndex' => $innovationTypesHighestScoreIndex,

    'innovativenessScores' => $innovativenessScores,
    'highestInnovativenessScoreIndex' => $highestInnovativenessScoreIndex
  ]);
});


/*----------------------------------------------------------------------------*
 * Form submission route.                                                     *
 *----------------------------------------------------------------------------*/
$app->get('/relatorio-tmp', function ($request, $response, $args) {
  $basePath = siteURL();

  return $this->renderer->render($response, 'template-email.phtml', [
    'name' => "João da Silva",
    'formUrl' => "{$basePath}/relatorio/1234567890"
  ]);
});
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
  for ($i = 1 ; $i <= 26; $i++) {
    $fieldName = "q$i";

    // Error if the response
    if (!isset($parsedBody[$fieldName])) {
      $this->db->rollBack();
      $errorArgs = [];
      $this->logger->error("Erro ao processar questão $i");
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
  $name = $parsedBody['q22'];
  $email = $parsedBody['q23'];
  $basePath = siteURL();
  $emailBody = $this->renderer->fetch('template-email.phtml', [
    'name' => $name,
    'formUrl' => "{$basePath}/relatorio/{$form->url}"
  ]);

  $mailer = $this->mailer;
  $mailer->addAddress($email, $name);
  $mailer->Subject = 'Conheça seu nível de maturidade em gestão da inovação';
  $mailer->Body = $emailBody;

  $this->logger->debug("Enviando email para email: {$email} ({$name})");
  if (!$mailer->send()) {
    $this->logger->error("Erro ao enviar email: {$mailer->ErrorInfo}");
  }

  // Redirect to report URL
  return $response
    ->withStatus(302)
    ->withHeader('Location', "/relatorio/{$form->url}");
});
