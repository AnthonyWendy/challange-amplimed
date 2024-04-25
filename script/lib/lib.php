<?php

function dateNow(){
  date_default_timezone_set('America/Sao_Paulo');
  return date('d-m-Y \à\s H:i:s');
}

function criarTabelaPacientes($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS pacientes (
        cod_paciente INT NOT NULL,
        nome_paciente VARCHAR(255) NOT NULL,
        nasc_paciente DATE NOT NULL,
        pai_paciente VARCHAR(255) NOT NULL,
        mae_paciente VARCHAR(255) NOT NULL,
        cpf_paciente VARCHAR(14) NOT NULL,
        rg_paciente VARCHAR(12) NOT NULL,
        sexo_pac VARCHAR(1) NOT NULL,
        id_conv INT NOT NULL,
        convenio VARCHAR(255) NOT NULL,
        obs_clinicas VARCHAR(255) NOT NULL,
        PRIMARY KEY (cod_paciente)
    )";

    if(mysqli_query($conn, $sql)) {
        echo "Tabela 'pacientes' criada com sucesso no 0temp - MariaDB.<br>";
    } else {
        echo "Erro ao criar tabela: " . mysqli_error($conn) . "\n";
        exit();
    }
}

function criarTabelaAgendamentos($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS agendamentos (
        cod_agendamento INT NOT NULL,
        descricao VARCHAR(255),
        dia DATE NOT NULL,
        hora_inicio TIME NOT NULL,
        hora_fim TIME NOT NULL,
        cod_paciente INT NOT NULL,
        paciente VARCHAR(255) NOT NULL,
        cod_medico INT NOT NULL,
        medico VARCHAR(255) NOT NULL,
        cod_convenio INT NOT NULL,
        convenio VARCHAR(255) NOT NULL,
        procedimento VARCHAR(255) NOT NULL,
        PRIMARY KEY (cod_agendamento)
    )";

    if(mysqli_query($conn, $sql)) {
        echo "Tabela 'agendamentos' criada com sucesso no 0temp - MariaDB.<br>";
    } else {
        echo "Erro ao criar tabela: " . mysqli_error($conn) . "\n";
        exit();
    }
}

function popularTabelaPacientes($connTemp) {
    $start_time = microtime(true);

    $file = __DIR__.'/legacysystemdata/20210512_pacientes.csv';

    if(file_exists($file)) {
        echo "<br>--------------------------------------------inicio-popularTabelaPacientesMariaDB--------------------------------------------------------<br>";
        echo "Arquivo encontrado. Iniciando leitura...<br><br>";

        $handle = fopen($file, 'r');
        if ($handle !== false) {
            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                // Construir o INSERT para cada linha
                $query = "INSERT INTO pacientes (cod_paciente, nome_paciente, nasc_paciente, pai_paciente, mae_paciente, cpf_paciente, rg_paciente, sexo_pac, id_conv, convenio, obs_clinicas) VALUES ('$data[0]', '$data[1]', STR_TO_DATE('$data[2]', '%d/%m/%Y'), '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]')";

                if(mysqli_query($connTemp, $query)) {
                    echo "Linha importada com sucesso: " . implode(', ', $data) . "<br>";
                } else {
                    echo "Erro ao importar linha: " . implode(', ', $data) . "<br>";
                    echo "Erro: " . mysqli_error($connTemp) . "<br>";
                    break; // Interrompe o loop se houver um erro
                }
            }

            fclose($handle);
            echo "Importação concluída.";
        } else {
            echo "Erro ao abrir o arquivo.<br>";
        }
    } else {
        echo "Arquivo não encontrado. Verifique o caminho e tente novamente.<br>";
    }

    $end_time = microtime(true);
    $execution_time = ($end_time - $start_time);
    echo "Tempo de execução: " . round($execution_time, 2) . " segundos";

    echo "<br>--------------------------------------------fim---------------------------------------------------------<br>";
}

