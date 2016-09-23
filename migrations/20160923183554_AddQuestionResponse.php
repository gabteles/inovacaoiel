<?php

use Phpmig\Migration\Migration;

class AddQuestionResponse extends Migration {
    /**
     * Do the migration
     */
    public function up() {
      $sql = "
        CREATE TABLE `question_response` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador da Resposta',
          `question_number` int(10) unsigned NOT NULL COMMENT 'Número da Questão',
          `form_submit_id` int(10) unsigned NOT NULL,
          `response` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`),
          KEY `form_submit_id` (`form_submit_id`),
          CONSTRAINT `fk_form_submit_id` FOREIGN KEY (`form_submit_id`) REFERENCES `form_submit` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
      ";

      $container = $this->getContainer();
      $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down() {
      $sql = "
        DROP TABLE `question_response`
      ";

      $container = $this->getContainer();
      $container['db']->query($sql);
    }
  }
