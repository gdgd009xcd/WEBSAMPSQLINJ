set -e
psql -w -U postgres << EOSQL1
CREATE ROLE test LOGIN
  NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;

alter role test with password 'password';

CREATE DATABASE testdb
  WITH OWNER = test
       ENCODING = 'UTF8'
       TEMPLATE template0
       TABLESPACE = pg_default
       CONNECTION LIMIT = -1;
EOSQL1

psql -w -U postgres -d testdb << EOSQL2
CREATE SEQUENCE inquiryno
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 6
  CACHE 1;

ALTER TABLE inquiryno
  OWNER TO test;

CREATE TABLE account
(
  username character varying(200),
  password character varying(200),
  age integer
)
WITH (
  OIDS=FALSE
);
ALTER TABLE account
  OWNER TO test;

CREATE TABLE uploadlist
(
  username character varying(50),
  mailaddr character varying(250),
  subject character varying(250),
  contents character varying(1000),
  savedmailfile character varying(250),
  showurl character varying(500),
  imgfile character varying(500),
  inqnum integer
)
WITH (
  OIDS=FALSE
);
ALTER TABLE uploadlist
  OWNER TO test;
EOSQL2
