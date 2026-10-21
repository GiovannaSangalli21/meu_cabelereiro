--
-- PostgreSQL database dump
--

\restrict eieZSw6GZB03xGAiqKGAi1s9Kwu48VWhxRCasLlXKeKMP7NvMU3kLGzc7L2i3gr

-- Dumped from database version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)
-- Dumped by pg_dump version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: agendamentos; Type: TABLE; Schema: public; Owner: roupa
--

CREATE TABLE public.agendamentos (
    id integer NOT NULL,
    nome_cliente character varying(100) NOT NULL,
    telefone_cliente character varying(20) NOT NULL,
    cabeleireira_id integer,
    servico character varying(50) NOT NULL,
    data_agendamento date NOT NULL,
    horario time without time zone,
    horario_inicio time without time zone,
    email_cliente character varying(100)
);


ALTER TABLE public.agendamentos OWNER TO roupa;

--
-- Name: agendamentos_id_seq; Type: SEQUENCE; Schema: public; Owner: roupa
--

CREATE SEQUENCE public.agendamentos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.agendamentos_id_seq OWNER TO roupa;

--
-- Name: agendamentos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: roupa
--

ALTER SEQUENCE public.agendamentos_id_seq OWNED BY public.agendamentos.id;


--
-- Name: cabeleireiras; Type: TABLE; Schema: public; Owner: roupa
--

CREATE TABLE public.cabeleireiras (
    id integer NOT NULL,
    nome character varying(250) NOT NULL,
    especialidade character varying(250) NOT NULL,
    telefone character varying(50) NOT NULL,
    email character varying(250) NOT NULL,
    horario_inicio time without time zone NOT NULL,
    horario_fim time without time zone
);


ALTER TABLE public.cabeleireiras OWNER TO roupa;

--
-- Name: cabeleireiras_id_seq; Type: SEQUENCE; Schema: public; Owner: roupa
--

CREATE SEQUENCE public.cabeleireiras_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cabeleireiras_id_seq OWNER TO roupa;

--
-- Name: cabeleireiras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: roupa
--

ALTER SEQUENCE public.cabeleireiras_id_seq OWNED BY public.cabeleireiras.id;


--
-- Name: clientes; Type: TABLE; Schema: public; Owner: roupa
--

CREATE TABLE public.clientes (
    id integer NOT NULL,
    nome character varying(100) NOT NULL,
    telefone character varying(20) NOT NULL,
    email character varying(100),
    especialidade character varying(100)
);


ALTER TABLE public.clientes OWNER TO roupa;

--
-- Name: clientes_id_seq; Type: SEQUENCE; Schema: public; Owner: roupa
--

CREATE SEQUENCE public.clientes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.clientes_id_seq OWNER TO roupa;

--
-- Name: clientes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: roupa
--

ALTER SEQUENCE public.clientes_id_seq OWNED BY public.clientes.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: roupa
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    senha character varying(255) NOT NULL
);


ALTER TABLE public.usuarios OWNER TO roupa;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: roupa
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuarios_id_seq OWNER TO roupa;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: roupa
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: agendamentos id; Type: DEFAULT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.agendamentos ALTER COLUMN id SET DEFAULT nextval('public.agendamentos_id_seq'::regclass);


--
-- Name: cabeleireiras id; Type: DEFAULT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.cabeleireiras ALTER COLUMN id SET DEFAULT nextval('public.cabeleireiras_id_seq'::regclass);


--
-- Name: clientes id; Type: DEFAULT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.clientes ALTER COLUMN id SET DEFAULT nextval('public.clientes_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Data for Name: agendamentos; Type: TABLE DATA; Schema: public; Owner: roupa
--

COPY public.agendamentos (id, nome_cliente, telefone_cliente, cabeleireira_id, servico, data_agendamento, horario, horario_inicio, email_cliente) FROM stdin;
7	GIOVANNA SANGALLI	19982866380902	\N	Corte	2026-10-31	\N	14:31:00	giovanna.sangalli@edu.senai.br
8	pedro	1999999999	\N	Luzes	2026-10-31	\N	14:41:00	giovanna.sangalli@edu.senai.br
11	joao	19789654	\N	Luzes	2026-12-20	\N	12:12:00	joao@gmail.com
13	Alef	19999999999	\N	Luzes	2026-10-23	\N	18:40:00	alef.a@edu.senai.br
12	LUCAS VILAS BOAS GALDINO	199999876	\N	Corte	2026-10-31	\N	20:19:00	lucas.galdino@edu.senai.br
9	pedro Sangalli	1999999999	\N	Luzes	2026-10-31	\N	14:41:00	giovanna.sangalli@edu.senai.br
\.


--
-- Data for Name: cabeleireiras; Type: TABLE DATA; Schema: public; Owner: roupa
--

COPY public.cabeleireiras (id, nome, especialidade, telefone, email, horario_inicio, horario_fim) FROM stdin;
10	giovanna	Corte	199999999	giovanna@gmail.com	00:14:00	\N
11	giovanna	Escova	19999999	giovanna@gmail	10:23:00	\N
14	Lucas Vilas Boas Galdino	Luzes	19983456789	lucas@gmail.com	14:55:00	\N
15	gio	Corte	1999999999	giovanna@gmail	11:14:00	\N
\.


--
-- Data for Name: clientes; Type: TABLE DATA; Schema: public; Owner: roupa
--

COPY public.clientes (id, nome, telefone, email, especialidade) FROM stdin;
\.


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: roupa
--

COPY public.usuarios (id, email, senha) FROM stdin;
\.


--
-- Name: agendamentos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: roupa
--

SELECT pg_catalog.setval('public.agendamentos_id_seq', 13, true);


--
-- Name: cabeleireiras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: roupa
--

SELECT pg_catalog.setval('public.cabeleireiras_id_seq', 15, true);


--
-- Name: clientes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: roupa
--

SELECT pg_catalog.setval('public.clientes_id_seq', 1, false);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: roupa
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 1, false);


--
-- Name: agendamentos agendamentos_pkey; Type: CONSTRAINT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.agendamentos
    ADD CONSTRAINT agendamentos_pkey PRIMARY KEY (id);


--
-- Name: cabeleireiras cabeleireiras_pkey; Type: CONSTRAINT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.cabeleireiras
    ADD CONSTRAINT cabeleireiras_pkey PRIMARY KEY (id);


--
-- Name: clientes clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (id);


--
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: idx_agendamentos_data_horario; Type: INDEX; Schema: public; Owner: roupa
--

CREATE INDEX idx_agendamentos_data_horario ON public.agendamentos USING btree (data_agendamento, horario_inicio);


--
-- Name: agendamentos fk_cabeleireira; Type: FK CONSTRAINT; Schema: public; Owner: roupa
--

ALTER TABLE ONLY public.agendamentos
    ADD CONSTRAINT fk_cabeleireira FOREIGN KEY (cabeleireira_id) REFERENCES public.cabeleireiras(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict eieZSw6GZB03xGAiqKGAi1s9Kwu48VWhxRCasLlXKeKMP7NvMU3kLGzc7L2i3gr

