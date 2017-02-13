<?php

namespace BrasseursApplis\Arrows\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170119212421 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql([
            'CREATE SCHEMA arrows',

            'CREATE TABLE arrows.researcher (id UUID NOT NULL, PRIMARY KEY(id))',
            'CREATE TABLE arrows.result (id UUID NOT NULL, session_id UUID DEFAULT NULL, sequence_position VARCHAR(10) NOT NULL, sequence_preview_orientation VARCHAR(10) NOT NULL, sequence_initiation_orientation VARCHAR(10) NOT NULL, sequence_main_orientation VARCHAR(10) NOT NULL, orientation VARCHAR(10) NOT NULL, start_timestamp INT NOT NULL, end_timestamp INT NOT NULL, PRIMARY KEY(id))',
            'CREATE TABLE arrows.scenario_template (id UUID NOT NULL, author UUID NOT NULL, name VARCHAR(255) NOT NULL, scenario_sequences JSON NOT NULL, PRIMARY KEY(id))',
            'CREATE TABLE arrows.session (id UUID NOT NULL, observer_id UUID NOT NULL, scenario_current_position INT DEFAULT NULL, scenario_sequences JSON NOT NULL, position_one UUID NOT NULL, position_two UUID NOT NULL, PRIMARY KEY(id))',
            'CREATE TABLE arrows.subject (id UUID NOT NULL, PRIMARY KEY(id))',
            'CREATE TABLE arrows."user" (id UUID NOT NULL, userName VARCHAR(75) NOT NULL, password VARCHAR(75) NOT NULL, salt VARCHAR(25) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))',

            'CREATE INDEX IDX_19F5F4E3613FECDF ON arrows.result (session_id)',

            'CREATE UNIQUE INDEX UNIQ_A9A0FD0F586CA949 ON arrows."user" (userName)',

            'ALTER TABLE arrows.result ADD CONSTRAINT FK_19F5F4E3613FECDF FOREIGN KEY (session_id) REFERENCES arrows.session (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        ]);

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql([ 'DROP SCHEMA arrows CASCADE;' ]);

    }
}
