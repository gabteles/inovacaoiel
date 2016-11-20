<?php

use Phpmig\Migration\Migration;

class UpdateContactsList extends Migration {
    /**
     * Do the migration
     */
    public function up() {
      $sql = "
        ALTER TABLE `report_contact` DROP PRIMARY KEY;
      ";

      $data = "
        INSERT INTO `report_contact`  (`uf`, `name`, `email`, `phone`) VALUES
          ('ms', 'Hugo Bittar', 'hugo@ms.iel.org.br', '(67) 3044-2104 / 99627-7832'),
          ('ms', 'Luis Alberto Borba Pereira', 'luisalberto@ms.iel.org.br', '(67) 3044-2103');
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
        DELETE FROM `report_contact` WHERE uf='ms';
      ";

      $container = $this->getContainer();
      $container['db']->query($sql);
    }
  }
