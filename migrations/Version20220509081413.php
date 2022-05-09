<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509081413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE link_user (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, user_id INT NOT NULL, username VARCHAR(100) NOT NULL, INDEX IDX_3CD444E07975B7E7 (model_id), INDEX IDX_3CD444E0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE link_user ADD CONSTRAINT FK_3CD444E07975B7E7 FOREIGN KEY (model_id) REFERENCES link_model (id)');
        $this->addSql('ALTER TABLE link_user ADD CONSTRAINT FK_3CD444E0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE link_user');
    }
}
