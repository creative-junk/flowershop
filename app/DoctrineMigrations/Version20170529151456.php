<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170529151456 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auction_order ADD receiving_agent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auction_order ADD CONSTRAINT FK_BA3D0A91DF94E42 FOREIGN KEY (receiving_agent_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BA3D0A91DF94E42 ON auction_order (receiving_agent_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auction_order DROP FOREIGN KEY FK_BA3D0A91DF94E42');
        $this->addSql('DROP INDEX IDX_BA3D0A91DF94E42 ON auction_order');
        $this->addSql('ALTER TABLE auction_order DROP receiving_agent_id');
    }
}
