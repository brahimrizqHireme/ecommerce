<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310025902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pruchase_product (pruchase_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_365C6B0ED332C809 (pruchase_id), INDEX IDX_365C6B0E4584665A (product_id), PRIMARY KEY(pruchase_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pruchase_product ADD CONSTRAINT FK_365C6B0ED332C809 FOREIGN KEY (pruchase_id) REFERENCES pruchase (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pruchase_product ADD CONSTRAINT FK_365C6B0E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pruchase_product DROP FOREIGN KEY FK_365C6B0ED332C809');
        $this->addSql('ALTER TABLE pruchase_product DROP FOREIGN KEY FK_365C6B0E4584665A');
        $this->addSql('DROP TABLE pruchase_product');
    }
}
