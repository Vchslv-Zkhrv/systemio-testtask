<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251006123016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add field `tax_code` to table `users`';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD tax_code VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E96B9A3F60 ON users (tax_code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_1483A5E96B9A3F60');
        $this->addSql('ALTER TABLE users DROP tax_code');
    }
}
