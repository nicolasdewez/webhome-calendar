<?php

use Phinx\Migration\AbstractMigration;

class Initialization extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->execute('CREATE SEQUENCE job_nursery_periods_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE nursery_calendars_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE calendars_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE job_nurseries_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE job_calendar_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE google_connections_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE SEQUENCE jobs_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->execute('CREATE TABLE job_nursery_periods (id INT NOT NULL, job_nursery_id INT DEFAULT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, type SMALLINT NOT NULL, meal BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE INDEX IDX_8DF0EA1A9B0EC81B ON job_nursery_periods (job_nursery_id);');
        $this->execute('CREATE TABLE nursery_calendars (id INT NOT NULL, calendar_id INT DEFAULT NULL, date DATE NOT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, meal BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE INDEX IDX_12CE9CBDA40A2C8 ON nursery_calendars (calendar_id);');
        $this->execute('CREATE TABLE calendars (id INT NOT NULL, title VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE TABLE job_nurseries (id INT NOT NULL, job_id INT DEFAULT NULL, day SMALLINT NOT NULL, serial BOOLEAN NOT NULL, number_days SMALLINT NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE INDEX IDX_A18D5FF1BE04EA9 ON job_nurseries (job_id);');
        $this->execute('CREATE TABLE job_calendar (id INT NOT NULL, job_id INT DEFAULT NULL, calendar_id INT DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE INDEX IDX_9EE043DBE04EA9 ON job_calendar (job_id);');
        $this->execute('CREATE INDEX IDX_9EE043DA40A2C8 ON job_calendar (calendar_id);');
        $this->execute('CREATE TABLE google_connections (id INT NOT NULL, title VARCHAR(255) NOT NULL, client_id VARCHAR(255) NOT NULL, client_secret VARCHAR(255) NOT NULL, project_id VARCHAR(255) NOT NULL, internal_id VARCHAR(255) NOT NULL, job_day_complete BOOLEAN NOT NULL, nursery_day_complete BOOLEAN NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE UNIQUE INDEX google_connections_google_unique ON google_connections (client_id, project_id, internal_id);');
        $this->execute('CREATE TABLE calendar_google_connection (calendar_id INT NOT NULL, google_connection_id INT NOT NULL, PRIMARY KEY(calendar_id, google_connection_id));');
        $this->execute('CREATE INDEX IDX_15510C5A40A2C8 ON calendar_google_connection (calendar_id);');
        $this->execute('CREATE INDEX IDX_15510C520D168F6 ON calendar_google_connection (google_connection_id);');
        $this->execute('ALTER TABLE calendar_google_connection ADD CONSTRAINT FK_15510C5A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendars (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE calendar_google_connection ADD CONSTRAINT FK_15510C520D168F6 FOREIGN KEY (google_connection_id) REFERENCES google_connections (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('CREATE TABLE jobs (id INT NOT NULL, code VARCHAR(5) NOT NULL, title VARCHAR(255) NOT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, duration INT NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id));');
        $this->execute('CREATE UNIQUE INDEX jobs_code_unique ON jobs (code);');
        $this->execute('ALTER TABLE job_nursery_periods ADD CONSTRAINT FK_8DF0EA1A9B0EC81B FOREIGN KEY (job_nursery_id) REFERENCES job_nurseries (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE nursery_calendars ADD CONSTRAINT FK_12CE9CBDA40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendars (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE job_nurseries ADD CONSTRAINT FK_A18D5FF1BE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE job_calendar ADD CONSTRAINT FK_9EE043DBE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->execute('ALTER TABLE job_calendar ADD CONSTRAINT FK_9EE043DA40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendars (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
    }
}
