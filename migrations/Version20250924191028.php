<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250924191028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paper ADD printing_method_id INT NOT NULL');
        $this->addSql('ALTER TABLE paper ADD CONSTRAINT FK_4E1A6016203BFB37 FOREIGN KEY (printing_method_id) REFERENCES printing_method (id)');
        $this->addSql('CREATE INDEX IDX_4E1A6016203BFB37 ON paper (printing_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paper DROP FOREIGN KEY FK_4E1A6016203BFB37');
        $this->addSql('DROP INDEX IDX_4E1A6016203BFB37 ON paper');
        $this->addSql('ALTER TABLE paper DROP printing_method_id');
    }
}