function popularTabelaAgendamentos($connTemp) {
    $start_time = microtime(true);

    $file = __DIR__.'/legacysystemdata/20210512_agendamentos.csv';

    if(file_exists($file)) {
        echo "<br>--------------------------------------------inicio-popularTabelaAgendamentos-MariaDB-------------------------------------------------------<br>";
        echo "Arquivo encontrado. Iniciando leitura...<br><br>";

        $handle = fopen($file, 'r');
        if ($handle !== false) {
            // Ignorar a primeira linha (cabeçalho)
            fgetcsv($handle);

            // Loop para ler o arquivo linha por linha
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                // Construir o INSERT para cada linha
                $query = "INSERT INTO agendamentos (cod_agendamento, descricao, dia, hora_inicio, hora_fim, cod_paciente, paciente, cod_medico, medico, cod_convenio, convenio, procedimento) VALUES ('$data[0]', '$data[1]', STR_TO_DATE('$data[2]', '%d/%m/%Y'), '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]')";

                // Executar o INSERT
                if(mysqli_query($connTemp, $query)) {
                    echo "Linha importada com sucesso: " . implode(', ', $data) . "<br>";
                } else {
                    echo "Erro ao importar linha: " . implode(', ', $data) . "<br>";
                    echo "Erro: " . mysqli_error($connTemp) . "<br>";
                    break; // Interrompe o loop se houver um erro
                }
            }

            fclose($handle);
            echo "Importação concluída.";
        } else {
            echo "Erro ao abrir o arquivo.<br>";
        }
    } else {
        echo "Arquivo não encontrado. Verifique o caminho e tente novamente.<br>";
    }

    $end_time = microtime(true);
    $execution_time = ($end_time - $start_time);
    echo "Tempo de execução: " . round($execution_time, 2) . " segundos";

    echo "<br>--------------------------------------------fim---------------------------------------------------------<br>";
}

function getListaPacientes($connMysql, $connMaria) {
    $sql = "SELECT * FROM pacientes";
    $result = $connMaria->query($sql);

    if ($result === false) {
        echo "Erro ao executar a consulta: " . $connMaria->error;
        return array();
    }

    $inserts = array();

    while ($row = $result->fetch_assoc()) {
        $id_convenio = obterIdConvenio($connMysql, $row['convenio']); // Obtém o id_convenio usando a função obterIdConvenio

        // Evita a conversão de caracteres especiais
        $nome_paciente = $row['nome_paciente'];
        $sexo_paciente = $row['sexo_pac'];
        $nascimento_paciente = $row['nasc_paciente'];
        $cpf_paciente = $row['cpf_paciente'];
        $rg_paciente = $row['rg_paciente'];
        $cod_paciente = $row['cod_paciente'];

        $insert = "INSERT INTO pacientes (nome, sexo, nascimento, cpf, rg, id_convenio, cod_referencia) VALUES (";
        $insert .= "'" . $nome_paciente . "', ";
        $insert .= "'" . ($sexo_paciente == 'M' ? 'Masculino' : 'Feminino') . "', ";
        $insert .= "'" . $nascimento_paciente . "', ";
        $insert .= "'" . $cpf_paciente . "', ";
        $insert .= "'" . $rg_paciente . "', ";
        $insert .= $id_convenio . ", ";
        $insert .= "'" . $cod_paciente . "'";
        $insert .= ")";

        $inserts[] = $insert;
    }

    return $inserts;
}

function getListAgendamentos($connMysql, $connMaria) {
    $sql = "SELECT * FROM agendamentos";
    $result = $connMaria->query($sql);

    if ($result === false) {
        echo "Erro ao executar a consulta: " . $connMaria->error;
        return array();
    }

    $inserts = array();

    while ($row = $result->fetch_assoc()) {
        $id_paciente = getIdPaciente($connMysql, $row['paciente']);
        $id_profissional = getIdMedico($connMysql, $row['medico']);
        $dh_inicio = $row['hora_inicio'];
        $dh_fim = $row['hora_fim'];
        $id_convenio = obterIdConvenio($connMysql, $row['convenio']);
        $id_procedimento = getIdProcedimento($connMysql, $row['procedimento']);
        $observacoes = $row['descricao'];

        $insert = "INSERT INTO MedicalChallenge.agendamentos (id_paciente, id_profissional, dh_inicio, dh_fim, id_convenio, id_procedimento, observacoes) VALUES (";
        $insert .= "'" . $id_paciente . "', ";
        $insert .= "'" . $id_profissional . "', ";
        $insert .= "'" . date('Y-m-d H:i:s', strtotime($dh_inicio)) . "', ";
        $insert .= "'" . date('Y-m-d H:i:s', strtotime($dh_fim)) . "', ";
        $insert .= "'" . $id_convenio . "', ";
        $insert .= "'" . $id_procedimento . "', ";
        $insert .= "'" . $observacoes . "'";
        $insert .= ")";

        $inserts[] = $insert;
    }

    return $inserts;
}

function getIdPaciente($conn, $nome_paciente) {
    $sql = "SELECT id FROM pacientes WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome_paciente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false || $result->num_rows == 0) {
        return "";
    }

    $row = $result->fetch_assoc();
    return $row['id'];
}

