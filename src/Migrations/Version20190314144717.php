<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190314144717 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE result (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', audit_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', requirement_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', state SMALLINT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_136AC113BD29F359 (audit_id), INDEX IDX_136AC1137B576F77 (requirement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113BD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1137B576F77 FOREIGN KEY (requirement_id) REFERENCES requirement (id)');
        $this->addSql('DROP TABLE response');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE response (id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', audit_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', requirement_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', state SMALLINT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_3E7B0BFB7B576F77 (requirement_id), INDEX IDX_3E7B0BFBBD29F359 (audit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB7B576F77 FOREIGN KEY (requirement_id) REFERENCES requirement (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFBBD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('DROP TABLE result');
    }
}
