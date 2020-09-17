-- DROP TABLE videos;

CREATE TABLE videos
(
  id bigserial, -- Id
  slug text NOT NULL, -- Slug
  title text NOT NULL, -- Title
  thumbnail text, -- Thumbnail
  duration integer NOT NULL, -- Duration
  views bigint NOT NULL DEFAULT 0, -- Views
  added_at timestamp with time zone NOT NULL DEFAULT now(), -- AddedAt
  CONSTRAINT videos_pkey PRIMARY KEY (id)
);

-- @migration delimiter@

COMMENT ON COLUMN videos.id IS 'Id';
-- @migration delimiter@
COMMENT ON COLUMN videos.slug IS 'Slug';
-- @migration delimiter@
COMMENT ON COLUMN videos.title IS 'Title';
-- @migration delimiter@
COMMENT ON COLUMN videos.thumbnail IS 'Thumbnail';
-- @migration delimiter@
COMMENT ON COLUMN videos.duration IS 'Duration';
-- @migration delimiter@
COMMENT ON COLUMN videos.views IS 'Views';
-- @migration delimiter@
COMMENT ON COLUMN videos.added_at IS 'AddedAt';

-- @migration delimiter@

-- DROP INDEX videos_views_desc;

CREATE INDEX videos_views_desc
  ON videos
  USING btree
  (views DESC);

-- @migration delimiter@

-- DROP INDEX videos_added_at_desc;

CREATE INDEX videos_added_at_desc
  ON videos
  USING btree
  (added_at DESC);

-- @migration delimiter@

--NOTE: 960.0 = counterPage parameter. Materialized views must be re-created when parameter changed
--@see /config/params.php

-- DROP MATERIALIZED VIEW videos_added_at_counter;

CREATE MATERIALIZED VIEW videos_added_at_counter AS
SELECT
    'desc' AS mode,
    max(t.added_at) AS added_at,
    max(t.real_amount) AS real_amount,
    t.amount
FROM
    (
    SELECT
        (ceil((sum(count(1)) OVER (ORDER BY (videos.added_at::date) DESC))::double precision / 960.0) * 960.0)::integer AS amount,
        sum(count(1)) OVER (ORDER BY (videos.added_at::date) DESC) AS real_amount,
        videos.added_at::date AS added_at
    FROM
        videos
    GROUP BY
        videos.added_at::date
    ORDER BY
        videos.added_at::date DESC
    ) t
GROUP BY
    t.amount
UNION ALL
SELECT
    'asc' AS mode,
    min(t.added_at) AS added_at,
    max(t.real_amount) AS real_amount,
    t.amount
FROM
    (
    SELECT
        (ceil((sum(count(1)) OVER (ORDER BY (videos.added_at::date) ASC))::double precision / 960.0) * 960.0)::integer AS amount,
        sum(count(1)) OVER (ORDER BY (videos.added_at::date) ASC) AS real_amount,
        videos.added_at::date AS added_at
    FROM
        videos
    GROUP BY
        videos.added_at::date
    ORDER BY
        videos.added_at::date ASC
    ) t
GROUP BY
    t.amount;

-- @migration delimiter@

-- DROP INDEX videos_added_at_counter_amount;

CREATE INDEX videos_added_at_counter_amount
  ON videos_added_at_counter
  USING btree
  (amount);

-- @migration delimiter@

-- DROP INDEX videos_added_at_counter_mode_added_at;

CREATE UNIQUE INDEX videos_added_at_counter_mode_added_at
  ON videos_added_at_counter
  USING btree
  (mode, added_at);

-- @migration delimiter@

-- DROP MATERIALIZED VIEW videos_views_counter;

CREATE MATERIALIZED VIEW videos_views_counter AS
SELECT
    'desc' AS mode,
    max(t.views) AS views,
    max(t.real_amount) AS real_amount,
    t.amount
FROM
    (
    SELECT
        (ceil((sum(count(1)) OVER (ORDER BY (videos.views) DESC))::double precision / 960.0) * 960.0)::integer AS amount,
        sum(count(1)) OVER (ORDER BY (videos.views) DESC) AS real_amount,
        videos.views
    FROM
        videos
    GROUP BY
        videos.views
    ORDER BY
        videos.views DESC
    ) t
GROUP BY
    t.amount
UNION ALL
SELECT
    'asc' AS mode,
    min(t.views) AS views,
    max(t.real_amount) AS real_amount,
    t.amount
FROM
    (
    SELECT
        (ceil((sum(count(1)) OVER (ORDER BY (videos.views) ASC))::double precision / 960.0) * 960.0)::integer AS amount,
        sum(count(1)) OVER (ORDER BY (videos.views) ASC) AS real_amount,
        videos.views
    FROM
        videos
    GROUP BY
        videos.views
    ORDER BY
        videos.views ASC
    ) t
GROUP BY
    t.amount;

-- @migration delimiter@

-- DROP INDEX videos_views_counter_amount;

CREATE INDEX videos_views_counter_amount
  ON videos_views_counter
  USING btree
  (amount);

-- @migration delimiter@

-- DROP INDEX videos_views_counter_mode_views;

CREATE UNIQUE INDEX videos_views_counter_mode_views
  ON videos_views_counter
  USING btree
  (mode, views);
