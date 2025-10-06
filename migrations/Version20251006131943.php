<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251006131943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove relation between `products` and `countries` tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_b3ba5a5af026bb7c');
        $this->addSql('ALTER TABLE products DROP country_code');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products ADD CONSTRAINT fk_b3ba5a5af026bb7c FOREIGN KEY (country_code) REFERENCES countries (domain_zone) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b3ba5a5af026bb7c ON products (country_code)');
    }
}
