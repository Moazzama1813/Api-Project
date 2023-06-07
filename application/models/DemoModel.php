<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DemoModel extends CI_Model{

	public function get_user(){
		$query= $this->db->get('user');
		return $query->result();
	}

	public function insertUser($data){


     return $this->db->insert('user',$data); 

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

	     
	  public function deleteUser($id){

		return $this->db->delete('user',['id'=>$id]);
	  }



	  public function check_login($email,$password){

		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('email',$email);
		$this->db->where('password',$password);
  
	   $query= $this->db->get();
  
	   if($query->num_rows()>0){
		  return $query->result_array();
	   }
	   else{
		  return "user not found";
	   }
  
	 }
	  

}



?>
