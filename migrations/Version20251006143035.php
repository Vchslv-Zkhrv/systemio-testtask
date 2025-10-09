<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251006143035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add fields `article` and `quantity` to `purchases` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE purchases ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE purchases ADD article INT NOT NULL');

        $this->addSql(<<<SQL
            ALTER TABLE purchases
            ADD CONSTRAINT FK_AA6431FE23A0E66
            FOREIGN KEY (article)
            REFERENCES products (article)
            ON DELETE RESTRICT
            NOT DEFERRABLE
        SQL);

        $this->addSql('CREATE INDEX IDX_AA6431FE23A0E66 ON purchases (article)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE purchases DROP CONSTRAINT FK_AA6431FE23A0E66');
        $this->addSql('DROP INDEX IDX_AA6431FE23A0E66');
        $this->addSql('ALTER TABLE purchases DROP quantity');
        $this->addSql('ALTER TABLE purchases DROP article');
    }
}