function getIdMedico($conn, $nome_medico) {
    if (empty($nome_medico)) {
        return "";
    }

    $sql = "SELECT id FROM profissionais WHERE nome = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return handleQueryError($conn, "Erro ao preparar a consulta: ");
    }

    $stmt->bind_param("s", $nome_medico);
    $stmt->execute();

    if ($stmt->error) {
        return handleQueryError($stmt, "Erro ao executar a consulta: ");
    }

    $result = $stmt->get_result();

    if (!$result) {
        return handleQueryError($stmt, "Erro ao obter o resultado da consulta: ");
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    $stmtInsert = $conn->prepare("INSERT INTO profissionais (nome) VALUES (?)");
    if (!$stmtInsert) {
        return handleQueryError($conn, "Erro ao preparar a consulta de inserção: ");
    }

    $stmtInsert->bind_param("s", $nome_medico);
    $stmtInsert->execute();

    if ($stmtInsert->error) {
        return handleQueryError($stmtInsert, "Erro ao executar a consulta de inserção: ");
    }

    return $stmtInsert->insert_id;
}

function handleQueryError($conn, $errorMessagePrefix) {
    echo $errorMessagePrefix . $conn->error;
    return "";
}

function getIdProcedimento($conn, $nome_procedimento) {
    if (empty($nome_procedimento)) {
        return "";
    }

    $sql = "SELECT id FROM procedimentos WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome_procedimento);
    $stmt->execute();
    $result = $stmt->get_result();

    $sqlInsert = "INSERT INTO procedimentos (nome) VALUES (?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("s", $nome_procedimento);
    $stmtInsert->execute();

    return $stmtInsert->insert_id;
}

function obterIdConvenio($connMysql, $nomeConvenio) {
    if (empty($nomeConvenio)) {
        return null;
    }

    $query = "SELECT id FROM convenios WHERE nome = ?";
    $stmt = $connMysql->prepare($query);

    if ($stmt === false) {
        echo "Erro ao preparar a consulta: " . $connMysql->error;
        return null;
    }

    $stmt->bind_param("s", $nomeConvenio);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    $insertQuery = "INSERT INTO MedicalChallenge.convenios (nome) VALUES (?)";
    $insertStmt = $connMysql->prepare($insertQuery);
    $insertStmt->bind_param("s", $nomeConvenio);
    $insertStmt->execute();

    return $insertStmt->insert_id;
}

function cadastrar($tipo, $inserts, $connTemp) {
    $start_time = microtime(true);

    echo "<br>--------------------------------------------inicio-cadastrar-$tipo-MySQL-------------------------------------------------------<br>";
    echo "Iniciando cadastro dos $tipo...<br><br>";

    foreach ($inserts as $insert) {
        $stmt = $connTemp->prepare($insert);
        if ($stmt === false) {
            echo "Erro ao preparar a consulta: " . $connTemp->error . "<br>";
            break;
        }

        if ($stmt->execute()) {
            echo "$tipo importado com sucesso<br>";
        } else {
            echo "Erro ao importar $tipo<br>";
            echo "Erro: " . $stmt->error . "<br>";
            break;
        }
    }

    echo "Cadastro de $tipo concluído.";

    $end_time = microtime(true);
    $execution_time = ($end_time - $start_time);
    echo "Tempo de execução: " . round($execution_time, 2) . " segundos";
    echo "<br>--------------------------------------------fim---------------------------------------------------------<br>";
}


function criandoEstruturaMariaDB($connTemp) {
    echo "<br>--------------------------------------------inicio-CriarTabelaMariaDB--------------------------------------------------------<br>";
    criarTabelaPacientes($connTemp);
    criarTabelaAgendamentos($connTemp);
    echo "<br>--------------------------------------------fim--------------------------------------------------------<br>";
}

function inserirDadosNoMariaDb($connTemp) {
    popularTabelaPacientes($connTemp);
    popularTabelaAgendamentos($connTemp);
}

function inserirDadosNoMysql($connMysql, $connMaria) {
    $insertsPacientes = getListaPacientes($connMysql, $connMaria);
    $insertsAgendamentos = getListAgendamentos($connMysql, $connMaria);

    cadastrar("Agendamentos", $insertsAgendamentos, $connMysql);
    cadastrar("Agendamentos", $insertsPacientes, $connMysql);
}

function gerarDumpMysql(){
    $output = shell_exec('mysqldump -h mysql -u root -proot MedicalChallenge > /var/www/html/lib/backup.sql');

    if ($output === null) {
        echo "Backup gerado com sucesso!";
    } else {
        echo "Erro ao gerar backup: $output";
    }
}

?>