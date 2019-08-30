<?php

namespace Src\Api;

use Src\Model\User;

class UserController extends User
{
    private $obj;
    private $request;
    private $result;
    private $email;
    private $password;
    private $datetime;

    public function __construct()
    {
        $this->obj = new User();
        $this->request = json_decode(file_get_contents("php://input"));
    }

    public function createUser(): void
    {
        if (!(property_exists($this->request, 'email')) ||
            !(property_exists($this->request, 'password')))
        {
            $this->result = array(
                "message" => "Please complete the form. The email or password does not exist"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->email = $this->request->email;
        $this->password = $this->request->password;

        if (empty($this->email) || empty($this->password))
        {
            $this->result = array(
                "message" => "Please complete the form. The email or password is empty"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if (null !== $this->obj->findBy("email", $this->email))
        {
            $this->result = array(
                "message" => "There is already an account with this email"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }
            
        $this->datetime = new \DateTime();
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->result = $this->obj->create($this->email, $this->password, $this->datetime->format("Y-m-d H:i:s"));
        if (isset($this->result))
        {
            $this->result = array(
                "message" => "The account was created successfully",
                "email" => "$this->email"
            );
            http_response_code(201);
            echo json_encode($this->result);
            return;
        }
        $this->result = array(
            "message" => "There was an error"
        );
        http_response_code(500);
        echo json_encode($this->result);
        return;
    }
}
