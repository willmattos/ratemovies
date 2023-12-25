<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231225210211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, critica_id INT DEFAULT NULL, comentario LONGTEXT DEFAULT NULL, fecha DATETIME NOT NULL, INDEX IDX_4B91E702A76ED395 (user_id), INDEX IDX_4B91E7023B7FACB5 (critica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenido (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, alias VARCHAR(255) DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, estreno DATE NOT NULL, poster VARCHAR(255) DEFAULT NULL, portada VARCHAR(255) DEFAULT NULL, serie INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE critica (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contenido_id INT DEFAULT NULL, comentario LONGTEXT DEFAULT NULL, fecha DATETIME NOT NULL, INDEX IDX_22C49E3EA76ED395 (user_id), INDEX IDX_22C49E3E7FDA517C (contenido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorito (id INT AUTO_INCREMENT NOT NULL, contenido_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_881067C77FDA517C (contenido_id), INDEX IDX_881067C7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genero_contenido (id INT AUTO_INCREMENT NOT NULL, genero_id INT DEFAULT NULL, contenido_id INT DEFAULT NULL, INDEX IDX_78CCFBAABCE7B795 (genero_id), INDEX IDX_78CCFBAA7FDA517C (contenido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE generos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, critica_id INT DEFAULT NULL, user_id INT DEFAULT NULL, comentario_id INT DEFAULT NULL, INDEX IDX_AC6340B33B7FACB5 (critica_id), INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B3F3F2D7EC (comentario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparto (id INT AUTO_INCREMENT NOT NULL, contenido_id INT DEFAULT NULL, actor_id INT DEFAULT NULL, INDEX IDX_5C18C397FDA517C (contenido_id), INDEX IDX_5C18C3910DAF24A (actor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE siguiendo (id INT AUTO_INCREMENT NOT NULL, follower_id INT DEFAULT NULL, following_id INT DEFAULT NULL, INDEX IDX_F372987AAC24F853 (follower_id), INDEX IDX_F372987A1816E3A3 (following_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, rol INT NOT NULL, password VARCHAR(255) NOT NULL, recuperar INT NOT NULL, activado INT NOT NULL, bloquear INT NOT NULL, foto VARCHAR(255) DEFAULT NULL, caducidad DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valora (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contenido_id INT DEFAULT NULL, puntuacion INT DEFAULT NULL, INDEX IDX_DD2C9F9EA76ED395 (user_id), INDEX IDX_DD2C9F9E7FDA517C (contenido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visita (id INT AUTO_INCREMENT NOT NULL, contenido_id INT DEFAULT NULL, user_id INT DEFAULT NULL, fecha DATE NOT NULL, INDEX IDX_B7F148A27FDA517C (contenido_id), INDEX IDX_B7F148A2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7023B7FACB5 FOREIGN KEY (critica_id) REFERENCES critica (id)');
        $this->addSql('ALTER TABLE critica ADD CONSTRAINT FK_22C49E3EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE critica ADD CONSTRAINT FK_22C49E3E7FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id)');
        $this->addSql('ALTER TABLE favorito ADD CONSTRAINT FK_881067C77FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id)');
        $this->addSql('ALTER TABLE favorito ADD CONSTRAINT FK_881067C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE genero_contenido ADD CONSTRAINT FK_78CCFBAABCE7B795 FOREIGN KEY (genero_id) REFERENCES generos (id)');
        $this->addSql('ALTER TABLE genero_contenido ADD CONSTRAINT FK_78CCFBAA7FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B33B7FACB5 FOREIGN KEY (critica_id) REFERENCES critica (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3F3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id)');
        $this->addSql('ALTER TABLE reparto ADD CONSTRAINT FK_5C18C397FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id)');
        $this->addSql('ALTER TABLE reparto ADD CONSTRAINT FK_5C18C3910DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id)');
        $this->addSql('ALTER TABLE siguiendo ADD CONSTRAINT FK_F372987AAC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE siguiendo ADD CONSTRAINT FK_F372987A1816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE valora ADD CONSTRAINT FK_DD2C9F9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE valora ADD CONSTRAINT FK_DD2C9F9E7FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id)');
        $this->addSql('ALTER TABLE visita ADD CONSTRAINT FK_B7F148A27FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id)');
        $this->addSql('ALTER TABLE visita ADD CONSTRAINT FK_B7F148A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702A76ED395');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7023B7FACB5');
        $this->addSql('ALTER TABLE critica DROP FOREIGN KEY FK_22C49E3EA76ED395');
        $this->addSql('ALTER TABLE critica DROP FOREIGN KEY FK_22C49E3E7FDA517C');
        $this->addSql('ALTER TABLE favorito DROP FOREIGN KEY FK_881067C77FDA517C');
        $this->addSql('ALTER TABLE favorito DROP FOREIGN KEY FK_881067C7A76ED395');
        $this->addSql('ALTER TABLE genero_contenido DROP FOREIGN KEY FK_78CCFBAABCE7B795');
        $this->addSql('ALTER TABLE genero_contenido DROP FOREIGN KEY FK_78CCFBAA7FDA517C');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B33B7FACB5');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3F3F2D7EC');
        $this->addSql('ALTER TABLE reparto DROP FOREIGN KEY FK_5C18C397FDA517C');
        $this->addSql('ALTER TABLE reparto DROP FOREIGN KEY FK_5C18C3910DAF24A');
        $this->addSql('ALTER TABLE siguiendo DROP FOREIGN KEY FK_F372987AAC24F853');
        $this->addSql('ALTER TABLE siguiendo DROP FOREIGN KEY FK_F372987A1816E3A3');
        $this->addSql('ALTER TABLE valora DROP FOREIGN KEY FK_DD2C9F9EA76ED395');
        $this->addSql('ALTER TABLE valora DROP FOREIGN KEY FK_DD2C9F9E7FDA517C');
        $this->addSql('ALTER TABLE visita DROP FOREIGN KEY FK_B7F148A27FDA517C');
        $this->addSql('ALTER TABLE visita DROP FOREIGN KEY FK_B7F148A2A76ED395');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE contenido');
        $this->addSql('DROP TABLE critica');
        $this->addSql('DROP TABLE favorito');
        $this->addSql('DROP TABLE genero_contenido');
        $this->addSql('DROP TABLE generos');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE reparto');
        $this->addSql('DROP TABLE siguiendo');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE valora');
        $this->addSql('DROP TABLE visita');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
