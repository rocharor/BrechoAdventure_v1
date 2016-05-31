<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;
    use Rocharor\Site\Models\CadastroModel;
    use Rocharor\MinhaConta\Models\PerfilModel;

    class Perfil extends Controller{

        private $model;
        private $suario_id;

        public function __construct()
        {
            $this->model = new PerfilModel();
            $this->usuario_id = Sessao::pegaSessao('logado');
        }

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


            $ret = $this->model->updateUsuario($user_id,$_POST['dados']);

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

                $arrNomeFoto = explode('.',$arquivo_file['name']);
                $extencao = end($arrNomeFoto);
                $foto_nome = $usuario_id . '_' .date('d-m-Y_h_i_s') . '.' . $extencao;

                if(move_uploaded_file($arquivo_file['tmp_name'], _IMAGENS_.'cadastro/' . $foto_nome))
                    $foto_salva = true;
                else
                    $foto_salva = false;

                if($foto_salva) {
                    $user_id = Sessao::pegaSessao('logado');
                    $ret = $this->model->updateUsuario($user_id, '', $foto_nome);

                    if($ret){
                        $nome_imagem = Sessao::pegaSessao('nome_imagem');
                        if($nome_imagem != 'padrao.jpg')
                            unlink(_IMAGENS_.'cadastro/'.$nome_imagem);
                            Sessao::setaSessao(array('nome_imagem'=>$foto_nome));

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
