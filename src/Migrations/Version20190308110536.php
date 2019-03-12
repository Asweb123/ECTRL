<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190308110536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE certification (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification_company (certification_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', company_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_1A5C0779CB47068A (certification_id), INDEX IDX_1A5C0779979B1AD6 (company_id), PRIMARY KEY(certification_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE register_code (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', company_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', role_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', code_content VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, max_nb_of_users INT NOT NULL, INDEX IDX_A3722038979B1AD6 (company_id), INDEX IDX_A3722038D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', company_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', role_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', register_code_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, user_enable TINYINT(1) NOT NULL, user_aware TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649979B1AD6 (company_id), INDEX IDX_8D93D649D60322AC (role_id), INDEX IDX_8D93D64967B64509 (register_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE certification_company ADD CONSTRAINT FK_1A5C0779CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE certification_company ADD CONSTRAINT FK_1A5C0779979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE register_code ADD CONSTRAINT FK_A3722038979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE register_code ADD CONSTRAINT FK_A3722038D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64967B64509 FOREIGN KEY (register_code_id) REFERENCES register_code (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE certification_company DROP FOREIGN KEY FK_1A5C0779CB47068A');
        $this->addSql('ALTER TABLE certification_company DROP FOREIGN KEY FK_1A5C0779979B1AD6');
        $this->addSql('ALTER TABLE register_code DROP FOREIGN KEY FK_A3722038979B1AD6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64967B64509');
        $this->addSql('ALTER TABLE register_code DROP FOREIGN KEY FK_A3722038D60322AC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE certification_company');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE register_code');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
    }
}
