<?php 
require_once '../db_config.php'; 

// Lógica de login PHP
$login_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Credenciais de admin (exemplo)
    // No futuro, busque no DB por um usuário com role 'admin'
    if ($email === "admin@site.com" && $senha === "1234") {
        $_SESSION['user_id'] = 'admin_master';
        $_SESSION['user_nome'] = 'Admin Principal';
        $_SESSION['is_admin'] = true;
        
        // Redirecionar para o painel financeiro
        header("Location: /DU.Hype/financeiro/financeiro.php");
        exit;
    } else {
        $login_error = "Email ou senha de administrador incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Painel Administrativo - Login</title>

    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
    <div class="login-container">
        <div class="left-side">
            <img src="/DU.Hype/src/assets/Images/imagem_fundo.jpg" alt="Imagem Decorativa" />
        </div>

        <div class="right-side">
            <div class="form-wrapper">
                <h2>Login do Administrador</h2>

                <form method="POST" action="login_adm.php">
                    <div class="input-box">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required />
                    </div>

                    <div class="input-box">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required />
                    </div>

                    <?php if (!empty($login_error)): ?>
                        <p style="color: red; font-size: 0.9em; margin-top: 10px;"><?php echo $login_error; ?></p>
                    <?php endif; ?>

                    <button type="submit" class="btn">Entrar</button>
                </form>

                <p class="separator">ou entre com</p>

                <div class="social-login">
                    <a href="#" class="social-icon"><i class="fab fa-google"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-apple"></i></a>
                </div>

                <p class="register">
                    Não tem conta? <a href="#">Cadastrar-se</a>
                </p>
            </div>
        </div>
    </div>
    

    <?php include '../libras/libras.php'; ?>

    </body>
</html>