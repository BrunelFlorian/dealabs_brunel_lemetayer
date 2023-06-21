<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620154803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, keyword VARCHAR(255) NOT NULL, min_temperature INT NOT NULL, notification_frequency VARCHAR(255) NOT NULL, email_notification_enabled TINYINT(1) NOT NULL, INDEX IDX_17FD46C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_deal (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, deal_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EDC13303A76ED395 (user_id), INDEX IDX_EDC13303F60E2305 (deal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_deal ADD CONSTRAINT FK_EDC13303A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_deal ADD CONSTRAINT FK_EDC13303F60E2305 FOREIGN KEY (deal_id) REFERENCES deal (id)');
        $this->addSql('ALTER TABLE deal DROP expiration_date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C1A76ED395');
        $this->addSql('ALTER TABLE saved_deal DROP FOREIGN KEY FK_EDC13303A76ED395');
        $this->addSql('ALTER TABLE saved_deal DROP FOREIGN KEY FK_EDC13303F60E2305');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE saved_deal');
        $this->addSql('ALTER TABLE deal ADD expiration_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
