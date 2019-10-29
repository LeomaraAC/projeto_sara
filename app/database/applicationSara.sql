CREATE TABLE cursos (
    id INT NOT NULL PRIMARY KEY,
    codigo VARCHAR(255) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    deleted_at TIMESTAMP
);
CREATE TABLE alunos (
    id BIGINT NOT NULL PRIMARY KEY,
    cpf BIGINT,
    rg VARCHAR(255),
    nome VARCHAR(255) NOT NULL,
    data_nascimento VARCHAR(15) NOT NULL,
    nome_mae VARCHAR(255),
    nome_pai VARCHAR(255),
    sexo ENUM('F', 'M') NOT NULL,
    responsavel VARCHAR(255),
    email_pessoal VARCHAR(255),
    email_responsavel VARCHAR(255),
    estado_civil VARCHAR(60) NOT NULL,
    naturalidade VARCHAR(255),
    deficiencia VARCHAR(255),
    etnia VARCHAR(20) NOT NULL,
    necessidades_especiais VARCHAR(255),
    renda_bruta FLOAT,
    renda_per_capta FLOAT,
    superdotacao VARCHAR(255),
    tipo_escola_origem VARCHAR(255),
    transtorno VARCHAR(255),
    endereco VARCHAR(255) NOT NULL,
    deleted_at TIMESTAMP
);
CREATE TABLE telefones (
    id BIGINT NOT NULL PRIMARY KEY,
    numero VARCHAR(20) NOT NULL,
    id_aluno BIGINT NOT NULL,
    FOREIGN KEY(id_aluno) REFERENCES alunos(id)
);
CREATE TABLE matriculas (
    id BIGINT NOT NULL PRIMARY KEY,
    prontuario VARCHAR(255) NOT NULL UNIQUE,
    id_curso INT NOT NULL,
    id_aluno BIGINT NOT NULL,
    previsao_conclusao YEAR NOT NULL,
    ano_ingresso YEAR NOT NULL,
    data_integralizacao VARCHAR(10),
    forma_ingresso VARCHAR(255) NOT NULL,
    instituicao_anterior VARCHAR(255),
    situacao_curso VARCHAR(255) NOT NULL,
    situacao_periodo VARCHAR(255),
    turma VARCHAR(255),
    email_academico VARCHAR(60),
    observacao_historico TEXT,
    observacoes TEXT,
    deleted_at TIMESTAMP,
    FOREIGN KEY(id_curso) REFERENCES cursos(id),
    FOREIGN KEY(id_aluno) REFERENCES alunos(id)
);
CREATE TABLE tipos_atendimento (
    id BIGINT NOT NULL PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL,
    deleted_at TIMESTAMP
);
CREATE TABLE agendamentos (
    id BIGINT NOT NULL PRIMARY KEY,
    id_tipo BIGINT NOT NULL,
    id_user INTEGER NOT NULL,
    dataPrevisto DATE,
    horaPrevistaInicio TIME,
    horaPrevistaFim TIME,
    formaAtendimento VARCHAR(255) NOT NULL,
    responsavel VARCHAR(15) NOT NULL,
    status VARCHAR(45) NOT NULL,
    dataRemarcada DATE,
    FOREIGN KEY(id_tipo) REFERENCES tipos_atendimentos(id)
);
CREATE TABLE agendamentos_matriculas (
    id BIGINT NOT NULL PRIMARY KEY,
    id_agendamento BIGINT NOT NULL,
    id_matricula BIGINT NOT NULL,
    FOREIGN KEY(id_agendamento) REFERENCES agendamentos(id),
    FOREIGN KEY(id_matricula) REFERENCES matriculas(id)
);
CREATE TABLE registros_atendimentos (
    id BIGINT NOT NULL PRIMARY KEY,
    id_agendamento BIGINT NOT NULL,
    dataRealizado DATE NOT NULL,
    horaRealizado TIME NOT NULL,
    comparecimentoFamiliar BOOLEAN,
    grauParentesco VARCHAR(60),
    resumo TEXT,
    deleted_at TIMESTAMP,
    FOREIGN KEY(id_agendamento) REFERENCES agendamentos(id)
);