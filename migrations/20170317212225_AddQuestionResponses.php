<?php

use Phpmig\Migration\Migration;

class AddQuestionResponses extends Migration {
    /**
     * Do the migration
     */
    public function up() {
      $sql = "
        CREATE TABLE `question_options` (
          `question_number` int(10) unsigned NOT NULL,
          `option_number` int(10) unsigned NOT NULL,
          `response` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
        );
      ";

      $data = "
        INSERT INTO `question_options`  (`question_number`, `option_number`, `response`) VALUES
          (1, 0, 'Água, gás e saneamento'),
          (1, 1, 'Indústrias em geral'),
          (1, 2, 'Setor de saúde'),
          (1, 3, 'Alimentos processados'),
          (1, 4, 'Materiais diversos'),
          (1, 5, 'Setor de seguros'),
          (1, 6, 'Bancos'),
          (1, 7, 'Mineração'),
          (1, 8, 'Setor de transporte'),
          (1, 9, 'Carnes e derivados'),
          (1, 10, 'Negócios de lazer e eventos'),
          (1, 11, 'Siderurgia e metalurgia'),
          (1, 12, 'Construção civil e intermediação'),
          (1, 13, 'Papel e madeira'),
          (1, 14, 'Tecnologia da informação'),
          (1, 15, 'Construção pesada e engenharia'),
          (1, 16, 'Petróleo, gás e combustíveis'),
          (1, 17, 'Telecomunicações'),
          (1, 18, 'Energia elétrica'),
          (1, 19, 'Química e petroquímica'),
          (1, 20, 'Têxteis'),
          (1, 21, 'Equipamentos, máquinas e peças'),
          (1, 22, 'Roupas, calçados e acessórios'),
          (1, 23, 'Utilidades domésticas'),
          (1, 24, 'Holdings'),
          (1, 25, 'Serviços diversos'),
          (1, 26, 'Varejo'),
          (1, 27, 'Hotelaria e Restaurantes'),
          (1, 28, 'Imóveis comerciais e shoppings'),
          (1, 29, 'Serviços financeiros'),
          (1, 30, 'Indústrias de alimentos'),
          (1, 31, 'Educação'),

          (3, 1, 'Faturamento anual até R$ 60 mil'),
          (3, 2, 'Faturamento anual até R$ 360 mil'),
          (3, 3, 'Faturamento anual entre R$ 360 mil e R$ 3,6 milhões'),
          (3, 4, 'Faturamento anual entre R$ 3,6 milhões e R$ 16 milhões'),
          (3, 5, 'Faturamento anual entre R$ 16 milhões e R$ 90 milhões'),
          (3, 6, 'Faturamento anual entre R$ 90 milhões e R$ 300 milhões'),
          (3, 7, 'Faturamento maior do que R$ 300 milhões'),

          (4, 0, 'Aumento da gama de bens e serviços'),
          (4, 1, 'Tempo reduzido de resposta às necessidades dos consumidores'),
          (4, 2, 'Melhoria da comunicação e da interação entre as diferentes atividades de negócios'),
          (4, 3, 'Aumento da visibilidade ou da exposição dos produtos'),

          (5, 0, 'Desenvolvimento de produtos não agressivos ao meio ambiente '),
          (5, 1, 'Entrada em novos mercados'),
          (5, 2, 'Melhoria das condições de trabalho'),
          (5, 3, 'Redução dos custos de concepção dos produtos'),

          (6, 0, 'Aumento ou manutenção da parcela de mercado (market-share)'),
          (6, 1, 'Melhoria da gestão de informações'),
          (6, 2, 'Aumento da visibilidade ou da exposição dos produtos'),
          (6, 3, 'Aumento da flexibilidade de produção ou provisão de serviços');
      ";

      $container = $this->getContainer();
      $container['db']->query($sql);
      $container['db']->query($data);
    }

    /**
     * Undo the migration
     */
    public function down() {
      $sql = "
        DROP TABLE `question_options`;
      ";

      $container = $this->getContainer();
      $container['db']->query($sql);
    }
  }
