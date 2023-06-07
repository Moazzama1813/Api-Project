<?php

 class AuthicationModel extends CI_Model{


    public function get_user(){

		// $query = $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('user')->row(); 

		$query=$this->db->order_by('id','DESC');
		$query= $this->db->get('user');
		return $query->result();
	}
 

   public function check_login($email){

    //   $this->db->select('*');
	//   $this->db->from('user');
	//   $this->db->where('email',$email);
	//   $this->db->where('password',$password);

    //  $query= $this->db->get();

	//  if($query->num_rows()>0){
	// 	return $query->result_array();
	//  }
	//  else{
	// 	// $error = array(
	// 	// 	"status"=>401,
	// 	// 	"message"=>"Email and Password is invalid,please valid Email and Password!",
	// 	// 	"sucess"=>false
	// 	// 	);

	// 	// echo json_encode($error);
	//  }


	$this->db->where('email',$email);
	return  $row=$this->db->get('user')->row_array();
	}



        
	 public function insertUser($data){


		return $this->db->insert('user',$data); 
   
	   }
   


	   public function deleteUser($id){

		return $this->db->delete('user',['id'=>$id]);
	  }

	  public function editUser($id){
		$this->db->where('id',$id);
		$query=$this->db->get('user');
		return $query->row();
	  }


	  public function updateUser($id, $data){
		$this->db->where('id',$id);
		return $this->db->update('user', $data);
	  }


 }




?>
