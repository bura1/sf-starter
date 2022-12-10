<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210224314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile_picture DROP CONSTRAINT fk_c5659115a76ed395');
        $this->addSql('DROP INDEX uniq_c5659115a76ed395');
        $this->addSql('ALTER TABLE profile_picture DROP user_id');
        $this->addSql('ALTER TABLE "user" ADD phone_number VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD skype VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD telegram VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD facebook VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE profile_picture ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_picture ADD CONSTRAINT fk_c5659115a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_c5659115a76ed395 ON profile_picture (user_id)');
        $this->addSql('ALTER TABLE "user" DROP phone_number');
        $this->addSql('ALTER TABLE "user" DROP skype');
        $this->addSql('ALTER TABLE "user" DROP telegram');
        $this->addSql('ALTER TABLE "user" DROP facebook');
    }
}
