<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;
    use Rocharor\Site\Models\CadastroModel;
    use Rocharor\MinhaConta\Models\PerfilModel;

    class Perfil extends Controller{

        public function indexAction()
        {
            $usuario_id = Sessao::pegaSessao('logado');

            $cadastroModel = new CadastroModel();
            $dadosUsuario = $cadastroModel->getUsuario(['id'=>$usuario_id]);

            $variaveis = ['pagina_main' => VIEWS_MC.'perfil.html','dadosUsuario'=>$dadosUsuario[0]];

            $this->view('main',$variaveis);
        }

        public function updatePerfilAction()
        {

            $user_id = Sessao::pegaSessao('logado');

            $objPerfil = new PerfilModel();
            $ret = $objPerfil->updateUsuario($user_id,$_POST['dados']);

            if($ret){
                $retorno = array('sucesso'=>true,
                                 'mensagem'=>'Dados alterados com sucesso.');
            }else{
                $retorno = array('sucesso'=>false,
                                 'mensagem'=>'Erro ao alterar dados, tente novamente!');
            }

            echo json_encode($retorno);
            exit();

        }


        /**
        * Faz alteração da foto de perfil
        *
        */
        public function updateFotoAction()
        {
            $arquivo_file = $_FILES['arquivo'];

            if($this->validaExtImagem([$arquivo_file])){
                
                $usuario_id = Sessao::pegaSessao('logado');
                
                $arquivo_file['nm_arq']   = $usuario_id . '_' . date('dmY_his');

                if(move_uploaded_file($arquivo_file['tmp_name'], _IMAGENS_.'cadastro/' . $arquivo_file['nm_arq']))
                    $foto_salva = true;
                else
                    $foto_salva = false;

                if($foto_salva) {
                    $user_id = Sessao::pegaSessao('logado');
                    $perfilModel = new PerfilModel();
                    $ret = $perfilModel->updateUsuario($user_id, $arquivo_file, true);

                    if($ret){
                        $nome_imagem = Sessao::pegaSessao('nome_imagem');
                        if($nome_imagem != 'padrao.jpg')
                            unlink(_IMAGENS_.'cadastro/'.$nome_imagem);
                            Sessao::setaSessao(array('nome_imagem'=>$arquivo_file['nm_arq']));

                        $retorno = array('sucesso'=>true,
                                         'mensagem'=>'Foto alterada com sucesso.');
                    }else{
                        $retorno = array('sucesso'=>false,
                                         'mensagem'=>'Erro ao alterar imagem , tente novamente! cod-U1');
                    }
                }else{
                    $retorno = array('sucesso'=>false,
                                     'mensagem'=>'Erro ao alterar imagem , tente novamente!');
                }

                echo json_encode($retorno);
                exit();

            }else{
                $retorno = array('sucesso'=>false, 'mensagem'=>'Extensão não permitida');
                echo json_encode($retorno);
                exit();
            }
        }
    }
