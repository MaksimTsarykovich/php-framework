USE app;

CREATE TABLE files
(
    `id`   INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255)                   NOT NULL,
    `size` INT                            NOT NULL,
    `type` VARCHAR(100)                   NOT NULL,
    `path` VARCHAR(255)                   NOT NULL
);


CREATE TABLE transactions
(
    `id`          INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `file_id`     INT                            NOT NULL,
    `data`        VARCHAR(55)                    NOT NULL,
    `check`       VARCHAR(55),
    `description` VARCHAR(255) UNIQUE            NOT NULL,
    `amount`      INT                            NOT NULL,
    `is_positive` BOOL                           NOT NULL,
    FOREIGN KEY (`file_id`) REFERENCES files (`id`)
);
