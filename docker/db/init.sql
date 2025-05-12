--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4 (Ubuntu 17.4-1.pgdg22.04+2)
-- Dumped by pg_dump version 17.4 (Ubuntu 17.4-1.pgdg22.04+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: notify_messenger_messages(); Type: FUNCTION; Schema: public; Owner: madari
--

CREATE FUNCTION public.notify_messenger_messages() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
                PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$;


ALTER FUNCTION public.notify_messenger_messages() OWNER TO madari;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: customer; Type: TABLE; Schema: public; Owner: madari
--

CREATE TABLE public.customer (
    id integer NOT NULL,
    cust_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    organization_id integer NOT NULL
);


ALTER TABLE public.customer OWNER TO madari;

--
-- Name: customer_id_seq; Type: SEQUENCE; Schema: public; Owner: madari
--

CREATE SEQUENCE public.customer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.customer_id_seq OWNER TO madari;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: madari
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO madari;

--
-- Name: feature_requests; Type: TABLE; Schema: public; Owner: madari
--

CREATE TABLE public.feature_requests (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    title character varying(255) NOT NULL,
    details text,
    status character varying(50) DEFAULT 'Open'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    attachment character varying(255)
);


ALTER TABLE public.feature_requests OWNER TO madari;

--
-- Name: feature_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: madari
--

CREATE SEQUENCE public.feature_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.feature_requests_id_seq OWNER TO madari;

--
-- Name: feature_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: madari
--

ALTER SEQUENCE public.feature_requests_id_seq OWNED BY public.feature_requests.id;


--
-- Name: invoice; Type: TABLE; Schema: public; Owner: madari
--

CREATE TABLE public.invoice (
    id integer NOT NULL,
    customer_id integer NOT NULL,
    invoice_id character varying(255) NOT NULL,
    status character varying(50) NOT NULL,
    date_issued timestamp(0) without time zone NOT NULL,
    organization_details character varying(500) NOT NULL,
    logo character varying(500) NOT NULL,
    items json NOT NULL,
    total_amount numeric(10,2) NOT NULL,
    token character varying(255) NOT NULL,
    organization_id integer NOT NULL
);


ALTER TABLE public.invoice OWNER TO madari;

--
-- Name: invoice_id_seq; Type: SEQUENCE; Schema: public; Owner: madari
--

CREATE SEQUENCE public.invoice_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.invoice_id_seq OWNER TO madari;

--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: madari
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.messenger_messages OWNER TO madari;

--
-- Name: COLUMN messenger_messages.created_at; Type: COMMENT; Schema: public; Owner: madari
--

COMMENT ON COLUMN public.messenger_messages.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.available_at; Type: COMMENT; Schema: public; Owner: madari
--

COMMENT ON COLUMN public.messenger_messages.available_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.delivered_at; Type: COMMENT; Schema: public; Owner: madari
--

COMMENT ON COLUMN public.messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)';


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: madari
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.messenger_messages_id_seq OWNER TO madari;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: madari
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: organization; Type: TABLE; Schema: public; Owner: madari
--

CREATE TABLE public.organization (
    id integer NOT NULL,
    org_id integer NOT NULL,
    org_name character varying(255) NOT NULL
);


ALTER TABLE public.organization OWNER TO madari;

--
-- Name: organization_id_seq; Type: SEQUENCE; Schema: public; Owner: madari
--

CREATE SEQUENCE public.organization_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.organization_id_seq OWNER TO madari;

--
-- Name: user; Type: TABLE; Schema: public; Owner: madari
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    is_paid_user boolean DEFAULT false NOT NULL,
    mobile_number bigint,
    image_path character varying(255) DEFAULT NULL::character varying,
    organization_id integer NOT NULL
);


ALTER TABLE public."user" OWNER TO madari;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: madari
--

CREATE SEQUENCE public.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_id_seq OWNER TO madari;

--
-- Name: feature_requests id; Type: DEFAULT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.feature_requests ALTER COLUMN id SET DEFAULT nextval('public.feature_requests_id_seq'::regclass);


--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Data for Name: customer; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.customer (id, cust_name, email, organization_id) FROM stdin;
1	Prashant Singh	prashant@invotek.com	1
12	barry allen	barry@barry.com	1
1337	Ironman	ironman@g00gle.com	2
9999	Hawkeye	hawkeye@g00gle.com	2
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20241017133208	\N	\N
DoctrineMigrations\\Version20241102175528	\N	\N
DoctrineMigrations\\Version20241102180454	\N	\N
DoctrineMigrations\\Version20241109080646	\N	\N
DoctrineMigrations\\Version20241109083358	\N	\N
DoctrineMigrations\\Version20241109083847	\N	\N
DoctrineMigrations\\Version20241125115347	\N	\N
DoctrineMigrations\\Version20241125123250	\N	\N
DoctrineMigrations\\Version20241127184944	\N	\N
DoctrineMigrations\\Version20241202112304	\N	\N
DoctrineMigrations\\Version20241202113101	\N	\N
DoctrineMigrations\\Version20241202114038	\N	\N
DoctrineMigrations\\Version20241202115745	\N	\N
DoctrineMigrations\\Version20241202121258	2024-12-02 17:43:11	1
\.


--
-- Data for Name: feature_requests; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.feature_requests (id, user_id, title, details, status, created_at, updated_at, attachment) FROM stdin;
\.


