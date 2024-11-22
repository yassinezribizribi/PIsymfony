<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122204608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, titre_ann VARCHAR(255) NOT NULL, contenu_ann VARCHAR(255) NOT NULL, date_publication DATE NOT NULL, INDEX IDX_F65593E5FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, titre_chap VARCHAR(255) NOT NULL, contenu_chap VARCHAR(255) NOT NULL, INDEX IDX_8C62B0257ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, titre_cours VARCHAR(255) NOT NULL, description_cours VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, titre_evalution VARCHAR(255) NOT NULL, note_maximale VARCHAR(255) NOT NULL, INDEX IDX_1323A5757ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, organisateurs_id INT DEFAULT NULL, nom_even VARCHAR(255) NOT NULL, description_even VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, lieu VARCHAR(255) NOT NULL, INDEX IDX_B26681EBAC09095 (organisateurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT DEFAULT NULL, destinataire_id INT DEFAULT NULL, contenu_message VARCHAR(255) NOT NULL, date_envoi DATE NOT NULL, INDEX IDX_B6BD307F10335F61 (expediteur_id), INDEX IDX_B6BD307FA4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, evenement_id INT DEFAULT NULL, date_inscri DATE NOT NULL, INDEX IDX_AB55E24FFB88E14F (utilisateur_id), INDEX IDX_AB55E24FFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom_role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom_user VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, pwd VARCHAR(255) NOT NULL, date_inscri DATE NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B0257ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5757ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBAC09095 FOREIGN KEY (organisateurs_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F10335F61 FOREIGN KEY (expediteur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5FB88E14F');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B0257ECF78B0');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5757ECF78B0');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBAC09095');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F10335F61');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA4F84F6E');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFB88E14F');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFD02F13');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
