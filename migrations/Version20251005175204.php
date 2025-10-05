<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\DBAL\Types\Enum\DbalSaleType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251005175204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `sale_type_enum` enum type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(DbalSaleType::getSQLCreateQuery());
    }

    public function down(Schema $schema): void
    {
        $this->addSql(DbalSaleType::getSQLDropQuery());
    }
}