--
-- Data for Name: invoice; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.invoice (id, customer_id, invoice_id, status, date_issued, organization_details, logo, items, total_amount, token, organization_id) FROM stdin;
8	1	INV001	DUE	2024-12-03 00:00:00	Helcim Inc	674d9fda98a45.svg	[{"name":"Cybersecurity","quantity":"1","price":"1000","total":"1000"}]	1000.00	5a995c0edb08effa381bb3971e0bc0b9	1
1337	1337	INV1337	DUE	2024-12-06 00:00:00	demo 123	674d9fda98a45.svg	[{"name":"q","quantity":1,"price":2,"total":2},{"name":"a","quantity":3,"price":5,"total":15}]	17.00	5a995c0edb08effa381bb3971e0bc0b8	2
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
\.


--
-- Data for Name: organization; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.organization (id, org_id, org_name) FROM stdin;
2	2	Google Inc.
1	1	 Helcim Inc.
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public."user" (id, email, roles, password, is_paid_user, mobile_number, image_path, organization_id) FROM stdin;
13	prashant@invotek.com	["ROLE_ADMIN"]	$2y$12$xyiabdV/j0MgoQhN8/s2.u6cQ7PoPAL/MmK5sIIXQ1XJ6GUhfJ9hG	t	999999998	/uploads/user_logos/13-320dce89-7826-46ee-8c1b-19296102f9c8.jpg	1
14	admin@invotek.com	["ROLE_ADMIN"]	$2y$12$xyiabdV/j0MgoQhN8/s2.u6cQ7PoPAL/MmK5sIIXQ1XJ6GUhfJ9hG	f	\N	\N	1
1337	admin@g00gle.com	["ROLE_ADMIN"]	$2y$13$GonQ2S.RU4ZD19SG4u.bPuzkfQEfucz7BpbcDuarBkwjYbu4wymqy	t	\N	\N	2
\.


--
-- Name: customer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.customer_id_seq', 24, true);


--
-- Name: feature_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.feature_requests_id_seq', 13, true);


--
-- Name: invoice_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.invoice_id_seq', 24, true);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 1, false);


--
-- Name: organization_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.organization_id_seq', 1, false);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.user_id_seq', 1, false);


--
-- Name: customer customer_pkey; Type: CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.customer
    ADD CONSTRAINT customer_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: feature_requests feature_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.feature_requests
    ADD CONSTRAINT feature_requests_pkey PRIMARY KEY (id);


--
-- Name: invoice invoice_pkey; Type: CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.invoice
    ADD CONSTRAINT invoice_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: organization organization_pkey; Type: CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.organization
    ADD CONSTRAINT organization_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: madari
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: madari
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: madari
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_81398e0932c8a3de; Type: INDEX; Schema: public; Owner: madari
--

CREATE INDEX idx_81398e0932c8a3de ON public.customer USING btree (organization_id);


--
-- Name: idx_8d93d64932c8a3de; Type: INDEX; Schema: public; Owner: madari
--

CREATE INDEX idx_8d93d64932c8a3de ON public."user" USING btree (organization_id);


--
-- Name: idx_9065174432c8a3de; Type: INDEX; Schema: public; Owner: madari
--

CREATE INDEX idx_9065174432c8a3de ON public.invoice USING btree (organization_id);


--
-- Name: idx_906517449395c3f3; Type: INDEX; Schema: public; Owner: madari
--

CREATE INDEX idx_906517449395c3f3 ON public.invoice USING btree (customer_id);


--
-- Name: uniq_81398e09e7927c74; Type: INDEX; Schema: public; Owner: madari
--

CREATE UNIQUE INDEX uniq_81398e09e7927c74 ON public.customer USING btree (email);


--
-- Name: uniq_906517445f37a13b; Type: INDEX; Schema: public; Owner: madari
--

CREATE UNIQUE INDEX uniq_906517445f37a13b ON public.invoice USING btree (token);


--
-- Name: uniq_identifier_email; Type: INDEX; Schema: public; Owner: madari
--

CREATE UNIQUE INDEX uniq_identifier_email ON public."user" USING btree (email);


--
-- Name: messenger_messages notify_trigger; Type: TRIGGER; Schema: public; Owner: madari
--

CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON public.messenger_messages FOR EACH ROW EXECUTE FUNCTION public.notify_messenger_messages();


--
-- Name: customer fk_81398e0932c8a3de; Type: FK CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.customer
    ADD CONSTRAINT fk_81398e0932c8a3de FOREIGN KEY (organization_id) REFERENCES public.organization(id);


--
-- Name: user fk_8d93d64932c8a3de; Type: FK CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT fk_8d93d64932c8a3de FOREIGN KEY (organization_id) REFERENCES public.organization(id);


--
-- Name: invoice fk_9065174432c8a3de; Type: FK CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.invoice
    ADD CONSTRAINT fk_9065174432c8a3de FOREIGN KEY (organization_id) REFERENCES public.organization(id);


--
-- Name: invoice fk_906517449395c3f3; Type: FK CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.invoice
    ADD CONSTRAINT fk_906517449395c3f3 FOREIGN KEY (customer_id) REFERENCES public.customer(id);


--
-- Name: feature_requests fk_feature_user; Type: FK CONSTRAINT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.feature_requests
    ADD CONSTRAINT fk_feature_user FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- PostgreSQL database dump complete
--

