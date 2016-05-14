<?php
namespace Rocharor\Site\Models;

class EmailModel{

    private $host = "smtp.gmail.com"; // servidor SMTP
    private $debug = 0;
    private $mail;

    public function __construct(){
        $this->mail = new \PHPMailer();
        $this->mail->CharSet = "utf-8";
        $this->mail->WordWrap = 50;
        $this->mail->IsHTML(true);
        $this->mail->IsSMTP ();
        $this->mail->SMTPDebug = $this->debug;
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Host = $this->host;
        $this->mail->Port = 587;
        $this->mail->Username = 'brechoAdventure@gmail.com';
        $this->mail->Password = 'rghdirkeakjfzvdh';
        $this->mail->SetFrom ('brechoAdventure@gmail.com', 'Brecho Adventure');
        $this->mail->AddReplyTo ("noreplay@brechoAdventure.com", "Brecho Adventure");
    }

    public function sendEmail($email,$assunto,$corpo){
        $mail = $this->mail;
        $mail->Subject = $assunto;
        $mail->Body = $corpo;
        $mail->AddAddress($email);

        if($mail->Send())
            return true;
        else
            return
        false;
    }




    /**
    * Método para recuperar senha
    *
    * @param mixed $dados_user
    */
    public function recuperarSenha($dados_user){
        global $smarty;

        $mail = $this->mail;
        $smarty->assign ( "nome", $dados_user['nome']);
        $smarty->assign ( "email", $dados_user['email']);
        $smarty->assign ( "senha_ext", $dados_user['senha_ext']);
        $smarty->assign ( "data_cadastro", $dados_user['data_cadastro']);


        $mail->Subject = "Recuperação de senha";
        /*$mail->Body    = "<p>Olá {$dados_user['nome']}, segue abaixo os dados de acesso:</p> <br />
                          <p>Email: {$dados_user['email']}</p>
                          <p>Senha: {$dados_user['senha_ext']}</p>
                          <p>Data de cadastro:  {$dados_user['data_cadastro']}</p>" ;*/

        $html = $smarty->fetch ("email/recuperarSenha.html");
        $mail->MsgHTML ( $html );

        $mail->AddAddress($dados_user['email']);

        if($mail->Send())
            return true;
        else
            return
         false;
    }

    public function respAutomaticaContato($nome,$email){
        global $smarty;

        $mail = $this->mail;
        $mail->Subject = "Resposta automatica";

         $mail->Body    = " <!DOCTYPE html>
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
        //$smarty->assign ( "nome", $nome);
        //$html = $smarty->fetch ( "email/respContato.html");
        //$mail->MsgHTML ( $html );

        $mail->AddAddress($email);

        if($mail->Send())
            return true;
        else
            return
         false;
    }
}