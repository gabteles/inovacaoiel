<?php

use Phpmig\Migration\Migration;

class AddIdToContacts extends Migration {
    /**
     * Do the migration
     */
    public function up() {
      $sql = "ALTER TABLE report_contact ADD id int not null auto_increment, ADD PRIMARY KEY (id);";

      $container = $this->getContainer();
      $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down() {
      $sql = "
        ALTER TABLE report_contact DROP COLUMN id;
      ";

      $container = $this->getContainer();
      $container['db']->query($sql);
    }
  }
