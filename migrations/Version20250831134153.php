<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831134153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quotation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, paper_id INT NOT NULL, printing_method_id INT NOT NULL, lamination_id INT DEFAULT NULL, corners_id INT DEFAULT NULL, folding_id INT DEFAULT NULL, hot_foil_id INT DEFAULT NULL, client_name VARCHAR(255) NOT NULL, quantity INT NOT NULL, total_price NUMERIC(10, 3) NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', notes LONGTEXT DEFAULT NULL, INDEX IDX_474A8DB9A76ED395 (user_id), INDEX IDX_474A8DB9E6758861 (paper_id), INDEX IDX_474A8DB9203BFB37 (printing_method_id), INDEX IDX_474A8DB978DFDDF2 (lamination_id), INDEX IDX_474A8DB954DD0633 (corners_id), INDEX IDX_474A8DB9721CCB10 (folding_id), INDEX IDX_474A8DB9ED236F2A (hot_foil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB9E6758861 FOREIGN KEY (paper_id) REFERENCES paper (id)');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB9203BFB37 FOREIGN KEY (printing_method_id) REFERENCES printing_method (id)');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB978DFDDF2 FOREIGN KEY (lamination_id) REFERENCES lamination (id)');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB954DD0633 FOREIGN KEY (corners_id) REFERENCES corners (id)');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB9721CCB10 FOREIGN KEY (folding_id) REFERENCES folding (id)');
        $this->addSql('ALTER TABLE quotation ADD CONSTRAINT FK_474A8DB9ED236F2A FOREIGN KEY (hot_foil_id) REFERENCES hot_foil (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quotation DROP FOREIGN KEY FK_474A8DB9A76ED395');
        $this->addSql('ALTER TABLE quotation DROP FOREIGN KEY FK_474A8DB9E6758861');
        $this->addSql('ALTER TABLE quotation DROP FOREIGN KEY FK_474A8DB9203BFB37');
        $this->addSql('ALTER TABLE quotation DROP FOREIGN KEY FK_474A8DB978DFDDF2');
        $this->addSql('ALTER TABLE quotation DROP FOREIGN KEY FK_474A8DB954DD0633');
        $this->addSql('ALTER TABLE quotation DROP FOREIGN KEY FK_474A8DB9721CCB10');
        $this->addSql('ALTER TABLE quotation DROP FOREIGN KEY FK_474A8DB9ED236F2A');
        $this->addSql('DROP TABLE quotation');
    }
}
