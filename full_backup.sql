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
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: madari
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Data for Name: customer; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.customer (id, cust_name, email, organization_id) FROM stdin;
1	Prashant Singh	psr595bro@gmail.com	1
7	{{7*7}}	johndoe@examle.com	1
12	barry allen	barry@barry.com	1
11	hacker	hacker@hacker.com	1
13	abc	abc@abc.com	1
15	Arijit Singh>	arijit@arijit.com	1
14	Arijit Singh	usa@usa.com	1
1337	Ironman	ironman@g00gle.com	2
9999	Hawkeye	hawkeye@g00gle.com	2
16	aaa	aa@aa.com	1
17	test	testnet@test.com	1
18	iphone	iph@iph.com	1
19	coke	coke@coke.com	1
20	pepsi	pepsi@coke.com	1
21	pepsig	pepsii@coke.com	1
22	barry	barry@barryy.com	1
23	barry	barry@barryyy.com	1
24	barry	barry@barryyyy.com	1
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
-- Data for Name: invoice; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public.invoice (id, customer_id, invoice_id, status, date_issued, organization_details, logo, items, total_amount, token, organization_id) FROM stdin;
8	1	INV001	DUE	2024-12-03 00:00:00	Helcim Inc	674d9fda98a45.svg	[{"name":"Cybersecurity","quantity":"1","price":"1000","total":"1000"}]	1000.00	5a995c0edb08effa381bb3971e0bc0b9	1
17	16	INV0020	DUE	2024-12-06 00:00:00	demo <script></script>	676f20bff1eb3.html	[{"name":"q","quantity":"1","price":"2","total":"2"},{"name":"a","quantity":"3","price":"5","total":"15"}]	17.00	Ut2UvS5UEEkgnUXjNzvHQX9mYQTm2XCA	1
1337	1337	INV1337	COMPLETED	2024-12-12 00:00:00	hacked	674d9fda98a45.svg	[{"name":"hacked","quantity":"1","price":"999","total":"999"}]	999.00	5a995c0edb08effa381bb3971e0bc0b8	2
9999	9999	INV9999	Paid	2024-12-20 00:00:00	Testing org	6769c654efffd.png	[{"name":"Mercury","quantity":"1","price":"38"}]	38.00	Dv3OloyQCuInmT2kK8BxNYVGszADqjFP	2
12	16	INV009	DUE	2024-12-19 00:00:00	demo	676f14980f7e4.html	[{"name":"aa","quantity":"1","price":"1"}]	1.00	9NW4Mx672b6GqG1ZRpnLAUPd4cB8hPNo	1
13	16	INV0022	DUE	2024-12-06 00:00:00	demo	676f178a5e9d4.jpg	[{"name":"q","quantity":"1","price":"2"},{"name":"a","quantity":"3","price":"5"}]	17.00	ojydFgKH2i8VLGkw0ZL0LnO1Ya5zAflb	1
9	12	INV002	COMPLETED	2024-12-12 00:00:00	 <iframe src="/home/madari/Desktop/Helcimworkspace/helcim_annual_ctf/templates/Invoice/flag.txt" width="600" height="400" ></iframe>	674da4b241d0b.png	[{"name":"L2 Switch CISCO","quantity":"1","price":"44","total":"44"}]	44.00	996b3ed12f02388846583d6d13cd040b	1
15	16	INV0023	DUE	2024-12-06 00:00:00	demo	676f1e9d3a8e2.html	[{"name":"q","quantity":"1","price":"2"},{"name":"a","quantity":"3","price":"5"}]	17.00	tPwaJmwwtERhaoZKQd1x4dhrzHqUPu4T	1
11	14	INV111	PAID	2024-12-20 00:00:00	7*7	6769c654efffd.png	[{"name":"testitem","quantity":"1","price":"28","total":"28"}]	28.00	3FUYohvR66Ll4g8VFCmTRbgtsfaF1a35	1
22	7	INV00123	DUE	2025-01-15 00:00:00	aaaa	67828382126b2.jpg	[{"name":"aaa","quantity":"2","price":"11"}]	22.00	9KlptKLcTm4Br8KubhnblDeJTC33hrSA	1
20	16	INV0024	DUE	2024-12-06 00:00:00	demo	676f212477639.html	[{"name":"q","quantity":"1","price":"2"},{"name":"a","quantity":"3","price":"5"}]	17.00	u8zzrw74fh2bVdl4bDmy7SgqR4iQnj8m	1
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
1	1	 Helcim Inc.
2	2	Google Inc.
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: madari
--

COPY public."user" (id, email, roles, password, is_paid_user, mobile_number, image_path, organization_id) FROM stdin;
13	psr595bro@gmail.com	["ROLE_ADMIN"]	$2y$13$GonQ2S.RU4ZD19SG4u.bPuzkfQEfucz7BpbcDuarBkwjYbu4wymqy	t	9999999994	/uploads/user_logos/13-678450e198089.txt	1
14	admin@admin.com	["ROLE_USER"]	$2y$13$GonQ2S.RU4ZD19SG4u.bPuzkfQEfucz7BpbcDuarBkwjYbu4wymqy	f	\N	\N	1
1337	admin@g00gle.com	["ROLE_ADMIN"]	$2y$13$GonQ2S.RU4ZD19SG4u.bPuzkfQEfucz7BpbcDuarBkwjYbu4wymqy	t	\N	\N	2
\.


--
-- Name: customer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.customer_id_seq', 24, true);


--
-- Name: invoice_id_seq; Type: SEQUENCE SET; Schema: public; Owner: madari
--

SELECT pg_catalog.setval('public.invoice_id_seq', 22, true);


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
-- PostgreSQL database dump complete
--

