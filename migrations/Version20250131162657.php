<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131162657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_plat DROP FOREIGN KEY FK_7E691291D73DB560');
        $this->addSql('ALTER TABLE ingredient_plat DROP FOREIGN KEY FK_7E691291933FE08C');
        $this->addSql('DROP INDEX IDX_7E691291D73DB560 ON ingredient_plat');
        $this->addSql('DROP INDEX IDX_7E691291933FE08C ON ingredient_plat');
        $this->addSql('ALTER TABLE ingredient_plat DROP plat_id, DROP ingredient_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_plat ADD plat_id INT NOT NULL, ADD ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient_plat ADD CONSTRAINT FK_7E691291D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_plat ADD CONSTRAINT FK_7E691291933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7E691291D73DB560 ON ingredient_plat (plat_id)');
        $this->addSql('CREATE INDEX IDX_7E691291933FE08C ON ingredient_plat (ingredient_id)');
    }
}
