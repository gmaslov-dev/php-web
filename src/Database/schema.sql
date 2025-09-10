-- src/Database/schema.sql

DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
       id SERIAL PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) UNIQUE NOT NULL
);