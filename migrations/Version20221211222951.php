<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211222951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE badge_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE static_page_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE badge (id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(10) NOT NULL, text_color VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE badge_user (badge_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(badge_id, user_id))');
        $this->addSql('CREATE INDEX IDX_299D3A50F7A2C2FC ON badge_user (badge_id)');
        $this->addSql('CREATE INDEX IDX_299D3A50A76ED395 ON badge_user (user_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, product_type_id INT NOT NULL, user_id INT NOT NULL, product_category_id INT NOT NULL, name VARCHAR(255) NOT NULL, link TEXT DEFAULT NULL, subscribers INT DEFAULT NULL, income_month INT DEFAULT NULL, expense_month INT DEFAULT NULL, description TEXT DEFAULT NULL, price INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, currency VARCHAR(5) NOT NULL, status VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04AD14959723 ON product (product_type_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA76ED395 ON product (user_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADBE6903FD ON product (product_category_id)');
        $this->addSql('COMMENT ON COLUMN product.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE product_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE profile_picture (id INT NOT NULL, name VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL, size INT NOT NULL, mime_type VARCHAR(255) NOT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN profile_picture.uploaded_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE static_page (id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN static_page.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN static_page.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, profile_picture_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_verified BOOLEAN NOT NULL, username VARCHAR(20) NOT NULL, phone_number VARCHAR(15) DEFAULT NULL, skype VARCHAR(255) DEFAULT NULL, telegram VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649292E8AE2 ON "user" (profile_picture_id)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE badge_user ADD CONSTRAINT FK_299D3A50F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE badge_user ADD CONSTRAINT FK_299D3A50A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649292E8AE2 FOREIGN KEY (profile_picture_id) REFERENCES profile_picture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE badge_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_picture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE static_page_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE badge_user DROP CONSTRAINT FK_299D3A50F7A2C2FC');
        $this->addSql('ALTER TABLE badge_user DROP CONSTRAINT FK_299D3A50A76ED395');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD14959723');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADBE6903FD');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649292E8AE2');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE badge_user');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP TABLE profile_picture');
        $this->addSql('DROP TABLE static_page');
        $this->addSql('DROP TABLE "user"');
    }
}
