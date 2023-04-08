<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125141434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE casting (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, person_id INT NOT NULL, role VARCHAR(255) NOT NULL, credit_order INT NOT NULL, INDEX IDX_D11BBA508F93B6FC (movie_id), INDEX IDX_D11BBA50217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_movie (genre_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_A058EDAA4296D31F (genre_id), INDEX IDX_A058EDAA8F93B6FC (movie_id), PRIMARY KEY(genre_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, duration INT NOT NULL, summary LONGTEXT NOT NULL, synopsys LONGTEXT NOT NULL, rating DOUBLE PRECISION NOT NULL, country VARCHAR(64) NOT NULL, type VARCHAR(5) NOT NULL, release_date DATE NOT NULL, poster VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, rating DOUBLE PRECISION NOT NULL, reactions LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', watched_at DATETIME NOT NULL, INDEX IDX_794381C68F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, number_episode INT NOT NULL, INDEX IDX_F0E45BA98F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA508F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA50217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE genre_movie ADD CONSTRAINT FK_A058EDAA4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_movie ADD CONSTRAINT FK_A058EDAA8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA98F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA508F93B6FC');
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA50217BBB47');
        $this->addSql('ALTER TABLE genre_movie DROP FOREIGN KEY FK_A058EDAA4296D31F');
        $this->addSql('ALTER TABLE genre_movie DROP FOREIGN KEY FK_A058EDAA8F93B6FC');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C68F93B6FC');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA98F93B6FC');
        $this->addSql('DROP TABLE casting');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE season');
    }
}
