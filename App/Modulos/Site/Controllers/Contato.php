<?php
    namespace Rocharor\Site\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Site\Models\ContatoModel;
    use Rocharor\Site\Models\EmailModel;

    class Contato extends Controller{

        public function indexAction(){

            if(!empty($_POST)){
                $this->salvaMensagemAction($_POST);
            }else{
                $variaveis = ['pagina_main' => 'contato.html','active_3'=>'active','msg'=>''];
                $this->view('main',$variaveis);
            }
        }

        /**
         * Salvo os dados de contato no banco de dados e manda email
         * @param unknown $dados
         */
        Public function salvaMensagemAction($dados){

            $contatoModel = new ContatoModel();
            $retorno = $contatoModel->setMensagem($dados);

            if($retorno){
                $msg = '<div class="alert alert-success" align="center" style="width: 400px;">Mensagem enviada com sucesso</div>';
                $this->respAutomaticaContato($dados['nome'], $dados['email']);
            }else{
               $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Erro ao enviar a mensagem</div>';
            }

            $variaveis = ['pagina_main' => 'contato.html','active_3'=>'active','msg'=>$msg];

            $this->view('main',$variaveis);
        }

        /**
         * Gera dodos para enviar o email de resposta automatica de resposta do contato
         * @param unknown $nome
         * @param unknown $email
         * @return boolean
         */
        public function respAutomaticaContato($nome,$email){
            //global $smarty;

            $assunto = "Resposta automatica";

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
            //$smarty->assign ( "nome", $nome);
            //$html = $smarty->fetch ( "email/respContato.html");
            //$mail->MsgHTML ( $html );



            $emailModel = new EmailModel();
            if($emailModel->sendEmail($email,$assunto,$corpo)){
                return true;
            }else{
                return false;
            }
        }

    }
