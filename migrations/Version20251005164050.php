<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251005164050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `products` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE products (
                tax_number   VARCHAR(255) NOT NULL,
                name         VARCHAR(255) NOT NULL,
                country_code VARCHAR(2) NOT NULL,

                PRIMARY KEY (tax_number)
            )
        SQL);

        $this->addSql('CREATE INDEX IDX_B3BA5A5AF026BB7C ON products (country_code)');
        $this->addSql('CREATE INDEX id_product_name ON products (name)');

        $this->addSql(<<<SQL
            ALTER TABLE products
            ADD CONSTRAINT FK_B3BA5A5AF026BB7C
            FOREIGN KEY (country_code)
            REFERENCES countries (domain_zone)
            ON DELETE RESTRICT
            NOT DEFERRABLE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5AF026BB7C');
        $this->addSql('DROP TABLE products');
    }
}
