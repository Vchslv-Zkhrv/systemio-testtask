<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251005191854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tax fixes: `tax_code_prefix` replaced with `tax_code_pattern` in `countries` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_5d66ebad6389d723');
        $this->addSql('ALTER TABLE countries ADD tax_code_pattern VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE countries DROP tax_code_prefix');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBADB2B1B8A ON countries (tax_code_pattern)');

        // mapping 
        $this->addSql('ALTER INDEX idx_aa6431fedd630168 RENAME TO IDX_AA6431FEED255ED6');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_5D66EBADB2B1B8A');
        $this->addSql('ALTER TABLE countries ADD tax_code_prefix VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE countries DROP tax_code_pattern');
        $this->addSql('CREATE UNIQUE INDEX uniq_5d66ebad6389d723 ON countries (tax_code_prefix)');

        // mapping
        $this->addSql('ALTER INDEX idx_aa6431feed255ed6 RENAME TO idx_aa6431fedd630168');
    }
}
