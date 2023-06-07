<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authication extends CI_Controller {

	public function __construct()
   {

   parent::__construct();

   $this->load->model('AuthicationModel');
   
   $this->load->library("form_validation");
//    $this->load->library("encryption");
   $this->load->helper('verifyDemoToken');

   

//    header("Access-Control-Allow-Origin: *");
//    header("Access-Control-Allow-Methods: GET, OPTIONS,POST,PUT");
//    header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
	
   }
   
	
   



   public function login(){



	$this->load->library('form_validation');
	$this->form_validation->set_rules('email','email','required|valid_email');
	$this->form_validation->set_rules('password','password','required');
	
	if($this->form_validation->run() == TRUE ){




	$jwt = new jwt();

	$JwtSecretKey="MyloginSecret";


	// $email=$this->input->post('email');
	// $password=$this->input->post('password');

	

	//   $user = new AuthicationModel;

	//    $result=$user->check_login($email, $password);

	//    if($result == TRUE){
		  
	// 	$token = $jwt->encode($result,$JwtSecretKey,'HS256');

	// 	echo json_encode($token);
		 
	//    }


	// 	else{

	// 	$error = array(
	// 		"status"=>401,
	// 		"message"=>"Email and Password is invalid,please valid Email and Password!",
	// 		"sucess"=>false
	// 		);

	// 	echo json_encode($error);
	
	// 	}

	$email=$this->input->post('email');
	$user=$this->AuthicationModel->check_login($email);
		if(!empty($user)){
			$password=$this->input->post('password');
			if(password_verify($password,$user['password']) == TRUE){
				
				$token = $jwt->encode($user,$JwtSecretKey,'HS256');

				$final=array();
				$final['id']   = $user['id'];
				$final['first_name'] = $user['first_name'];
				$final['last_name'] = $user['last_name'];
				$final['email']= $user['email'];
				$final['phone']= $user['phone'];
				// $final['password']= $this->encryption->decode($user['password']);
				$final['token']= $token;
				$final['status']= 'ok';
				echo json_encode($final);
			}
			else{
				$error = array(
							"status"=>401,
							"message"=>"Email and Password is invalid,please valid Email and Password!",
							"sucess"=>false
							);
				
				 echo json_encode($error);
			}
		}
		else{
			$error = array(
				"status"=>401,
				"message"=>"Email and Password is invalid,please valid Email and Password!",
				"sucess"=>false
				);
	
			echo json_encode($error);
		}}

		else{

			
			$error =array('status' => 400, 'message' => "please add correct data!" . validation_errors(),400);
			echo json_encode($error);

		}

	

   }	

	
	// public function index()
	// {
	// 	echo "ali";
	// }
	// public function token(){
	// 	$jwt = new jwt();
	// 	$JwtSecretKey="Mysecretwordshere";
	// 	$data = array(
	// 		"userid"=>145,
	// 		"email"=>"admin123@gmail.com",
	// 		"userType"=>"admin",

	// 	);
	// 	$token=$jwt->encode($data,$JwtSecretKey,'HS256');
	// 	echo $token;
	//  }

	//  public function decode_token(){
	// 	$token=$this->uri->segment(3);

	// 	$jwt = new jwt();

	// 	$JwtSecretKey="Mysecretwordshere";
	// 	$decode_token= $jwt->decode($token,$JwtSecretKey,'HS256');

	// 	// echo "<pre>";
	// 	// print_r($decode_token);

	// 	$token1=$jwt->jsonEncode($decode_token);
	// 	echo $token1;

	//  }


        public function getUsers(){
	
		$headerToken = $this->input->get_request_header('Authorization');
		$splitToken = explode(" ", $headerToken);
		$token =  $splitToken[0];
		
			try {
				
				$token = verifyDemoToken($token);
				if($token){
					$user=new AuthicationModel;
					 $result_user=$user->get_user();

					echo json_encode($result_user);
				}
					
			}
			catch(Exception $e) {
			// echo 'Message: ' .$e->getMessage();
				$error = array(
					"status"=>401,
					"message"=>"Invalid Token provided",
					"sucess"=>false
					);
		
				echo json_encode($error);
			}
			
		}







	
		




// public function index_get(){
// 	// echo "I'm  User Function";
// 	$user = new UserModel;
// 	$result_user=$user->get_user();
// 	$this->response($result_user, 200);
// }


public function storeUser(){




         
	$user = new AuthicationModel;
	

	$this->form_validation->set_rules("first_name","first_name","required");
	$this->form_validation->set_rules("last_name","last_name","required");
	$this->form_validation->set_rules("email","email","required|valid_email|is_unique[user.email]");
	$this->form_validation->set_rules("phone","phone","required");
	$this->form_validation->set_rules("password","password","required|min_length[8]");

	// if($this->form_validation->run() === FALSE){
	
	// 	$error = array("status"=>0,"message"=>"All Fields are required");
	// 	echo json_encode($error);
 

	// }

	if ($this->form_validation->run() == FALSE) {
        $this->form_validation->set_error_delimiters('', '');
        $error =array('status' => 400, 'message' => "please add correct data!" . validation_errors(),400);
		echo json_encode($error);
 
    }

	else{


		$data=[  
			'first_name'=>$this->input->post('first_name'),
			'last_name'=>$this->input->post('last_name'),
			'email'=> $this->input->post('email'),
			'phone'=> $this->input->post('phone'),
			'password'=>password_hash($this->input->post('password'),PASSWORD_BCRYPT)
		
			];

	   	 
		$headerToken = $this->input->get_request_header('Authorization');
		$splitToken = explode(" ", $headerToken);
		$token =  $splitToken[0];
		
			try {
				
				$token = verifyDemoToken($token);
				if($token){
					$user=new AuthicationModel;
					 $result_user=$user->insertUser($data);
					 if($result_user > 0){
		 
						$error= array('status'=> true,
						'message'=>'NEW USER CREATED');
						echo json_encode($error);
			 
				   }
			
				   else{
			
					   $error=array(
						'status'=> true,
						'message'=>'FAILED TO CREATE USER');
			
						echo json_encode($error);
			 
			
				   }
					 
					//  print_r($result_user);

					echo json_encode($result_user);
				}
					
			}
			catch(Exception $e) {
			// echo 'Message: ' .$e->getMessage();
				$error = array(
					"status"=>401,
					"message"=>"Invalid Token provided",
					"sucess"=>false
					);
		
				echo json_encode($error);
			}

	}

	
	
}


    public function deleteUser($id){



		$headerToken = $this->input->get_request_header('Authorization');
		$splitToken = explode(" ", $headerToken);
		$token =  $splitToken[0];
		
			try {
				
				$token = verifyDemoToken($token);
				if($token){
					$user=new AuthicationModel;
					$result= $user->deleteUser($id);
					 if($result > 0){
						$error= array('status'=> true,
							'message'=>' USER DELETED SUCCESSFULLY!');
							echo json_encode($error);
					  }
					 
					//  print_r($result_user);

					else{

						$error= array('status'=> true,
						'message'=>' FAILED TO DELETE USER!');
						echo json_encode($error);
					  }
				}

					
			}
			catch(Exception $e) {
			// echo 'Message: ' .$e->getMessage();
				$error = array(
					"status"=>401,
					"message"=>"Invalid Token provided",
					"sucess"=>false
					);
		
				echo json_encode($error);
			}
	  
}


   public function findUser($id){

	$headerToken = $this->input->get_request_header('Authorization');
		$splitToken = explode(" ", $headerToken);
		$token =  $splitToken[0];
		
			try {
				
				$token = verifyDemoToken($token);
				if($token){
					$user=new AuthicationModel;
					 $result_user=$user->editUser($id);
					 
					//  print_r($result_user);

					echo json_encode($result_user);
				}
					
			}
			catch(Exception $e) {
			// echo 'Message: ' .$e->getMessage();
				$error = array(
					"status"=>401,
					"message"=>"Invalid Token provided",
					"sucess"=>false
					);
		
				echo json_encode($error);
			}
	  
}


    public function updateUser($id){

	$user=new AuthicationModel;
	$data=[  
	'first_name'=> $this->input->post('first_name'),
	'last_name'=> $this->input->post('last_name'),
	'email'=> $this->input->post('email'),
	'phone'=> $this->input->post('phone')

	];

	$headerToken = $this->input->get_request_header('Authorization');
	$splitToken = explode(" ", $headerToken);
	$token =  $splitToken[0];
	
		try {
			
			$token = verifyDemoToken($token);
			if($token){
				$user=new AuthicationModel;
				$update_result=$user->updateUser($id,$data);
				 
			
				 if($update_result > 0){

					$error = array(
					   "status"=>true,
					   "message"=>"USER UPDATED SUCCESSFULLY!"
					   );
			   
				   echo json_encode($error);
						  
				  }
			   
				  else{
			   
				   $error = array(
					   "status"=>true,
					   "message"=>"FAILED TO UPDATE USER!"
					   );
			   
				   echo json_encode($error);
			   
				  }
				
			}
				
		}
		catch(Exception $e) {
		// echo 'Message: ' .$e->getMessage();
			$error = array(
				"status"=>401,
				"message"=>"Invalid Token provided",
				"sucess"=>false
				);
	
			echo json_encode($error);
		}

   



}



}
