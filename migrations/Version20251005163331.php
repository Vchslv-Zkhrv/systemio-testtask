<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251005163331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `countries` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE countries (
                domain_zone     VARCHAR(2) NOT NULL,
                tax_code_prefix VARCHAR(5) NOT NULL,
                name            VARCHAR(255) NOT NULL,

                PRIMARY KEY (domain_zone)
            )
        SQL);

        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBAD6389D723 ON countries (tax_code_prefix)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBAD5E237E06 ON countries (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE countries');
    }
}
