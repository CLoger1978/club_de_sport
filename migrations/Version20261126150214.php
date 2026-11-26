<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261126150214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE competition_participation (
              id INT AUTO_INCREMENT NOT NULL,
              progression INT NOT NULL,
              sportif_id INT NOT NULL,
              competition_id INT NOT NULL,
              UNIQUE INDEX UNIQ_SPORTIF_COMPETITION (sportif_id, competition_id),
              INDEX IDX_5C8020EFFFB7083B (sportif_id),
              INDEX IDX_5C8020EF7B39D312 (competition_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_participation
            ADD
              CONSTRAINT FK_5C8020EFFFB7083B FOREIGN KEY (sportif_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_participation
            ADD
              CONSTRAINT FK_5C8020EF7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competition_participation DROP FOREIGN KEY FK_5C8020EFFFB7083B');
        $this->addSql('ALTER TABLE competition_participation DROP FOREIGN KEY FK_5C8020EF7B39D312');
        $this->addSql('DROP TABLE competition_participation');
    }
}
