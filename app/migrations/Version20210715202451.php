<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210715202451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `account` (`id` INT AUTO_INCREMENT NOT NULL, `bank_id` INT DEFAULT NULL, `contact_id` INT DEFAULT NULL, `name` VARCHAR(255) NOT NULL, `account_holder` VARCHAR(255) DEFAULT NULL, `bank_code` VARCHAR(64) DEFAULT NULL, `account_number` VARCHAR(64) DEFAULT NULL, `bic` VARCHAR(11) DEFAULT NULL, `iban` VARCHAR(34) DEFAULT NULL, `opening_balance` INT DEFAULT NULL, `is_active` TINYINT(1) NOT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, INDEX IDX_7D3656A441327D4B (`bank_id`), UNIQUE INDEX UNIQ_7D3656A4315E9CF5 (`contact_id`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `bank` (`id` INT AUTO_INCREMENT NOT NULL, `record_number` INT NOT NULL, `bic` VARCHAR(11) NOT NULL, `name` VARCHAR(58) NOT NULL, `short_name` VARCHAR(27) NOT NULL, `postal_code` VARCHAR(5) NOT NULL, `locality` VARCHAR(35) NOT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `budget` (`id` INT AUTO_INCREMENT NOT NULL, `category_id` INT DEFAULT NULL, `name` VARCHAR(255) NOT NULL, `description` VARCHAR(1000) DEFAULT NULL, `amount` INT NOT NULL, `start_date` DATE DEFAULT NULL, `end_date` DATE DEFAULT NULL, `is_active` TINYINT(1) NOT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, INDEX IDX_73F2F77BABF411AF (`category_id`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `category` (`id` INT AUTO_INCREMENT NOT NULL, `parent_id` INT DEFAULT NULL, `name` VARCHAR(255) NOT NULL, `description` VARCHAR(1000) NOT NULL, `is_active` TINYINT(1) NOT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, INDEX IDX_64C19C1171ECA81 (`parent_id`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `contact` (`id` INT AUTO_INCREMENT NOT NULL, `account_id` INT DEFAULT NULL, `name` VARCHAR(255) NOT NULL, `description` VARCHAR(1000) DEFAULT NULL, `is_active` TINYINT(1) NOT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4C62E6388C9FA493 (`account_id`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `entry` (`id` INT AUTO_INCREMENT NOT NULL, `parent_id` INT DEFAULT NULL, `account_id` INT DEFAULT NULL, `contact_id` INT DEFAULT NULL, `savings_id` INT DEFAULT NULL, `category_id` INT DEFAULT NULL, `date` DATE NOT NULL, `amount` INT NOT NULL, `description` VARCHAR(1000) DEFAULT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, INDEX IDX_2B219D70171ECA81 (`parent_id`), INDEX IDX_2B219D708C9FA493 (`account_id`), INDEX IDX_2B219D70315E9CF5 (`contact_id`), INDEX IDX_2B219D70AE3E42F1 (`savings_id`), INDEX IDX_2B219D70ABF411AF (`category_id`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `savings` (`id` INT AUTO_INCREMENT NOT NULL, `name` VARCHAR(255) NOT NULL, `description` VARCHAR(1000) DEFAULT NULL, `amount` INT NOT NULL, `installment` INT NOT NULL, `installment_frequency` VARCHAR(255) NOT NULL, `start_date` DATE DEFAULT NULL, `end_date` DATE DEFAULT NULL, `created_at` DATETIME DEFAULT NULL, `updated_at` DATETIME DEFAULT NULL, PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `account` ADD CONSTRAINT FK_7D3656A441327D4B FOREIGN KEY (`bank_id`) REFERENCES `bank` (`id`) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `account` ADD CONSTRAINT FK_7D3656A4315E9CF5 FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `budget` ADD CONSTRAINT FK_73F2F77BABF411AF FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)');
        $this->addSql('ALTER TABLE `category` ADD CONSTRAINT FK_64C19C1171ECA81 FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`)');
        $this->addSql('ALTER TABLE `contact` ADD CONSTRAINT FK_4C62E6388C9FA493 FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `entry` ADD CONSTRAINT FK_2B219D70171ECA81 FOREIGN KEY (`parent_id`) REFERENCES `entry` (`id`)');
        $this->addSql('ALTER TABLE `entry` ADD CONSTRAINT FK_2B219D708C9FA493 FOREIGN KEY (`account_id`) REFERENCES `account` (`id`)');
        $this->addSql('ALTER TABLE `entry` ADD CONSTRAINT FK_2B219D70315E9CF5 FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`)');
        $this->addSql('ALTER TABLE `entry` ADD CONSTRAINT FK_2B219D70AE3E42F1 FOREIGN KEY (`savings_id`) REFERENCES `savings` (`id`)');
        $this->addSql('ALTER TABLE `entry` ADD CONSTRAINT FK_2B219D70ABF411AF FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `contact` DROP FOREIGN KEY FK_4C62E6388C9FA493');
        $this->addSql('ALTER TABLE `entry` DROP FOREIGN KEY FK_2B219D708C9FA493');
        $this->addSql('ALTER TABLE `account` DROP FOREIGN KEY FK_7D3656A441327D4B');
        $this->addSql('ALTER TABLE `budget` DROP FOREIGN KEY FK_73F2F77BABF411AF');
        $this->addSql('ALTER TABLE `category` DROP FOREIGN KEY FK_64C19C1171ECA81');
        $this->addSql('ALTER TABLE `entry` DROP FOREIGN KEY FK_2B219D70ABF411AF');
        $this->addSql('ALTER TABLE `account` DROP FOREIGN KEY FK_7D3656A4315E9CF5');
        $this->addSql('ALTER TABLE `entry` DROP FOREIGN KEY FK_2B219D70315E9CF5');
        $this->addSql('ALTER TABLE `entry` DROP FOREIGN KEY FK_2B219D70171ECA81');
        $this->addSql('ALTER TABLE `entry` DROP FOREIGN KEY FK_2B219D70AE3E42F1');
        $this->addSql('DROP TABLE `account`');
        $this->addSql('DROP TABLE `bank`');
        $this->addSql('DROP TABLE `budget`');
        $this->addSql('DROP TABLE `category`');
        $this->addSql('DROP TABLE `contact`');
        $this->addSql('DROP TABLE `entry`');
        $this->addSql('DROP TABLE `savings`');
    }
}
