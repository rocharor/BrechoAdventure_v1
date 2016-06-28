<?php
namespace Rocharor\Site\Models;

class EmailModel
{

    private $host = "smtp.gmail.com";

    private $debug = 0;

    private $mail;
    
    private $emailAdmin = 'rocharor@gmail.com';

    /**
     * 
     */
    public function __construct()
    {
        $this->mail = new \PHPMailer();
        $this->mail->CharSet = "utf-8";
        $this->mail->WordWrap = 50;
        $this->mail->IsHTML(true);
        $this->mail->IsSMTP();
        $this->mail->SMTPDebug = $this->debug;
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Host = $this->host;
        $this->mail->Port = 587;
        $this->mail->Username = 'brechoAdventure@gmail.com';
        $this->mail->Password = 'rghdirkeakjfzvdh';
        $this->mail->SetFrom('brechoAdventure@gmail.com', 'Brecho Adventure');
        $this->mail->AddReplyTo("noreplay@brechoAdventure.com", "Brecho Adventure");
    }

    /**
     * 
     * @param unknown $email
     * @param unknown $assunto
     * @param unknown $corpo
     * @return boolean
     */
    public function sendEmail($email, $assunto, $corpo)
    {
        $mail = $this->mail;
        $mail->Subject = $assunto;
        $mail->Body = $corpo;
        $mail->AddAddress($email);
        
        if ($mail->Send())
            return true;
        else
            return false;
    }

    /**
     * Envia o e-mail para usuários que os produtos foram aprovados
     * @param unknown $email
     * @return boolean
     */
    public function produtoAprovado($email)
    {
        global $smarty;
        
        $assunto = 'Parabéns seu produto foi aprovado.';
        $corpo = $smarty->fetch("email/produtoAprovado.html");
        $retorno = $this->sendEmail($email, $assunto, $corpo);
        
        return $retorno;
    }   

    /**
     * Envia uma resposta automática quando alguem envia umamensagam pelo contato do site
     * @param unknown $nome
     * @param unknown $email
     * @return boolean
     */
    public function respAutomaticaContato($nome, $email)
    {
        global $smarty;
        
        $assunto = "Resposta automatica";
        //$corpo = $smarty->fetch("email/respAutomaticaContato.html");
        $corpo    = "   <!DOCTYPE html>
                        <html lang='pt-BR'>
                            <head>
                                <meta charset='UTF-8'>
                            </head>
                            <body>
                                <table align='center' style='width: 600px;'>
                                    <tr>
                                        <td style='background-color: #F3BC55;' align='center'>
                                            <h3>BRECHÓ ADVENTURE<h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='background-color: #fedd7a; font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #414042; padding: 20px; line-height: 15px;'>
                                            <h2>Olá {$nome},</h2>
                                            <p>Recebemos seu email e retornaremos o mais rápido possivel.</p><br>

                                            <p><small>Att,</small><br><b style='color: #11254c'>Brech&oacute; Adventure</b></p>
                                            <hr style='border: 0; border-top: 1px solid #cecece;'>
                                            <p style='text-align: center; color: #777;'><small>** Este &eacute; um e-mail autom&aacute;tico. Favor n&atilde;o respond&ecirc;-lo **</small></p>
                                        </td>
                                    </tr>
                                </table>
                            </body>
                        </html>" ;        
        if ($this->sendEmail($email,$assunto,$corpo)){
            $this->avisoNovaMensagem();
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 
     * @return boolean
     */
    public function avisoNovaMensagem()
    {
        $assunto = "Nova Mensagem";
        $corpo = "Uma nova mensagem foi enviada";
        $retorno = $this->sendEmail($this->emailAdmin, $assunto, $corpo);
        
        return $retorno;
    }
    
    
    /**
     * Informa o Admin que existem produtos pendentes 
     */
    public function avisoNovoProduto()
    {
        $assunto = "Novo Produto";
        $corpo = "Um novo produto acaba de ser cadastrado e encontra-se pendente.";
        $retorno = $this->sendEmail($this->emailAdmin, $assunto, $corpo);
        
        return $retorno;
    }
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Método para recuperar senha
     *
     * @param mixed $dados_user
     */
    public function recuperarSenha($dados_user)
    {
        global $smarty;
    
        $mail = $this->mail;
        $smarty->assign("nome", $dados_user['nome']);
        $smarty->assign("email", $dados_user['email']);
        $smarty->assign("senha_ext", $dados_user['senha_ext']);
        $smarty->assign("data_cadastro", $dados_user['data_cadastro']);
    
        $mail->Subject = "Recuperação de senha";
    
        $html = $smarty->fetch("email/recuperarSenha.html");
        $mail->MsgHTML($html);
    
        $mail->AddAddress($dados_user['email']);
    
        if ($mail->Send())
            return true;
            else
                return false;
    }
}