<h3 align="center">
  :hammer_and_wrench: :computer:
  <br><br>
  Desafio de Migração de Dados
</h3>

<blockquote align="center">Colocando em prática conceitos básicos para a realização de uma migração de dados em PHP!</blockquote>

<p align="center">
  <img alt="License" src="https://shields.io/badge/PHP-grey?logo=php&style=flat">&nbsp;&nbsp;
  <img alt="License" src="https://shields.io/badge/MySQL-grey?logo=mysql&style=flat">&nbsp;&nbsp;
  <img alt="License" src="https://shields.io/badge/MariaDB-grey?logo=mariadb&style=flat">&nbsp;&nbsp;
  <img alt="License" src="https://img.shields.io/badge/license-MIT-%2304D361">
</p>

## :rocket: Sobre o Desafio:

Nesse desafio desenvolvi um script em PHP para migrar os dados entre dois sistemas médicos fictícios. Os dados do sistema legado foram extraídos no formato CSV e devem ser migrados para a estrutura do outro sistema que utiliza o banco de dados MySQL.

## :medical_symbol: Contextualizando:

Uma clínica médica está se atualizando e trocando de sistema. Para isso, é necessário migrar os dados dos seus pacientes e agendamentos para o novo sistema que será implantado, o MedicalChallenge. Os dados dos dois sistemas estão estruturados de formas diferentes, e o seu desafio é adequar e migrar os dados do sistema legado para o novo sistema. A clínica já cadastrou seus médicos e também alguns convênios, procedimentos, pacientes e agendamentos no sistema novo.

## :dart: Objetivos:

* Avaliar os conhecimentos técnicos na linguagem PHP;
* Avaliar os conhecimentos técnicos em bancos de dados MySQL;
* Avaliar a capacidade na resolução de problemas lógicos.



## Rodando localmente :car:

Pré -requisitos

- Docker
- Composer


Clone o projeto

```bash
  git clone https://github.com/AnthonyWendy/challange-amplimed.git
```

Entre no diretório do projeto

```bash
  cd challange-amplimed
```

Inicie o servidor

```bash
  docker-compose up
```

Execute os scripts do arquivo no banco mysql
```bash
  script/lib/script-database.sql
```

Execute o index.php pela navegador
```bash
  http://localhost:81/index.php
```
