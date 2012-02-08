<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120202124207 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE club_shop_product_attribute DROP FOREIGN KEY FK_7D097C3EB6E62EFA");
        $this->addSql("DROP INDEX IDX_7D097C3EB6E62EFA ON club_shop_product_attribute");
        $this->addSql("ALTER TABLE club_shop_product_attribute ADD attribute VARCHAR(255) NOT NULL, DROP attribute_id");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE club_shop_product_attribute ADD attribute_id INT DEFAULT NULL, DROP attribute");
        $this->addSql("ALTER TABLE club_shop_product_attribute ADD CONSTRAINT FK_7D097C3EB6E62EFA FOREIGN KEY (attribute_id) REFERENCES club_shop_attribute(id)");
        $this->addSql("CREATE INDEX IDX_7D097C3EB6E62EFA ON club_shop_product_attribute (attribute_id)");
    }
}