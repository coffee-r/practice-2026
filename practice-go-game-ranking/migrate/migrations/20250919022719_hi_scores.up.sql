SET statement_timeout = 0;

--bun:split

CREATE TABLE hi_scores (
    name varchar(10) primary key,
    hi_score int NOT NULL,
    recorded_at TIMESTAMP NOT NULL
);

--bun:split

SELECT 2
