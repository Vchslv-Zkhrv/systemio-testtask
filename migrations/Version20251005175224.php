<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251005175224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `purchases` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE purchases (
                id             UUID NOT NULL,
                created_at     TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                payment_system payment_system_enum NOT NULL,
                status         purchase_status_enum NOT NULL,
                purchaser_id   UUID NOT NULL,

                PRIMARY KEY (id)
            )
        SQL);

        $this->addSql('CREATE INDEX IDX_AA6431FEDD630168 ON purchases (purchaser_id)');

        $this->addSql(<<<SQL
            ALTER TABLE purchases
            ADD CONSTRAINT FK_AA6431FEDD630168
            FOREIGN KEY (purchaser_id)
            REFERENCES users (id)
            ON DELETE RESTRICT
            NOT DEFERRABLE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE purchases DROP CONSTRAINT FK_AA6431FEDD630168');
        $this->addSql('DROP TABLE purchases');
    }
}
