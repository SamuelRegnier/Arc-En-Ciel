<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605084320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_matter (task_id INT NOT NULL, matter_id INT NOT NULL, INDEX IDX_8B771FBC8DB60186 (task_id), INDEX IDX_8B771FBCD614E59F (matter_id), PRIMARY KEY(task_id, matter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_matter ADD CONSTRAINT FK_8B771FBC8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_matter ADD CONSTRAINT FK_8B771FBCD614E59F FOREIGN KEY (matter_id) REFERENCES matter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise ADD matter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51CD614E59F FOREIGN KEY (matter_id) REFERENCES matter (id)');
        $this->addSql('CREATE INDEX IDX_AEDAD51CD614E59F ON exercise (matter_id)');
        $this->addSql('ALTER TABLE task ADD level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB255FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('CREATE INDEX IDX_527EDB255FB14BA7 ON task (level_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_matter DROP FOREIGN KEY FK_8B771FBC8DB60186');
        $this->addSql('ALTER TABLE task_matter DROP FOREIGN KEY FK_8B771FBCD614E59F');
        $this->addSql('DROP TABLE task_matter');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB255FB14BA7');
        $this->addSql('DROP INDEX IDX_527EDB255FB14BA7 ON task');
        $this->addSql('ALTER TABLE task DROP level_id');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51CD614E59F');
        $this->addSql('DROP INDEX IDX_AEDAD51CD614E59F ON exercise');
        $this->addSql('ALTER TABLE exercise DROP matter_id');
    }
}
