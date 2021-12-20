<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211203135333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appareil (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, editeur_id INT DEFAULT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) DEFAULT NULL, ns VARCHAR(255) DEFAULT NULL, mdp VARCHAR(255) DEFAULT NULL, prblm VARCHAR(255) DEFAULT NULL, chargeur TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_456A601A19EB6921 (client_id), INDEX IDX_456A601A3375BD21 (editeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, date DATETIME DEFAULT NULL, personne VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, cp INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editeur (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, etat_id INT DEFAULT NULL, INDEX IDX_5A747EFA76ED395 (user_id), INDEX IDX_5A747EFD5E86FF (etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appareil ADD CONSTRAINT FK_456A601A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE appareil ADD CONSTRAINT FK_456A601A3375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id)');
        $this->addSql('ALTER TABLE editeur ADD CONSTRAINT FK_5A747EFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE editeur ADD CONSTRAINT FK_5A747EFD5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appareil DROP FOREIGN KEY FK_456A601A19EB6921');
        $this->addSql('ALTER TABLE appareil DROP FOREIGN KEY FK_456A601A3375BD21');
        $this->addSql('ALTER TABLE editeur DROP FOREIGN KEY FK_5A747EFD5E86FF');
        $this->addSql('ALTER TABLE editeur DROP FOREIGN KEY FK_5A747EFA76ED395');
        $this->addSql('DROP TABLE appareil');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE editeur');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE user');
    }
}
