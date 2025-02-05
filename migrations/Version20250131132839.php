<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131132839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, nom_ingredient VARCHAR(255) NOT NULL, nom_image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_plat (id INT AUTO_INCREMENT NOT NULL, plat_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_7E691291D73DB560 (plat_id), INDEX IDX_7E691291933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (id INT AUTO_INCREMENT NOT NULL, nom_plat VARCHAR(255) NOT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, temps_cuisson TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient_plat ADD CONSTRAINT FK_7E691291D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_plat ADD CONSTRAINT FK_7E691291933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_plat DROP FOREIGN KEY FK_7E691291D73DB560');
        $this->addSql('ALTER TABLE ingredient_plat DROP FOREIGN KEY FK_7E691291933FE08C');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_plat');
        $this->addSql('DROP TABLE plat');
    }
}
