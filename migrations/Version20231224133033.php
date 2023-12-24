<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231224133033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE comentario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, critica_id INTEGER DEFAULT NULL, comentario VARCHAR(255) DEFAULT NULL, fecha DATETIME NOT NULL, CONSTRAINT FK_4B91E702A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4B91E7023B7FACB5 FOREIGN KEY (critica_id) REFERENCES critica (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4B91E702A76ED395 ON comentario (user_id)');
        $this->addSql('CREATE INDEX IDX_4B91E7023B7FACB5 ON comentario (critica_id)');
        $this->addSql('CREATE TABLE contenido (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, alias VARCHAR(255) DEFAULT NULL, descripcion VARCHAR(255) DEFAULT NULL, estreno DATE NOT NULL, poster VARCHAR(255) DEFAULT NULL, portada VARCHAR(255) DEFAULT NULL, serie INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE critica (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, contenido_id INTEGER DEFAULT NULL, comentario VARCHAR(255) DEFAULT NULL, fecha DATETIME NOT NULL, CONSTRAINT FK_22C49E3EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_22C49E3E7FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_22C49E3EA76ED395 ON critica (user_id)');
        $this->addSql('CREATE INDEX IDX_22C49E3E7FDA517C ON critica (contenido_id)');
        $this->addSql('CREATE TABLE favorito (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contenido_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, CONSTRAINT FK_881067C77FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_881067C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_881067C77FDA517C ON favorito (contenido_id)');
        $this->addSql('CREATE INDEX IDX_881067C7A76ED395 ON favorito (user_id)');
        $this->addSql('CREATE TABLE genero_contenido (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, genero_id INTEGER DEFAULT NULL, contenido_id INTEGER DEFAULT NULL, CONSTRAINT FK_78CCFBAABCE7B795 FOREIGN KEY (genero_id) REFERENCES generos (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_78CCFBAA7FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_78CCFBAABCE7B795 ON genero_contenido (genero_id)');
        $this->addSql('CREATE INDEX IDX_78CCFBAA7FDA517C ON genero_contenido (contenido_id)');
        $this->addSql('CREATE TABLE generos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE "like" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, critica_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, comentario_id INTEGER DEFAULT NULL, CONSTRAINT FK_AC6340B33B7FACB5 FOREIGN KEY (critica_id) REFERENCES critica (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AC6340B3F3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AC6340B33B7FACB5 ON "like" (critica_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3A76ED395 ON "like" (user_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3F3F2D7EC ON "like" (comentario_id)');
        $this->addSql('CREATE TABLE reparto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contenido_id INTEGER DEFAULT NULL, actor_id INTEGER DEFAULT NULL, CONSTRAINT FK_5C18C397FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C18C3910DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5C18C397FDA517C ON reparto (contenido_id)');
        $this->addSql('CREATE INDEX IDX_5C18C3910DAF24A ON reparto (actor_id)');
        $this->addSql('CREATE TABLE siguiendo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, follower_id INTEGER DEFAULT NULL, following_id INTEGER DEFAULT NULL, CONSTRAINT FK_F372987AAC24F853 FOREIGN KEY (follower_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F372987A1816E3A3 FOREIGN KEY (following_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F372987AAC24F853 ON siguiendo (follower_id)');
        $this->addSql('CREATE INDEX IDX_F372987A1816E3A3 ON siguiendo (following_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , rol INTEGER NOT NULL, password VARCHAR(255) NOT NULL, recuperar INTEGER NOT NULL, activado INTEGER NOT NULL, bloquear INTEGER NOT NULL, foto VARCHAR(255) DEFAULT NULL, caducidad DATETIME DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TABLE valora (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, contenido_id INTEGER DEFAULT NULL, puntuacion INTEGER DEFAULT NULL, CONSTRAINT FK_DD2C9F9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DD2C9F9E7FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DD2C9F9EA76ED395 ON valora (user_id)');
        $this->addSql('CREATE INDEX IDX_DD2C9F9E7FDA517C ON valora (contenido_id)');
        $this->addSql('CREATE TABLE visita (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contenido_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, fecha DATE NOT NULL, CONSTRAINT FK_B7F148A27FDA517C FOREIGN KEY (contenido_id) REFERENCES contenido (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B7F148A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B7F148A27FDA517C ON visita (contenido_id)');
        $this->addSql('CREATE INDEX IDX_B7F148A2A76ED395 ON visita (user_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE contenido');
        $this->addSql('DROP TABLE critica');
        $this->addSql('DROP TABLE favorito');
        $this->addSql('DROP TABLE genero_contenido');
        $this->addSql('DROP TABLE generos');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('DROP TABLE reparto');
        $this->addSql('DROP TABLE siguiendo');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE valora');
        $this->addSql('DROP TABLE visita');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
