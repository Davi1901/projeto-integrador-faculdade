<?php 
require_once '../../../db_config.php'; // Caminho relativo (correto)

// Lógica de login PHP
$login_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $login_error = "Por favor, preencha todos os campos.";
    } else {
        // Buscar usuário no banco
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $senha_valida = false;

            // --- INÍCIO DA CORREÇÃO PARA APRESENTAÇÃO ---
            // Adiciona uma verificação "mestra" para o admin
            // A senha agora é 'barcelona2026' APENAS para o admin@duhype.com
            if ($usuario['email'] == 'admin@duhype.com' && $senha == 'barcelona2026') {
                $senha_valida = true; // Senha mestra para a apresentação!
            }
            // --- FIM DA CORREÇÃO ---
            
            // Verificação normal para outros usuários (se eles se cadastrarem)
            elseif (password_verify($senha, $usuario['senha_hash'])) {
                $senha_valida = true;
            }

            if ($senha_valida) {
                // Login bem-sucedido
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_nome'] = $usuario['nome']; 
                
                // VERIFICA SE É ADMIN!
                if ($usuario['is_admin'] == 1) {
                    $_SESSION['is_admin'] = true;
                    // CAMINHO DE REDIRECT CORRIGIDO
                    header("Location: " . BASE_URL . "/financeiro/financeiro.php"); 
                } else {
                    $_SESSION['is_admin'] = false;
                    // CAMINHO DE REDIRECT CORRIGIDO
                    header("Location: " . BASE_URL . "/src/pages/fedd/feed.php"); 
                }
                exit;
                
            } else {
                // Login inválido
                $login_error = "Email ou senha incorretos.";
            }
        } else {
            // Login inválido
            $login_error = "Email ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Login</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/pages/login/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

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
    <div class="left-section">
      </div>

    <div class="curved-shape">
      <svg viewBox="0 0 100 100" preserveAspectRatio="none">
        <path d="M 0,0 L 100,0 C 100,50 80,100 0,100 Z" fill="#f5f5f5"/>
      </svg>
    </div>

    <div class="right-section">
      <div class="login-container">
        <form class="login-form" id="loginForm" method="POST" action="<?php echo BASE_URL; ?>/src/pages/login/login.php">
          <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Digite seu email" required>
          </div>
          <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
          </div>
          <?php if (!empty($login_error)): ?>
              <p style="color: red; font-size: 0.9em;"><?php echo $login_error; ?></p>
          <?php endif; ?>
          <button type="submit" class="botao-entrar">Entrar</button>
        </form>

        <p class="or-separator">ou</p>

        <div class="social-login">
          <a href="https://accounts.google.com" target="_blank" rel="noopener noreferrer" class="link-sem-sublinhado">
            <button class="social-btn google">
              <i class="fab fa-google"></i>Login com Google
            </button>
          </a>
          <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" class="link-sem-sublinhado">
            <button class="social-btn facebook">
              <i class="fab fa-facebook"></i> Login com Facebook
            </button>
          </a>
          <a href="https://appleid.apple.com" target="_blank" rel="noopener noreferrer" class="link-sem-sublinhado">
            <button class="social-btn apple">
              <i class="fab fa-apple"></i> Login com Apple
            </button>
          </a>
        </div>

        <div class="signup-link">
          Não tem conta? <a href="<?php echo BASE_URL; ?>/src/pages/cadastro/cadastro.php" class="link-sem-sublinhado">Cadastrar-se</a>
        </div>
      </div>
    </div>
  </main>

  <!-- SCRIPT DE LIMPEZA: Garante que dados de usuários anteriores não vazem -->
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          localStorage.removeItem('endereco_usuario'); // Remove o endereço antigo
          localStorage.removeItem('carrinho');         // Remove o carrinho antigo (opcional)
          sessionStorage.clear();                      // Limpa dados da sessão do navegador
      });
  </script>
  
  <?php include '../../../libras/libras.php'; ?>

  </body>
</html>