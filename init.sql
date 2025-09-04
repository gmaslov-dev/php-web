DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
       id SERIAL PRIMARY KEY,
       name VARCHAR(50),
       email VARCHAR(50) UNIQUE
);

INSERT INTO users (name, email) VALUES ('test_user', 'user@mail.ex');