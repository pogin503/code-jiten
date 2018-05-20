
ALTER TABLE "t_language_extension" DROP CONSTRAINT "t_language_extension_language_id_fkey";

DROP TABLE IF EXISTS t_language_extension;

-- Table: public.t_example

-- DROP TABLE public.t_example;

-- CREATE TABLE t_example
-- (
--     example_id serial PRIMARY KEY,
--     language_id integer CONSTRAINT ,
--     language character varying(50),
--     example text NOT NULL,
--     group_cd integer NOT NULL
-- );

CREATE TABLE public.t_example (
       example_id integer NOT NULL DEFAULT nextval('t_example_example_id_seq'::regclass),
       language_id integer NOT NULL DEFAULT 0,
       language character varying(50) COLLATE pg_catalog."default",
       example text COLLATE pg_catalog."default" NOT NULL,
       group_cd integer NOT NULL,
       CONSTRAINT t_example_example_id PRIMARY KEY (example_id),
       CONSTRAINT t_example_group_cd FOREIGN KEY (group_cd)
       REFERENCES public.t_example_group (group_cd) MATCH SIMPLE
       ON UPDATE NO ACTION
       ON DELETE NO ACTION
) WITH (OIDS = FALSE)
TABLESPACE pg_default;

ALTER TABLE public.t_example
OWNER to postgres;

-- Index: fki_t_example_group_cd

-- DROP INDEX public.fki_t_example_group_cd;

-- Table: public.t_example_group

-- DROP TABLE public.t_example_group;

CREATE TABLE public.t_example_group (
       group_cd integer NOT NULL DEFAULT nextval('t_example_group_group_cd_seq'::regclass),
       group_name character varying(255) COLLATE pg_catalog."default" NOT NULL,
       "desc" character varying(400) COLLATE pg_catalog."default" NOT NULL,
       disp_flag smallint NOT NULL DEFAULT 0,
       parent_id integer NOT NULL DEFAULT 0,
       CONSTRAINT t_example_group_pkey PRIMARY KEY (group_cd)
) WITH (OIDS = FALSE)
TABLESPACE pg_default;

ALTER TABLE public.t_example_group
OWNER to postgres;


CREATE INDEX fki_t_example_group_cd
ON public.t_example USING btree
(group_cd)
TABLESPACE pg_default;


-- Table: public.t_example_relation

-- DROP TABLE public.t_example_relation;

CREATE TABLE public.t_example_relation (
       group_ancestor integer NOT NULL,
       group_descendant integer NOT NULL,
       depth integer NOT NULL,
       CONSTRAINT t_example_relation_group_ancestor_fkey FOREIGN KEY (group_ancestor)
       REFERENCES public.t_example_group (group_cd) MATCH SIMPLE
       ON UPDATE NO ACTION
       ON DELETE NO ACTION,
       CONSTRAINT t_example_relation_group_descendant_fkey FOREIGN KEY (group_descendant)
       REFERENCES public.t_example_group (group_cd) MATCH SIMPLE
       ON UPDATE NO ACTION
       ON DELETE NO ACTION
) WITH (OIDS = FALSE)
TABLESPACE pg_default;

ALTER TABLE public.t_example_relation
OWNER to postgres;

-- Table: public.t_language

-- DROP TABLE public.t_language;

CREATE TABLE public.t_language (
       id integer NOT NULL DEFAULT nextval('t_language_id_seq'::regclass),
       language character varying(50) COLLATE pg_catalog."default" NOT NULL,
       processing_system character varying(50) COLLATE pg_catalog."default" NOT NULL,
       version character varying(14) COLLATE pg_catalog."default",
       CONSTRAINT t_language_pkey PRIMARY KEY (id)
) WITH (OIDS = FALSE)
TABLESPACE pg_default;

ALTER TABLE public.t_language
OWNER to postgres;

-- Trigger: insert_template_trigger

-- DROP TRIGGER insert_template_trigger ON public.t_language;

CREATE TRIGGER insert_template_trigger
AFTER INSERT
ON public.t_language
FOR EACH ROW
EXECUTE PROCEDURE public.insert_template();


----

-- Table: public.t_language_extension

-- DROP TABLE public.t_language_extension;

CREATE TABLE public.t_language_extension (
       id integer NOT NULL DEFAULT nextval('t_language_extension_id_seq'::regclass),
       language_id integer NOT NULL,
       extension character varying(10) COLLATE pg_catalog."default" NOT NULL,
       default_extension smallint DEFAULT 0,
       CONSTRAINT t_language_extension_pkey PRIMARY KEY (id),
       CONSTRAINT t_language_extension_language_id_fkey FOREIGN KEY (language_id)
       REFERENCES public.t_language (id) MATCH SIMPLE
       ON UPDATE NO ACTION
       ON DELETE NO ACTION
) WITH (OIDS = FALSE)
TABLESPACE pg_default;

ALTER TABLE public.t_language_extension
OWNER to postgres;

-- Table: public.t_language_template

-- DROP TABLE public.t_language_template;

CREATE TABLE public.t_language_template(
       id integer NOT NULL DEFAULT nextval('t_language_template_id_seq'::regclass),
       language_id integer,
       template character varying(1000) COLLATE pg_catalog."default",
       created_at timestamp with time zone NOT NULL DEFAULT now(),
       updated_at timestamp with time zone NOT NULL DEFAULT now(),
       CONSTRAINT t_language_template_pkey PRIMARY KEY (id),
       CONSTRAINT t_language_template_language_id_fkey FOREIGN KEY (language_id)
       REFERENCES public.t_language (id) MATCH SIMPLE
       ON UPDATE NO ACTION
       ON DELETE NO ACTION
) WITH (OIDS = FALSE)
TABLESPACE pg_default;

-- Trigger: trigger_set_timestamp

-- DROP TRIGGER trigger_set_timestamp ON public.t_language_template;

CREATE TRIGGER trigger_set_timestamp
       BEFORE UPDATE
       ON public.t_language_template
       FOR EACH ROW
       EXECUTE PROCEDURE public.set_update_time();

-- Table: public.t_syntax_highlight

-- DROP TABLE public.t_syntax_highlight;
DROP TABLE IF EXISTS t_syntax_mode;
CREATE TABLE t_syntax_mode(
       id serial PRIMARY KEY,
       mode_syntax character varying(40),
       mode character varying(16)
);
