<?php
// Caminho: finadoVini-1/DU.Hype/logout.php

// Puxa o db_config.php para iniciar a sessão e ter a BASE_URL
require_once 'db_config.php'; 

// Limpa todas as variáveis da sessão (como $_SESSION['user_id'] e $_SESSION['is_admin'])
session_unset();

// Destrói completamente a sessão
session_destroy();

// Redireciona o usuário de volta para a página de login
header("Location: " . BASE_URL . "/src/pages/login/login.php");
exit;
?>