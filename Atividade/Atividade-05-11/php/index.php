<?php
$host = 'db'; 
$user = 'user_app'; 
$password = 'user_password';
$database = 'user_data_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Falha na Conexão: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    
    if (!empty($nome) && !empty($email)) {
        
        $stmt = $conn->prepare("INSERT INTO users (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);

        if ($stmt->execute()) {
            $message = "<div style='color: green;'> Usuário cadastrado com sucesso!</div>";
        } else {
            $message = "<div style='color: red;'> Erro ao cadastrar: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div style='color: orange;'> Por favor, preencha todos os campos.</div>";
    }
}


$result = $conn->query("SELECT id, nome, email, data_cadastro FROM users ORDER BY id DESC");

$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários - Docker PHP/MySQL</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        form { background: #f4f4f4; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; margin: 5px 0 10px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: #4CAF50; color: white; padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastro de Usuários</h2>
    
    <?php echo $message; ?>

    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        
        <input type="submit" value="Cadastrar Usuário">
    </form>

    <h2>Usuários Cadastrados</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Data de Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo $row['data_cadastro']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum usuário cadastrado ainda.</p>
    <?php endif; ?>

</div>

</body>
</html>