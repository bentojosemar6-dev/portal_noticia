-- Portal de Notícias IPIL v2.0
-- Database: portal_ipil
-- Encoding: utf8mb4

CREATE DATABASE IF NOT EXISTS portal_ipil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portal_ipil;

-- Categorias
CREATE TABLE IF NOT EXISTS categorias (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(80) NOT NULL,
    slug      VARCHAR(80) NOT NULL UNIQUE,
    cor       VARCHAR(7) NOT NULL DEFAULT '#F97316',
    icone     VARCHAR(50) DEFAULT NULL,
    descricao TEXT DEFAULT NULL,
    ativa     TINYINT(1) NOT NULL DEFAULT 1,
    criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Utilizadores
CREATE TABLE IF NOT EXISTS utilizadores (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    senha         VARCHAR(255) NOT NULL,
    perfil        ENUM('super_admin','admin','editor','autor') NOT NULL DEFAULT 'autor',
    avatar        VARCHAR(255) DEFAULT NULL,
    bio           TEXT DEFAULT NULL,
    ativo         TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_login  DATETIME DEFAULT NULL,
    criado_em     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Noticias
CREATE TABLE IF NOT EXISTS noticias (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo          VARCHAR(255) NOT NULL,
    slug            VARCHAR(255) NOT NULL UNIQUE,
    resumo          TEXT NOT NULL,
    conteudo        LONGTEXT NOT NULL,
    imagem_capa     VARCHAR(255) DEFAULT NULL,
    alt_imagem      VARCHAR(255) DEFAULT NULL,
    autor_id        INT UNSIGNED NOT NULL,
    categoria_id    INT UNSIGNED NOT NULL,
    status          ENUM('rascunho','pendente','publicado','arquivado') NOT NULL DEFAULT 'rascunho',
    destaque        TINYINT(1) NOT NULL DEFAULT 0,
    views           INT UNSIGNED NOT NULL DEFAULT 0,
    permitir_comentarios TINYINT(1) NOT NULL DEFAULT 1,
    tags            VARCHAR(500) DEFAULT NULL,
    publicado_em    DATETIME DEFAULT NULL,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id)     REFERENCES utilizadores(id) ON DELETE RESTRICT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)   ON DELETE RESTRICT,
    INDEX idx_status    (status),
    INDEX idx_destaque  (destaque),
    INDEX idx_publicado (publicado_em)
) ENGINE=InnoDB;

