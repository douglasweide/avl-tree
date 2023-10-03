<?php 

class No {

    public $valor, $altura, $esquerda, $direita;

    public function __construct($valor){
        $this->valor = $valor; 
        $this->altura = 1;
        $this->esquerda = null;
        $this->direita = null;
    }
}


class AVL{
    public $root; 

    private function verificaAltura($no){
        if ($no) {
            return $no->altura;
        } else {
            return 0;
        }
    }

    private function fatorDeBalanceamento($no){
        if ($no) {
            return $this->verificaAltura($no->esquerda) - $this->verificaAltura($no->direita);
        } else {
            return 0;
        }
    }

    private function novaAltura($no){
        $no->altura = 1 + max($this->verificaAltura($no->esquerda), $this->verificaAltura($no->direita));
    }

    private function rotacaoEsquerda($x){
        
        $y = $x->direita;
        $varAuxiliar = $y->esquerda;
        $y->esquerda = $x;
        $x->direita = $varAuxiliar;
    
        $this->novaAltura($x);
        $this->novaAltura($y);
    
        return $y;
    }

    private function rotacaoDireita($y){
        $x = $y->esquerda;
        $varAuxiliar = $x->direita;

        $x->direita = $y;
        $y->esquerda = $varAuxiliar;

        $this->novaAltura($y);
        $this->novaAltura($x);

        return $x;
    }

    public function Insere($root, $valor){
        if ($root === null) {
            return new No($valor);
        }

        if ($valor < $root->valor) {
            $root->esquerda = $this->insere($root->esquerda, $valor);
        } elseif ($valor > $root->valor) {
            $root->direita = $this->insere($root->direita, $valor);
        } else {
            // Possivelmente um valor duplicado
            return $root;
        }

        // Atualiza a altura do no atual 
        $this->novaAltura($root);

        // Rotaciona para manter balanceado 
        $fb = $this->fatorDeBalanceamento($root);


        if ($fb > 1 && $valor < $root->esquerda->valor) {
            return $this->rotacaoDireita($root);
        }

    
        if ($fb < -1 && $valor > $root->direita->valor) {
            return $this->rotacaoEsquerda($root);
        }

        if ($fb > 1 && $valor > $root->esquerda->valor) {
            $root->esquerda = $this->rotacaoEsquerda($root->esquerda);
            return $this->rotacaoDireita($root);
        }

        if ($fb < -1 && $valor < $root->direita->valor) {
            $root->direita = $this->rotacaoDireita($root->direita);
            return $this->rotacaoEsquerda($root);
        }

        return $root;
    }


    public function deleta($root, $valor)
    {
        if ($root === null) {
            return null;
        }

        if ($valor < $root->valor) {
            $root->esquerda = $this->deleta($root->esquerda, $valor);
        } elseif ($valor > $root->valor) {
            $root->direita = $this->deleta($root->direita, $valor);
        } else {

            // No com um filho ou sem nenhum filho 
            if ($root->esquerda === null || $root->direita === null) {
                $varAuxiliar = ($root->esquerda !== null) ? $root->esquerda : $root->direita;

                // Sem filho
                if ($varAuxiliar === null) {
                    $varAuxiliar = $root;
                    $root = null;
                } else {
                    // Um filho
                    $root = $varAuxiliar; 
                }

                unset($varAuxiliar);
            } else {
                // No com 2 filhos 
                $varAuxiliar = $this->menorNo($root->direita); // Procura o  "inorder successor" - menor valor da subáravore da direita - 

                $root->valor = $varAuxiliar->valor;

                $root->direita = $this->deleta($root->direita, $varAuxiliar->valor);
            }
        }

        // Verifica se tem apenas um no 
        if ($root === null) {
            return $root;
        }

        // atualiza a altura do no atual 
        $this->novaAltura($root);

        // Rotaciona para manter balanceado 
        $fb = $this->fatorDeBalanceamento($root);

        if ($fb > 1 && $this->fatorDeBalanceamento($root->esquerda) >= 0) {
            return $this->rotacaoDireita($root);
        }

        if ($fb < -1 && $this->fatorDeBalanceamento($root->direita) <= 0) {
            return $this->rotacaoEsquerda($root);
        }

        if ($fb > 1 && $this->fatorDeBalanceamento($root->esquerda) < 0) {
            $root->esquerda = $this->rotacaoEsquerda($root->esquerda);
            return $this->rotacaoDireita($root);
        }

        if ($fb < -1 && $this->fatorDeBalanceamento($root->direita) > 0) {
            $root->direita = $this->rotacaoDireita($root->direita);
            return $this->rotacaoEsquerda($root);
        }

        return $root;
    }


    public function busca($root, $valor)
    {
        if ($root === null || $root->valor === $valor) {
            return $root;
        }

        if ($valor < $root->valor) {
            return $this->busca($root->esquerda, $valor);
        }

        return $this->busca($root->direita, $valor);
    }


    private function menorNo($no)
    {
        $atual = $no;
        while ($atual->esquerda !== null) {
            $atual = $atual->esquerda;
        }
        return $atual;
    }

    // funções principais - INSERCAO - DELECAO - BUSCA
    public function insereValor($valor)
    {
        $this->root = $this->insere($this->root, $valor);
    }

    public function deletaValor($valor)
    {
        $this->root = $this->deleta($this->root, $valor);
    }

    public function buscaValor($valor)
    {
        return $this->busca($this->root, $valor);
    }

    public function emOrdem($no)
    {
        if ($no !== null) {
            $this->emOrdem($no->esquerda);
            echo $no->valor . " ";
            $this->emOrdem($no->direita);
        }
    }
}

// Valores de Exemplo 

echo "----------- ARVORE BALANCEADA ----------------\n";
$avl = new AVL();
$avl->insereValor(11);
$avl->insereValor(22);
$avl->insereValor(30);
$avl->insereValor(12);
$avl->insereValor(9);
$avl->insereValor(78);
$avl->insereValor(3);
$avl->emOrdem($avl->root);
echo PHP_EOL;


echo "----------- DELECAO ----------------\n";
// Insira o valor que deseja deletar abaixo:
$avl->deletaValor(22);
$avl->emOrdem($avl->root);
echo PHP_EOL;


echo "----------- BUSCA ----------------\n";
// Insira o valor que deseja buscar abaixo:
$buscaResultado = $avl->buscaValor(11);
if ($buscaResultado  !== null){
    echo "Valor Encontrado\n";
} else {
    echo "Valor Não Encontrado ou Nenhum Valor Buscado\n";
}


