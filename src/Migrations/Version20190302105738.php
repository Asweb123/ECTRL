<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190302105738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE companies (company_id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(255) NOT NULL, company_plan VARCHAR(255) NOT NULL, company_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE register_codes (register_id INT AUTO_INCREMENT NOT NULL, code_content VARCHAR(255) NOT NULL, code_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(register_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (role_id INT AUTO_INCREMENT NOT NULL, role_title VARCHAR(255) NOT NULL, role_description VARCHAR(255) NOT NULL, role_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id INT AUTO_INCREMENT NOT NULL, user_email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, phone_ssid VARCHAR(255) NOT NULL, user_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_8D93D649550872C (user_email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE register_codes');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE user');
    }
}
