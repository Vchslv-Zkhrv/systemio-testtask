<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251006124258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create ManyToOne relation users -> countries';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD country_code VARCHAR(2) NOT NULL');

        $this->addSql(<<<SQL
            ALTER TABLE users
            ADD CONSTRAINT FK_1483A5E9F026BB7C
            FOREIGN KEY (country_code)
            REFERENCES countries (domain_zone)
            ON DELETE RESTRICT
            NOT DEFERRABLE
        SQL);

        $this->addSql('CREATE INDEX IDX_1483A5E9F026BB7C ON users (country_code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9F026BB7C');
        $this->addSql('DROP INDEX IDX_1483A5E9F026BB7C');
        $this->addSql('ALTER TABLE users DROP country_code');
    }
}
