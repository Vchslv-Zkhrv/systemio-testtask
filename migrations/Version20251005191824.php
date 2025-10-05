<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251005191824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `coupons` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE coupons (
                id UUID NOT NULL,
                sale_type sale_type_enum NOT NULL,
                sale_value DOUBLE PRECISION NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                valid_till TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                code VARCHAR(255) NOT NULL,
                receiver_id UUID DEFAULT NULL,
                purchase_id UUID DEFAULT NULL,

                PRIMARY KEY (id)
            )
        SQL);

        $this->addSql('CREATE INDEX IDX_F5641118CD53EDB6 ON coupons (receiver_id)');
        $this->addSql('CREATE INDEX IDX_F5641118558FBEB9 ON coupons (purchase_id)');

        $this->addSql(<<<SQL
            ALTER TABLE coupons
            ADD CONSTRAINT FK_F5641118CD53EDB6
            FOREIGN KEY (receiver_id)
            REFERENCES users (id)
            ON DELETE RESTRICT
            NOT DEFERRABLE
        SQL);

        $this->addSql(<<<SQL
            ALTER TABLE coupons
            ADD CONSTRAINT FK_F5641118558FBEB9
            FOREIGN KEY (purchase_id)
            REFERENCES purchases (id)
            ON DELETE RESTRICT
            NOT DEFERRABLE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE coupons DROP CONSTRAINT FK_F5641118CD53EDB6');
        $this->addSql('ALTER TABLE coupons DROP CONSTRAINT FK_F5641118558FBEB9');
        $this->addSql('DROP TABLE coupons');
    }
}
