<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251006122018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add fields `price`, `stock` and `article` to table `products`';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP CONSTRAINT products_pkey');
        $this->addSql('ALTER TABLE products ADD article INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE products ADD stock INT NOT NULL');
        $this->addSql('ALTER TABLE products DROP tax_number');
        $this->addSql('ALTER TABLE products ADD PRIMARY KEY (article)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP CONSTRAINT products_pkey');
        $this->addSql('ALTER TABLE products ADD tax_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE products DROP article');
        $this->addSql('ALTER TABLE products DROP price');
        $this->addSql('ALTER TABLE products DROP stock');
        $this->addSql('ALTER TABLE products ADD PRIMARY KEY (tax_number)');
    }
}
