<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produtos extends CI_Controller {
  // GET / produtos   -> pegar todos os produtos

	public function index()
	{
     // fazer uma query no banco de dados, que busca todos os produtos 
     $produto = $this->db->get('produtos')->result_array();

     $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($produto));
	}

 // GET /produtos/{id}   -> pegar um produto especifico
  public function buscar($id) {
  $produto = $this->db
      ->get_where('produtos', ['id' => $id])
      ->row_array(); 
   
      if(!$produto) {
         return $this->output 
          ->set_content_type('application/json')
          ->set_status_header(400)
          ->set_output(json_encode(['erro' => 'produto não foi encontrado']));
      }

     $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($produto));
  }

 // POST /produtos       -> adiciona um produto
 public function criar() {
    $produto = json_decode(file_get_contents('php://input'), true);
       if (empty($produto['nome'] || !isset($produto['preco']))) { 
           return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(['erro' => 'nome e preco sao obrigatorios']));
    }
    $this->db->insert('produtos', $produto);
    $id = $this->db->insert_id();
    
    $this->output
     ->set_content_type('application/json')
     ->set_status_header(201)
     ->set_output(json_encode(['id' => $id] + $produtos));
    }
 // PUT /produtos        -> alterar/edita um produto
    public function atualizar ($id) {
      $produto = $this->db
      ->get_where('produtos', ['id' => $id])
      ->row_array(); 
   
      if(!$produto) {
         return $this->output 
          ->set_content_type('application/json')
          ->set_status_header(400)
          ->set_output(json_encode(['erro' => 'produto não foi encontrado']));
      }
    $dadosDigitados = json_decode(file_get_contents('php://input'), true);
          if (empty($dadosDigitados['nome']) || !empty($dados['preco'])) {
           return $this->output
             ->set_content_type('application/json')
             ->set_status_header(400)
             ->set_output(json_encode(['erro' => 'nome e preco sao obrigatorios']));
    }
    $this->db->where(['id' => $id])->update('produtos', $dadosDigitados);
             $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output(json_encode(['id' => $id] + $dadosDigitados));
} 

 // DELETE /produtos     -> deleta um produto
   public function apagar ($id) {
       $produto = $this->db
        ->get_where('produtos', ['id' => $id])
        ->row_array(); 
      if(!$produto) {
          return $this->output 
           ->set_content_type('application/json')
           ->set_status_header(400)
           ->set_output(json_encode(['erro' => 'produto não foi encontrado']));
      }
    $this->db->where(['id' => $id])->delete('produtos');
           $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output(json_encode(['mensagem' => 'produto deletado com sucesso']));
}
}