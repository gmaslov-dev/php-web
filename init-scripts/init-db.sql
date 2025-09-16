-- CREATE DATABASE postgres_app;
\c postgres_app;

CREATE TABLE IF NOT EXISTS users (
     id SERIAL PRIMARY KEY,
     name VARCHAR(255),
     email VARCHAR(255) UNIQUE
);