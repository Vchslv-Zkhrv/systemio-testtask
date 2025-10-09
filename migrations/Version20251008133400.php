<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008133400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Nullable `tax_code` and `country_code` in `users` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_5d66ebadb2b1b8a');
        $this->addSql('ALTER TABLE countries DROP tax_code_pattern');
        $this->addSql('ALTER TABLE users ALTER tax_code DROP NOT NULL');
        $this->addSql('ALTER TABLE users ALTER country_code DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE countries ADD tax_code_pattern VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_5d66ebadb2b1b8a ON countries (tax_code_pattern)');
        $this->addSql('ALTER TABLE users ALTER tax_code SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER country_code SET NOT NULL');
    }
}
