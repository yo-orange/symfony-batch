CREATE TABLE batch_job_insance  (
    command VARCHAR(100) NOT NULL PRIMARY KEY,
    started_at datetime,
    finished_at datetime,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO batch_job_insance(command) VALUES('export:product');
INSERT INTO batch_job_insance(command) VALUES('HelloWorld');