-- Galeria de imagens
CREATE TABLE IF NOT EXISTS galeria_imagens (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    noticia_id  INT UNSIGNED DEFAULT NULL,
    caminho     VARCHAR(255) NOT NULL,
    nome_orig   VARCHAR(255) NOT NULL,
    tamanho     INT UNSIGNED NOT NULL,
    largura     SMALLINT UNSIGNED DEFAULT NULL,
    altura      SMALLINT UNSIGNED DEFAULT NULL,
    enviado_por INT UNSIGNED NOT NULL,
    enviado_em  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (noticia_id)  REFERENCES noticias(id)     ON DELETE CASCADE,
    FOREIGN KEY (enviado_por) REFERENCES utilizadores(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Comentarios
CREATE TABLE IF NOT EXISTS comentarios (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    noticia_id  INT UNSIGNED NOT NULL,
    nome        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    mensagem    TEXT NOT NULL,
    aprovado    TINYINT(1) NOT NULL DEFAULT 0,
    ip          VARCHAR(45) DEFAULT NULL,
    criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
    INDEX idx_aprovado (aprovado)
) ENGINE=InnoDB;

-- Sessoes admin
CREATE TABLE IF NOT EXISTS sessoes_admin (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT UNSIGNED NOT NULL,
    ip            VARCHAR(45) NOT NULL,
    agente        VARCHAR(300) DEFAULT NULL,
    login_em      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    logout_em     DATETIME DEFAULT NULL,
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tentativas de login (rate limiting)
CREATE TABLE IF NOT EXISTS tentativas_login (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip         VARCHAR(45) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    tentada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip, tentada_em)
) ENGINE=InnoDB;

-- Seeder: categorias
INSERT INTO categorias (nome, slug, cor, icone, descricao) VALUES
('Eventos Académicos',  'eventos-academicos',   '#F97316', 'graduation-cap', 'Eventos, palestras e cerimónias da instituição'),
('Avisos',              'avisos',               '#EF4444', 'megaphone',      'Comunicados oficiais e avisos importantes'),
('Resultados',          'resultados',           '#22C55E', 'clipboard-list', 'Resultados de exames e provas'),
('Formação',            'formacao',             '#3B82F6', 'book-open',      'Cursos, formações e capacitações'),
('Desporto',            'desporto',             '#A855F7', 'trophy',         'Actividades desportivas e competições'),
('Cultura',             'cultura',              '#EC4899', 'palette',        'Eventos culturais e artísticos');

-- Seeder: admin padrao (senha: Admin@2025)
INSERT INTO utilizadores (nome, email, senha, perfil) VALUES
('Administrador', 'admin@ipil.ao', '$2y$10$QudgvzE4rnjessTd5JzM6eAUP7L9XoLCgr7kIrXAaGGyB787FAFRG', 'super_admin');

-- Seeder: noticias exemplo
INSERT INTO noticias (titulo, slug, resumo, conteudo, categoria_id, autor_id, status, destaque, tags, publicado_em, views) VALUES
('IPIL realiza I Feira de Ciência e Tecnologia 2026', 'ipil-realiza-i-feira-de-ciencia-e-tecnologia-2026', 'O Instituto Politécnico Industrial de Luanda promove a primeira edição da Feira de Ciência e Tecnologia, reunindo projectos inovadores dos estudantes.', '<p>O Instituto Politécnico Industrial de Luanda (IPIL) anunciou a realização da sua I Feira de Ciência e Tecnologia, marcada para os dias 15 a 17 de Junho de 2026. O evento tem como objectivo promover a inovação e o empreendedorismo entre os estudantes.</p><p>Serão apresentados mais de 50 projectos nas áreas de engenharia, tecnologias de informação, energias renováveis e automação industrial. A feira contará com a presença de empresas parceiras e instituições de ensino superior.</p><p>Os interessados em participar podem inscrever-se até ao dia 30 de Maio através do portal do IPIL.</p>', 1, 1, 'publicado', 1, 'feira,ciência,tecnologia,inovação,eventos', '2026-05-20 10:00:00', 150),
('Aviso: Calendário de Exames do 2º Semestre', 'calendario-de-exames-2-semestre-2026', 'O IPIL divulga o calendário oficial de exames do segundo semestre do ano lectivo 2025/2026.', '<p>A Direcção Académica do IPIL torna público o calendário de exames do segundo semestre do ano lectivo 2025/2026. As provas terão início no dia 10 de Julho e decorrerão até 31 de Julho de 2026.</p><p>Os estudantes devem consultar o calendário completo no portal e verificar as datas das disciplinas em que estão inscritos. As pautas serão afixadas 48 horas antes de cada exame.</p><p>Para mais informações, contactar a secretaria académica.</p>', 2, 1, 'publicado', 0, 'exames,calendário,académico,avisos', '2026-05-18 14:30:00', 320),
('Resultados dos Exames de Admissão 2026', 'resultados-exames-admissao-2026', 'Já se encontram disponíveis os resultados dos exames de admissão para o ano lectivo 2026.', '<p>O IPIL informa que os resultados dos exames de admissão para o ano lectivo 2026 já estão disponíveis para consulta. Os candidatos podem aceder ao portal e verificar o seu desempenho.</p><p>Os aprovados deverão realizar a matrícula entre os dias 1 e 15 de Junho, apresentando os documentos originais exigidos no edital.</p>', 3, 1, 'publicado', 1, 'admissão,resultados,vestibular', '2026-05-15 09:00:00', 580),
('Curso de Programação Web com PHP e MySQL', 'curso-programacao-web-php-mysql', 'Inscrições abertas para o curso de Programação Web com PHP e MySQL, promovido pelo departamento de informática.', '<p>O Departamento de Informática do IPIL abre inscrições para o curso de Programação Web com PHP e MySQL. O curso tem duração de 3 meses e é voltado para estudantes e profissionais que desejam aprimorar as suas competências em desenvolvimento web.</p><p>As aulas decorrerão às segundas e quartas-feiras, das 18h às 20h, no laboratório de informática do IPIL. As vagas são limitadas.</p>', 4, 1, 'publicado', 0, 'curso,programação,PHP,MySQL,formação', '2026-05-12 11:00:00', 210),
('IPIL conquista taça de futebol inter-escolas', 'ipil-conquista-taca-futebol-inter-escolas', 'A equipa de futebol do IPIL venceu o campeonato inter-escolas da província de Luanda.', '<p>A equipa de futebol do Instituto Politécnico Industrial de Luanda sagrou-se campeã do torneio inter-escolas da província de Luanda, após vencer a final por 3-1 contra o Instituto Médio de Economia.</p><p>O jogo foi realizado no estádio da Cidadela, com grande apoio da comunidade estudantil. O destaque da partida foi o estudante João Paulo, autor de dois golos.</p>', 5, 1, 'publicado', 0, 'futebol,desporto,campeonato,taça', '2026-05-10 16:00:00', 180),
('Semana Cultural do IPIL 2026', 'semana-cultural-ipil-2026', 'O IPIL realiza a sua Semana Cultural com apresentações musicais, teatro, dança e exposições de arte.', '<p>A Semana Cultural do IPIL 2026 decorrerá de 20 a 25 de Junho, com uma vasta programação que inclui apresentações musicais, peças de teatro, danças tradicionais, exposições de arte e concursos literários.</p><p>O evento é aberto à comunidade e conta com a participação de artistas convidados e grupos culturais de outras instituições de ensino.</p>', 6, 1, 'publicado', 0, 'cultura,semana cultural,arte,música,teatro', '2026-05-08 10:30:00', 95);
