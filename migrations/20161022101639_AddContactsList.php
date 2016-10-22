<?php

use Phpmig\Migration\Migration;

class AddContactsList extends Migration {
    /**
     * Do the migration
     */
    public function up() {
      $sql = "
        CREATE TABLE `report_contact` (
          `uf` varchar(2) NOT NULL,
          `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
          `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
          `phone` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`uf`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
      ";

      $data = "
        INSERT INTO `report_contact`  (`uf`, `name`, `email`, `phone`) VALUES
          ('ac', 'MARJHA BRAGA DE SOUZA', 'marjha@ielac.org.br', '(68) 3212-4271'),
          ('al', 'Eliana Maria de Oliveira Sá', 'eliana.sa@fiea.org.br', '(82) 2121-3085'),
          ('am', 'HUDSON DE SOUZA BATISTA', 'hudson.batista@iel-am.org.br', '(92) 2125-8817 / 98134-0253'),
          ('ba', 'Fabiana Carvalho de Araújo', 'fabianaca@fieb.org.br', '(71) 3343-1293 / 98667-7789'),
          ('ce', 'ADRIANA KELLEN DA SILVEIRA CARVALHO HONORATO', 'akellen@sfiec.org.br', '(85) 3421-6517 / 99270-7210'),
          ('df', 'Neiane da Silva Azevedo Andreato', 'neiane.andreato@sistemafibra.org.br', '(61) 3403-0887 / 3403-0870 / 99912-8324'),
          ('es', 'José Vieira', 'jvieira@findes.org.br', '(27) 3334-5739'),
          ('go', 'LIDIANE MONTEIRO DE ABREU', 'lidiane.iel@sistemafieg.org.br', '(62) 3216-0332 / 99980-2436'),
          ('ma', 'Adeyane Santos Sousa de Oliveira', 'adeyaneoliveira@fiema.org.br', '(98) 3212-1821'),
          ('mg', 'Gabriela Ferreira Franco', 'gffranco@fiemg.com.br', '(31) 3263-4792'),
          ('mt', 'Elieth Shirley Santos Silva', 'educação.executiva@ielmt.com.br', '(65) 3611-1664'),
          ('pa', 'Ivone Izete de Lima Braga', 'ivone.braga@iel-pa.org.br', '(91) 4009-4731 / 98990-8404'),
          ('pb', 'PAULO DE TARSO NOGUEIRA MUNIZ', 'paulo@ielpb.org.br', '(83) 98873-0812 / 99924-9227 / 3099-2020 / 3099-1010'),
          ('pe', 'Gelisa Couto', 'gcouto@ielpe.org.br', '(81) 3334-7014'),
          ('pi', 'Maria Rebelo', 'maria@iel-pi.com.br', '(86) 3218-3000'),
          ('rj', 'Myriam Marques', 'mmcarvalho@firjan.org.br', '(21) 2563-4564'),
          ('rn', 'Kesiane dos Santos Santana', 'kesianesantana@rn.iel.org.br', '(84) 3204-6302'),
          ('ro', 'Sharlene Ribeiro da Silva Picolotto', 'Sharlene.picolotto@fiero.org.br', '(69) 3216-3455 / 98485-3570'),
          ('rr', 'Karina de Almeida Nascimento', 'educacao01@ielrr.org.br', '(95) 98112-2075'),
          ('rs', 'THAISE GRAZIADIO', ' thaise.graziadio@ielrs.org.br', '(51) 3347-8960'),
          ('sc', 'Cristiane Iata', 'cristianeiata@ielsc.org.br', '(48) 8431-6090'),
          ('se', 'Flavia de Oliveira Barbosa', 'Flavia.Barbosa@fies.org.br', '(79) 3218-2913'),
          ('to', 'Marcela Poliana Lima Sousa', 'marcelasousa@sistemafieto.com.br', '(63) 3229-5709');
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
        DROP TABLE `report_contact`
      ";

      $container = $this->getContainer();
      $container['db']->query($sql);
    }
  }
