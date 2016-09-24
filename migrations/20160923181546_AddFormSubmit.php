<?php

use Phpmig\Migration\Migration;

class AddFormSubmit extends Migration {
  /**
   * Do the migration
   */
  public function up() {
    $sql = "
      CREATE TABLE `form_submit` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `created_at` int(10) unsigned NOT NULL,
        PRIMARY KEY (`id`)
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
      DROP TABLE `form_submit`
    ";

    $container = $this->getContainer();
    $container['db']->query($sql);
  }
}
