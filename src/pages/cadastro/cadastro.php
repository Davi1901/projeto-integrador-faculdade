<?php 
require_once '../../../db_config.php'; 

// Adicione isso para limpar sessões antigas ao entrar na tela de cadastro
if (session_status() == PHP_SESSION_NONE) { session_start(); }
session_unset();
session_destroy();
session_start();

// Lógica de cadastro PHP pode vir aqui
$cadastro_error = '';
$cadastro_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Isso é um exemplo simples. Você precisará de mais campos no seu form.
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $senha_confirma = $_POST['senha_confirma'] ?? '';
    // Você deve adicionar um campo 'nome' ao seu formulário
    $nome = $_POST['nome'] ?? $email; // Pega o nome ou usa o email como padrão

    if (empty($email) || empty($senha) || empty($senha_confirma)) {
        $cadastro_error = "Todos os campos são obrigatórios.";
    } elseif ($senha !== $senha_confirma) {
        $cadastro_error = "As senhas não coincidem.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $cadastro_error = "Formato de e-mail inválido.";
    } else {
        // Verificar se o e-mail já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $cadastro_error = "Este e-mail já está cadastrado.";
        } else {
            // Criar novo usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            // Inserindo nome também
            $stmt = $pdo->prepare("INSERT INTO usuarios (email, nome, senha_hash) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$email, $nome, $senha_hash]);
                $cadastro_success = "Cadastro realizado com sucesso! Você já pode fazer login.";
                
                // Opcional: Logar o usuário automaticamente após o cadastro
                // $_SESSION['user_id'] = $pdo->lastInsertId();
                // $_SESSION['user_nome'] = $nome;
                // $_SESSION['is_admin'] = false;
                // header("Location: " . BASE_URL . "/src/pages/fedd/feed.php"); // Link corrigido
                // exit;

            } catch (PDOException $e) {
                $cadastro_error = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login DU.HYPE</title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/pages/cadastro/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- Adicione este script logo após o body para limpar o LocalStorage -->
    <script>
        localStorage.removeItem('endereco_usuario');
        localStorage.removeItem('carrinho');
    </script>

    <div class="header-container">
        <header class="header">
            <div class="logo">
                <img src="<?php echo BASE_URL; ?>/src/assets/Images/LogoDu.Hype.svg" alt="Logo duhype">
            </div>
            <nav class="nav">
                <a href="<?php echo BASE_URL; ?>/home.php">Início</a>
                <a href="<?php echo BASE_URL; ?>/src/pages/marcas/marcas.php">Marcas</a>
                <a href="<?php echo BASE_URL; ?>/src/pages/fedd/feed.php">Feed</a>
                <a href="<?php echo BASE_URL; ?>/src/pages/novidades/novidades.php">Novidades</a>
            </nav>
            <div class="icons">
                <a href="<?php echo BASE_URL; ?>/src/pages/fedd/feed.php"><i class="fas fa-search"></i></a>
                <a href="<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php"><i class="fas fa-shopping-cart"></i></a>
                <a href="<?php echo BASE_URL; ?>/src/pages/cadastro/cadastro.php"><i class="fas fa-user"></i></a>
            </div>
        </header>
    </div>
    
    <main class="main-content">
        <section class="form-section">
            <div class="login-container">
                <form class="login-form" method="POST" action="<?php echo BASE_URL; ?>/src/pages/cadastro/cadastro.php" autocomplete="off">
                    
                    <!-- TRUQUE: Inputs falsos para enganar o autofill do navegador -->
                    <input type="text" style="display:none; opacity:0; height:0; width:0; position:absolute;">
                    <input type="password" style="display:none; opacity:0; height:0; width:0; position:absolute;">
                    <!-- FIM DO TRUQUE -->

                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nome" placeholder="Digite seu nome" required autocomplete="off">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Digite seu email" required autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="senha" placeholder="Crie uma senha" required autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="senha_confirma" placeholder="Confirme sua senha" required autocomplete="new-password">
                    </div>
                    
                    <?php if (!empty($cadastro_error)): ?>
                        <p style="color: red; font-size: 0.9em;"><?php echo $cadastro_error; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($cadastro_success)): ?>
                        <p style="color: green; font-size: 0.9em;"><?php echo $cadastro_success; ?></p>
                    <?php endif; ?>

                    <button type="submit" class="social-btn apple" style="margin-top: 20px; border: none;">Criar Conta</button>
                </form>
                
                <div class="or-separator">
                    <span>ou</span>
                </div>
                
                <div class="social-login">
                    <a href="https://accounts.google.com" target="_blank" rel="noopener noreferrer">
                        <button class="social-btn google">
                            <i class="fab fa-google"></i> Registre-se com Google
                        </button>
                    </a>
                    <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer">
                        <button class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i> Registre-se com Facebook
                        </button>
                    </a>
                    <a href="https://account.apple.com" target="_blank" rel="noopener noreferrer">
                        <button class="social-btn apple">
                            <i class="fab fa-apple"></i> Registre-se com Apple
                        </button>
                    </a>
                </div>
                
                <div class="signup-link">
                    Já tem conta? <a href="<?php echo BASE_URL; ?>/src/pages/login/login.php">Fazer Login</a>
                </div>
            </div>
        </section>

        <section class="image-section">
            </section>
    </main>
    
    <?php include '../../../libras/libras.php'; ?>

</body>
</html>