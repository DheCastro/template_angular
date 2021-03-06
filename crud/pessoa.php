<?php
class Pessoa
{
    private $conn;
    function __construct() {
    session_start();
    $servername = "localhost";
    $dbname = "crud";
    $username = "root";
    $password = "";
  

    // Cria conexao
    $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
      }else{
        $this->conn=$conn;  
      }

    }

    // Lista pessoas
    public function listar_pessoas(){
     
      $sql = "SELECT * FROM pessoa ORDER BY pessoa_id desc";
     
      $query=  $this->conn->query($sql);
      $pessoa=array();
       
      if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
        $pessoa['pessoa_data'][]= $row;
      }

      }
	   
      $count_sql = "SELECT COUNT(*) FROM pessoa";
      $query=  $this->conn->query($count_sql);
      $total = mysqli_fetch_row($query);
      $pessoa['total'][]= $total;
       
       
    return $pessoa;  
    }    

    // Busca pessoa por nome
    public function busca_pessoa_por_nome($nome_pesquisa=''){
       $pesquisa='';
       if($nome_pesquisa!=''){
         $pesquisa="WHERE ( pessoa_nome LIKE '%$nome_pesquisa%' )";  
       }
      
     
       $sql = "SELECT * FROM pessoa $pesquisa ORDER BY pessoa_id desc";
     
       $query=  $this->conn->query($sql);
       $pessoa=array();
       if ($query->num_rows > 0) {
       while ($row = $query->fetch_assoc()) {
          $pessoa['pessoa_data'][]= $row;
       }
       }
       
       
    return $pessoa;  
    }

    // Insert de Pessoa no banco
    public function criar_pessoa($post_data=array()){
       $pessoa_nome='';
       if(isset($post_data->pessoa_nome)){
       $pessoa_nome= mysqli_real_escape_string($this->conn,trim($post_data->pessoa_nome));
       }
       
       $sql="INSERT INTO pessoa (pessoa_nome) VALUES ('$pessoa_nome')";
        
        $result=  $this->conn->query($sql);
        
        if($result){
          return 'Pessoa criada com sucesso';     
        }else{
           return 'Erro ao inserir registro';     
        }
          
    }


    public function atualizar_pessoa($post_data=array()){
       
       if( isset($post_data->pessoa_id)){
       $pessoa_id=mysqli_real_escape_string($this->conn,trim($post_data->pessoa_id));
           
       $pessoa_nome='';
       if(isset($post_data->pessoa_nome)){
       $pessoa_nome= mysqli_real_escape_string($this->conn,trim($post_data->pessoa_nome));
       }
       

       $sql="UPDATE pessoa SET pessoa_nome='$pessoa_nome' WHERE pessoa_id =$pessoa_id";
     
        $result=  $this->conn->query($sql);
        
         
         unset($post_data->pessoa_id); 
         if($result){
          return 'Pessoa atualizada com sucesso';     
        }else{
         return 'Erro ao atualizar pessoa';     
        }
          
           
           
      
       }   
    }

    // Delete de Pessoa no banco
    public function deletar_pessoa($id){
        
      if(isset($id)){
        $pessoa_id= mysqli_real_escape_string($this->conn,trim($id));

        $sql="DELETE FROM  pessoa  WHERE pessoa_id =$pessoa_id";
        $result=  $this->conn->query($sql);
        
        if($result){
          return 'Pessoa deletada com sucesso';     
        }else{
          return 'Erro ao deletar registro';     
        }

      }
      
    }

    function __destruct() {
    mysqli_close($this->conn);  
    }
    
}

?>