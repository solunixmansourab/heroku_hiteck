<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706134410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE addresses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_details_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "orders_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE partners_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE posts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE addresses (id INT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FCA7516A76ED395 ON addresses (user_id)');
        $this->addSql('CREATE TABLE order_details (id INT NOT NULL, my_order_id INT DEFAULT NULL, product VARCHAR(255) NOT NULL, quantity VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_845CA2C1BFCDF877 ON order_details (my_order_id)');
        $this->addSql('CREATE TABLE "orders" (id INT NOT NULL, customer_id INT DEFAULT NULL, address_id INT DEFAULT NULL, order_price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reference VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEE9395C3F3 ON "orders" (customer_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEF5B7AF75 ON "orders" (address_id)');
        $this->addSql('CREATE TABLE partners (id INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE post_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE posts (id INT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, slug VARCHAR(255) NOT NULL, post_image VARCHAR(255) DEFAULT NULL, is_published BOOLEAN NOT NULL, excerpt VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_885DBAFA12469DE2 ON posts (category_id)');
        $this->addSql('CREATE INDEX IDX_885DBAFAA76ED395 ON posts (user_id)');
        $this->addSql('CREATE TABLE product_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, slug VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, cover_image VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, in_stock BOOLEAN DEFAULT NULL, is_published BOOLEAN DEFAULT NULL, is_promo BOOLEAN DEFAULT NULL, promo_price VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AA76ED395 ON products (user_id)');
        $this->addSql('CREATE TABLE product_product_category (product_id INT NOT NULL, product_category_id INT NOT NULL, PRIMARY KEY(product_id, product_category_id))');
        $this->addSql('CREATE INDEX IDX_437017AA4584665A ON product_product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_437017AABE6903FD ON product_product_category (product_category_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA7516A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1BFCDF877 FOREIGN KEY (my_order_id) REFERENCES "orders" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "orders" ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "orders" ADD CONSTRAINT FK_E52FFDEEF5B7AF75 FOREIGN KEY (address_id) REFERENCES addresses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA12469DE2 FOREIGN KEY (category_id) REFERENCES post_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_product_category ADD CONSTRAINT FK_437017AA4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_product_category ADD CONSTRAINT FK_437017AABE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "orders" DROP CONSTRAINT FK_E52FFDEEF5B7AF75');
        $this->addSql('ALTER TABLE order_details DROP CONSTRAINT FK_845CA2C1BFCDF877');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFA12469DE2');
        $this->addSql('ALTER TABLE product_product_category DROP CONSTRAINT FK_437017AABE6903FD');
        $this->addSql('ALTER TABLE product_product_category DROP CONSTRAINT FK_437017AA4584665A');
        $this->addSql('ALTER TABLE addresses DROP CONSTRAINT FK_6FCA7516A76ED395');
        $this->addSql('ALTER TABLE "orders" DROP CONSTRAINT FK_E52FFDEE9395C3F3');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFAA76ED395');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5AA76ED395');
        $this->addSql('DROP SEQUENCE addresses_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_details_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "orders_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE partners_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE posts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE products_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE "orders"');
        $this->addSql('DROP TABLE partners');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE product_product_category');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
