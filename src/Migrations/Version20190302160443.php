<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190302160443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(255) NOT NULL, company_plan VARCHAR(255) NOT NULL, company_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE register_code (id INT AUTO_INCREMENT NOT NULL, register_code_company_id INT NOT NULL, register_code_role_id INT NOT NULL, code_content VARCHAR(255) NOT NULL, code_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_A3722038D3B3481B (register_code_company_id), INDEX IDX_A37220384AB3FB09 (register_code_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, role_title VARCHAR(255) NOT NULL, role_description VARCHAR(255) NOT NULL, role_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_company_id INT NOT NULL, user_role_id INT NOT NULL, user_email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, phone_ssid VARCHAR(255) NOT NULL, user_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user_enable TINYINT(1) NOT NULL, user_aware TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649550872C (user_email), INDEX IDX_8D93D64930FCDC3A (user_company_id), INDEX IDX_8D93D6498E0E3CA6 (user_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE register_code ADD CONSTRAINT FK_A3722038D3B3481B FOREIGN KEY (register_code_company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE register_code ADD CONSTRAINT FK_A37220384AB3FB09 FOREIGN KEY (register_code_role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64930FCDC3A FOREIGN KEY (user_company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE register_code DROP FOREIGN KEY FK_A3722038D3B3481B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64930FCDC3A');
        $this->addSql('ALTER TABLE register_code DROP FOREIGN KEY FK_A37220384AB3FB09');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498E0E3CA6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE register_code');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
    }
}
