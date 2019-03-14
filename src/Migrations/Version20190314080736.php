<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190314080736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE requirement (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', certification_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', theme_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', description LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, rank_theme INT NOT NULL, rank_certification INT NOT NULL, INDEX IDX_DB3F5550CB47068A (certification_id), INDEX IDX_DB3F555059027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', audit_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', requirement_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', state SMALLINT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_3E7B0BFBBD29F359 (audit_id), INDEX IDX_3E7B0BFB7B576F77 (requirement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', certification_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, rank_certification INT NOT NULL, INDEX IDX_9775E708CB47068A (certification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE requirement ADD CONSTRAINT FK_DB3F5550CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id)');
        $this->addSql('ALTER TABLE requirement ADD CONSTRAINT FK_DB3F555059027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFBBD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB7B576F77 FOREIGN KEY (requirement_id) REFERENCES requirement (id)');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E708CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id)');
        $this->addSql('ALTER TABLE audit ADD score INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB7B576F77');
        $this->addSql('ALTER TABLE requirement DROP FOREIGN KEY FK_DB3F555059027487');
        $this->addSql('DROP TABLE requirement');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE theme');
        $this->addSql('ALTER TABLE audit DROP score');
    }
}
