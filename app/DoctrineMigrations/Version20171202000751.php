<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171202000751 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE text text LONGTEXT NOT NULL');

        $this->addSql('ALTER TABLE user ADD username_canonical VARCHAR(180) NOT NULL');
	    $this->addSql('ALTER TABLE user ADD email_canonical VARCHAR(180) NOT NULL');
	    $this->addSql('ALTER TABLE user ADD confirmation_token VARCHAR(180) NOT NULL');
		$this->addSql('ALTER TABLE user ADD enabled TINYINT(1) NOT NULL');
		$this->addSql('ALTER TABLE user ADD salt VARCHAR(255) NOT NULL');
		$this->addSql('ALTER TABLE user ADD last_login DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE user ADD password_requested_at DATETIME DEFAULT NULL');
		$this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT "(DC2Type:array)"');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A0D96FBF ON user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON user (confirmation_token)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE text text VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_8D93D64992FC23A8 ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D649A0D96FBF ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D649C05FB297 ON `user`');
        $this->addSql('ALTER TABLE user DROP (username_canonical, email_canonical, confirmation_token, enabled, salt, last_login, password_requested_at, roles)');
    }
}
