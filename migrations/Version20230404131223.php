<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404131223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classroom ADD picture LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD picture LONGBLOB DEFAULT NULL, CHANGE pai pai VARCHAR(30) NOT NULL, CHANGE description_pai description_pai VARCHAR(1000) NOT NULL, CHANGE allergy allergy VARCHAR(30) NOT NULL, CHANGE description_allergy description_allergy VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE user ADD picture LONGBLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classroom DROP picture');
        $this->addSql('ALTER TABLE student DROP picture, CHANGE pai pai VARCHAR(30) DEFAULT NULL, CHANGE description_pai description_pai VARCHAR(1000) DEFAULT NULL, CHANGE allergy allergy VARCHAR(30) DEFAULT NULL, CHANGE description_allergy description_allergy VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP picture');
    }
}
