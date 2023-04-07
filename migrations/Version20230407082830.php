<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407082830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise ADD description VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE task CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE last_name last_name VARCHAR(30) NOT NULL, CHANGE first_name first_name VARCHAR(30) NOT NULL, CHANGE email email VARCHAR(50) NOT NULL, CHANGE phone phone VARCHAR(15) NOT NULL, CHANGE adress adress VARCHAR(150) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise DROP description');
        $this->addSql('ALTER TABLE task CHANGE description description VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE phone phone VARCHAR(255) NOT NULL, CHANGE adress adress VARCHAR(255) NOT NULL');
    }
}
