CREATE TABLE events (
  id integer NOT NULL,
  date date,
  starts_at timestamp without time zone,
  ends_at timestamp without time zone,
  frequency character varying(255),
  count integer,
  "until" date
);

CREATE TABLE event_cancellations (
  id integer NOT NULL,
  event_id integer,
  date date
);

CREATE TABLE event_recurrences (
  id integer NOT NULL,
  event_id integer,
  "month" integer,
  "day" integer,
  week integer
);
