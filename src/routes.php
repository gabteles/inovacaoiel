<?php

require __DIR__ . '/models/form_submit.php';
require __DIR__ . '/models/question_response.php';
require __DIR__ . '/models/report_contact.php';

// Routes

/*----------------------------------------------------------------------------*
 * Index route. Shows form.                                                   *
 *----------------------------------------------------------------------------*/

$app->get('/', function ($request, $response, $args) {
  return $this->renderer->render($response, 'index.phtml', [
    'showShareCounter' => false
  ]);
});

/*----------------------------------------------------------------------------*
/*----------------------------------------------------------------------------*/
$app->get('/responses', function($request, $response, $args) {
  return $this->renderer->render($response, 'responses.phtml');
});

$app->post('/responses', function($request, $response, $args) {
  if ($request->getParsedBody()['password'] != $_ENV['APP_EXPORT_PASSWORD']) {
    return $this->renderer->render($response, 'responses.phtml', [
      'flashText' => 'Senha incorreta'
    ]);
  }

  $sql = "
    SELECT
      CONCAT(
        from_unixtime((SELECT created_at FROM form_submit fs WHERE (fs.id = qr.form_submit_id)), '%d/%m/%Y %h:%i:%s'), \"\t\",
        GROUP_CONCAT(
            CASE
            WHEN question_number IN (1, 3, 4, 5, 6) THEN (
                SELECT response FROM question_options qo
                WHERE qo.question_number = qr.question_number AND  qo.option_number = IF(qr.response = 0 AND qr.question_number = 3, 1, qr.response)
            )
            WHEN question_number IN (7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20) THEN qr.response + 1
            ELSE qr.response
            END
            SEPARATOR \"\t\"
        )
      ) AS responseLine
    FROM question_response qr
    GROUP BY qr.form_submit_id
    ORDER BY question_number;
  ";

  $header = implode("\t", [
    "Data/Hora",
    "Categoria",
    "Localidade de atuação",
    "Porte",
    "Prioridade 1",
    "Prioridade 2",
    "Prioridade 3",
    "Questão 7",
    "Questão 8",
    "Questão 9",
    "Questão 10",
    "Questão 11",
    "Questão 12",
    "Questão 13",
    "Questão 14",
    "Questão 15",
    "Questão 16",
    "Questão 17",
    "Questão 18",
    "Questão 19",
    "Questão 20",
    "Empresa",
    "Nome",
    "Email",
    "Estado",
    "Cidade",
    "Telefone"
  ]) . "\n";

  $responseStmt = $this->db->query($sql);
  $responseLines = $responseStmt->fetchAll();
  $responseBody = $header . implode("\n", array_map(function($r) { return $r['responseLine']; }, $responseLines));

  return $response->withHeader('Content-Type', 'application/force-download')
                  ->withHeader('Content-Type', 'application/octet-stream')
                  ->withHeader('Content-Type', 'application/download')
                  ->withHeader('Content-Description', 'File Transfer')
                  ->withHeader('Content-Transfer-Encoding', 'binary')
                  ->withHeader('Content-Disposition', 'attachment; filename="respostas.csv"')
                  ->withHeader('Expires', '0')
                  ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                  ->withHeader('Pragma', 'public')
                  ->write($responseBody);
});

/*----------------------------------------------------------------------------*
 *----------------------------------------------------------------------------*/
$app->get('/contador', function($request, $response, $args) {
  return $this->renderer->render($response, 'index.phtml', [
    'showShareCounter' => true
  ]);
});
/*----------------------------------------------------------------------------*
 *----------------------------------------------------------------------------*/
$app->post('/contador', function($request, $response, $args) {
  // Get request body
  $parsedBody = $request->getParsedBody();

  $formUrl = processForm(
    $this->db,
    $this->logger,
    $this->renderer,
    $this->mailer,
    $parsedBody
  );

  if ($formUrl) {
    // Redirect to report URL
    ob_end_clean();
    return $this->renderer->render($response, 'contador.phtml', [
      'companySize' => $parsedBody['q3'],
      'referer' => $parsedBody['q22'],
      'state' => $parsedBody['q24'],
      'shareUrl' => siteURL()
    ]);
  } else {
    return $response->withHeader('Location', "/");
  }
});


