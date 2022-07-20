<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220716072256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jawaban (id_jawaban INT AUTO_INCREMENT NOT NULL, pertanyaan_id INT DEFAULT NULL, user_name VARCHAR(100) DEFAULT NULL, jawab TEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, vote INT DEFAULT NULL, approve_status INT DEFAULT NULL, INDEX jawaban_ibfk_1 (pertanyaan_id), PRIMARY KEY(id_jawaban)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), INDEX IDX_75EA56E0FB7336F0 (queue_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pertanyaan (id_pertanyaan INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(100) DEFAULT NULL, judul_tanya VARCHAR(255) DEFAULT NULL, tanya TEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id_pertanyaan)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id_tag INT AUTO_INCREMENT NOT NULL, nama_tag VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id_tag)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_pertanyaan (id_tag_pertanyaan INT AUTO_INCREMENT NOT NULL, tag_id INT DEFAULT NULL, pertanyaan_id INT DEFAULT NULL, INDEX id_pertanyaan (pertanyaan_id), INDEX id_tag (tag_id), PRIMARY KEY(id_tag_pertanyaan)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jawaban ADD CONSTRAINT FK_24D5B5648E174348 FOREIGN KEY (pertanyaan_id) REFERENCES pertanyaan (id_pertanyaan) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_pertanyaan ADD CONSTRAINT FK_3C1A84EABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id_tag)');
        $this->addSql('ALTER TABLE tag_pertanyaan ADD CONSTRAINT FK_3C1A84EA8E174348 FOREIGN KEY (pertanyaan_id) REFERENCES pertanyaan (id_pertanyaan)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jawaban DROP FOREIGN KEY FK_24D5B5648E174348');
        $this->addSql('ALTER TABLE tag_pertanyaan DROP FOREIGN KEY FK_3C1A84EA8E174348');
        $this->addSql('ALTER TABLE tag_pertanyaan DROP FOREIGN KEY FK_3C1A84EABAD26311');
        $this->addSql('DROP TABLE jawaban');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE pertanyaan');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_pertanyaan');
    }
}
