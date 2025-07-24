DROP TABLE if exists users;

CREATE TABLE users (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   name VARCHAR(50),
   email VARCHAR(50) UNIQUE
);

INSERT INTO users (name, email) VALUES ('user1', 'user1@mail.ex');