/*----------------------------------------------------------------------------*
 *----------------------------------------------------------------------------*/
$app->post('/share', function($request, $response, $args) {
  $parsedBody = $request->getParsedBody();

  sendEmailTo(
    $this->renderer,
    $this->mailer,
    $this->logger,
    $parsedBody['email'],
    $parsedBody['name'],
    'Conheça seu nível de maturidade em gestão da inovação',
    'template-email-share.phtml', [
      'name' => $parsedBody['name'],
      'referer' => htmlspecialchars_decode($parsedBody['referer']),
      'shareUrl' => siteURL()
    ]
  );

  return $response->withHeader('Location', '/contador');
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

  for ($i = 3; $i <= 24; $i++) {
    $responses[$i] = $form->responses()->where('question_number', $i)->findOne()->response;
  }

  # UF
  $state = $form->responses()->where('question_number', 24)->findOne()->response;

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

  if (($xAxisValue < 33) || ($yAxisValue < 33)) {
    $generalInnovationScore = "BAIXA";
  } elseif (($xAxisValue < 66) || ($yAxisValue < 66)) {
    $generalInnovationScore = "MÉDIA";
  } else {
    $generalInnovationScore = "ALTA";
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

  //=======================================
  // CONTACT
  //=======================================
  $contacts = Model::factory('ReportContact')
    ->where('uf', $state)
    ->find_many();

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
    'highestInnovativenessScoreIndex' => $highestInnovativenessScoreIndex,

    'contacts' => $contacts,

    'companySize' => $responses[3],
    'referer' => $responses[22],
    'state' => $responses[24]
  ]);
});

/*----------------------------------------------------------------------------*
 * Form submission route.                                                     *
 *----------------------------------------------------------------------------*/
$app->post('/relatorio', function ($request, $response, $args) {
  // Get request body
  $parsedBody = $request->getParsedBody();

  $formUrl = processForm(
    $this->db,
    $this->logger,
    $this->renderer,
    $this->mailer,
    $parsedBody
  );

  if ($formUrl) {
    // Redirect to report URL
    return $response
      ->withStatus(302)
      ->withHeader('Location', "/relatorio/{$formUrl}");
  } else {
    return $response->withHeader('Location', "/");
  }
});


function processForm($db, $logger, $renderer, $mailer, $parsedBody) {
  // Initialize transaction
  $db->beginTransaction();

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
      $db->rollBack();
      $errorArgs = [];
      $logger->error("Erro ao processar questão $i");
      return false;
    }

    // Create response to this question
    $res = Model::factory('QuestionResponse')->create();
    $res->question_number = $i;
    $res->form_submit_id = $form->id;
    $res->response = $parsedBody[$fieldName];
    $res->save();
  }

  // Commit
  $db->commit();

  // Send user email
  $userName = $parsedBody['q22'];
  $userEmail = $parsedBody['q23'];
  $state = $parsedBody['q24'];
  $basePath = siteURL();

  sendEmailTo(
    $renderer,
    $mailer,
    $logger,
    $userEmail,
    $userName,
    'Conheça seu nível de maturidade em gestão da inovação',
    'template-email.phtml', [
      'name' => $userName,
      'formUrl' => "{$basePath}/relatorio/{$form->url}"
    ]
  );

  // Send consultor email
  $contacts = Model::factory('ReportContact')
    ->where('uf', $state)
    ->find_many();

  if ($contacts && $contacts != null && !empty($contacts)) {
    $userCompany = $parsedBody['q21'];
    $userCity = $parsedBody['q25'];
    $userPhone = $parsedBody['q26'];

    foreach ($contacts as $contact) {
      sendEmailTo(
        $renderer,
        $mailer,
        $logger,
        $contact->email,
        $contact->name,
        'Novo relatório de capacidade de gestão da inovação',
        'template-email-interno.phtml', [
          'name' => $contact->name,
          'state' => $state,
          'userName' => $userName,
          'userEmail' => $userEmail,
          'userCompany' => $userCompany,
          'userCity' => $userCity,
          'userPhone' => $userPhone,
          'formUrl' => "{$basePath}/relatorio/{$form->url}"
        ]
      );
    }
  }

  return $form->url;
}
