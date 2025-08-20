<?php
// Define o endereço de e-mail para onde as mensagens serão enviadas.
// Altere 'seu-email@exemplo.com' pelo seu endereço de e-mail.
$destinatario = "pgoncalves@outlook.pt";

// Verifica se o método de requisição é POST.
// Isso garante que o script só será executado quando o formulário for enviado.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitiza e valida os dados de entrada para evitar injeção de código e ataques XSS.
    $nome = htmlspecialchars(trim($_POST['nome']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $mensagem = htmlspecialchars(trim($_POST['mensagem']));

    // Verifica se os campos obrigatórios estão preenchidos.
    if (empty($nome) || empty($email) || empty($mensagem) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Se houver campos inválidos, retorna uma mensagem de erro.
        http_response_code(400); // Bad Request
        echo "Por favor, preencha todos os campos corretamente.";
        exit;
    }

    // Assunto do e-mail.
    $assunto = "Nova Mensagem de Contato do Portfólio de $nome";

    // Conteúdo do e-mail.
    $corpo_email = "Nome: $nome\n";
    $corpo_email .= "Email: $email\n\n";
    $corpo_email .= "Mensagem:\n$mensagem\n";

    // Cabeçalhos do e-mail.
    // O 'Reply-To' permite que você responda diretamente ao e-mail do remetente.
    $headers = "From: $nome <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Tenta enviar o e-mail.
    if (mail($destinatario, $assunto, $corpo_email, $headers)) {
        // Se o envio for bem-sucedido, retorna uma mensagem de sucesso.
        http_response_code(200); // OK
        echo "Sua mensagem foi enviada com sucesso!";
    } else {
        // Se houver um erro no envio, retorna uma mensagem de erro.
        http_response_code(500); // Internal Server Error
        echo "Oops! Ocorreu um erro e não foi possível enviar sua mensagem. Por favor, tente novamente mais tarde.";
    }

} else {
    // Redireciona o usuário de volta para o formulário se ele tentar acessar o script diretamente.
    http_response_code(403); // Forbidden
    echo "Acesso negado. Por favor, use o formulário de contato.";
}
?>