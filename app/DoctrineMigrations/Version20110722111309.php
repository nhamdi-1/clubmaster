<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20110722111309 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE club_user_profile_address DROP FOREIGN KEY club_user_profile_address_ibfk_1");
        $this->addSql("ALTER TABLE club_user_profile_address ADD FOREIGN KEY (profile_id) REFERENCES club_user_profile(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY club_user_profile_ibfk_1");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY club_user_profile_ibfk_2");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY club_user_profile_ibfk_3");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY club_user_profile_ibfk_4");
        $this->addSql("ALTER TABLE club_user_profile ADD FOREIGN KEY (user_id) REFERENCES club_user_user(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_user_profile ADD FOREIGN KEY (profile_address_id) REFERENCES club_user_profile_address(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_user_profile ADD FOREIGN KEY (profile_phone_id) REFERENCES club_user_profile_phone(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_user_profile ADD FOREIGN KEY (profile_email_id) REFERENCES club_user_profile_email(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_user_user DROP FOREIGN KEY club_user_user_ibfk_1");
        $this->addSql("ALTER TABLE club_user_user ADD FOREIGN KEY (profile_id) REFERENCES club_user_profile(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_user_profile_phone DROP FOREIGN KEY club_user_profile_phone_ibfk_1");
        $this->addSql("ALTER TABLE club_user_profile_phone ADD FOREIGN KEY (profile_id) REFERENCES club_user_profile(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_user_profile_email DROP FOREIGN KEY club_user_profile_email_ibfk_1");
        $this->addSql("ALTER TABLE club_user_profile_email ADD FOREIGN KEY (profile_id) REFERENCES club_user_profile(id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE club_event_attend DROP FOREIGN KEY club_event_attend_ibfk_1");
        $this->addSql("ALTER TABLE club_event_attend ADD FOREIGN KEY (user_id) REFERENCES club_user_user(id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE club_event_attend DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_event_attend ADD CONSTRAINT club_event_attend_ibfk_1 FOREIGN KEY (user_id) REFERENCES club_user_user(id)");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_profile DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_profile ADD CONSTRAINT club_user_profile_ibfk_1 FOREIGN KEY (user_id) REFERENCES club_user_user(id)");
        $this->addSql("ALTER TABLE club_user_profile ADD CONSTRAINT club_user_profile_ibfk_2 FOREIGN KEY (profile_address_id) REFERENCES club_user_profile_address(id)");
        $this->addSql("ALTER TABLE club_user_profile ADD CONSTRAINT club_user_profile_ibfk_3 FOREIGN KEY (profile_phone_id) REFERENCES club_user_profile_phone(id)");
        $this->addSql("ALTER TABLE club_user_profile ADD CONSTRAINT club_user_profile_ibfk_4 FOREIGN KEY (profile_email_id) REFERENCES club_user_profile_email(id)");
        $this->addSql("ALTER TABLE club_user_profile_address DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_profile_address ADD CONSTRAINT club_user_profile_address_ibfk_1 FOREIGN KEY (profile_id) REFERENCES club_user_profile(id)");
        $this->addSql("ALTER TABLE club_user_profile_email DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_profile_email ADD CONSTRAINT club_user_profile_email_ibfk_1 FOREIGN KEY (profile_id) REFERENCES club_user_profile(id)");
        $this->addSql("ALTER TABLE club_user_profile_phone DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_profile_phone ADD CONSTRAINT club_user_profile_phone_ibfk_1 FOREIGN KEY (profile_id) REFERENCES club_user_profile(id)");
        $this->addSql("ALTER TABLE club_user_user DROP FOREIGN KEY ");
        $this->addSql("ALTER TABLE club_user_user ADD CONSTRAINT club_user_user_ibfk_1 FOREIGN KEY (profile_id) REFERENCES club_user_profile(id)");
    }
}