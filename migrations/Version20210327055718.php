<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210327055718 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_point DROP FOREIGN KEY FK_A7AE15B68BAC62AF');
        $this->addSql('DROP INDEX IDX_A7AE15B68BAC62AF ON delivery_point');
        $this->addSql('ALTER TABLE delivery_point ADD city VARCHAR(255) NOT NULL, DROP city_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_point ADD city_id INT NOT NULL, DROP city');
        $this->addSql('ALTER TABLE delivery_point ADD CONSTRAINT FK_A7AE15B68BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_A7AE15B68BAC62AF ON delivery_point (city_id)');
    }
}
