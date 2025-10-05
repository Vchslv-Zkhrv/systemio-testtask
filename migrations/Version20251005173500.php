<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\DBAL\Types\Enum\DbalPaymentSystemType;
use App\DBAL\Types\Enum\DbalPurchaseStatusType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251005173500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `payment_system_enum` and `purchase_status_enum` types';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(DbalPaymentSystemType::getSQLCreateQuery());
        $this->addSql(DbalPurchaseStatusType::getSQLCreateQuery());
    }

    public function down(Schema $schema): void
    {
        $this->addSql(DbalPaymentSystemType::getSQLDropQuery());
        $this->addSql(DbalPurchaseStatusType::getSQLDropQuery());
    }
}
