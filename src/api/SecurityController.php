<?php

namespace Src\Api;

use Src\Model\User;

class SecurityController
{
    private $obj;
    private $request;
    private $result;
    private $id;
    private $email;
    private $password;
    private $application_id;
    private $access_token;

    public function __construct()
    {
        session_start();
        $this->request = json_decode(file_get_contents("php://input"));
        $this->access_token = &$_SESSION['access_token'];
    }

    public function generateToken(): void
    {
        $length = 32;
        $this->access_token = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
        return;
    }

    public function login(): void
    {
        if (!(property_exists($this->request, 'email')) ||
            !(property_exists($this->request, 'password')) ||
            !(property_exists($this->request, 'application_id')))
        {
            $this->result = array(
                "message" => "Please complete the form. The email, password or application_id does not exist"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->email = $this->request->email;
        $this->password = $this->request->password;
        $this->application_id = $_ENV['APP_ID'];

        if (empty($this->email) || empty($this->password) || empty($this->application_id))
        {
            $this->result = array(
                "message" => "Please complete the form. The email, password or application_id is empty"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if ($this->application_id !== $this->request->application_id)
        {
            $this->result = array(
                "message" => "The application_id is incorrect"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->obj = new User();
        $this->result = $this->obj->findBy("email", $this->email);
        if (!isset($this->result))
        {
            $this->result = array(
                "message" => "The email is not registered. Please create an account"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if (password_verify($this->password, $this->result[0]->password))
        {
            $this->generateToken();
            $this->id = $this->result[0]->id;
            $_SESSION['id'] = $this->id;
            $_SESSION['email'] = $this->email;
            $this->result = array(
                "message" => "Successful login",
                "id" => "$this->id",
                "email" => "$this->email",
                "access_token" => "$this->access_token"
            );
            http_response_code(200);
            echo json_encode($this->result);
            return;
        }
        $this->result = array(
            "message" => "Your password is incorrect"
        );
        http_response_code(400);
        echo json_encode($this->result);
        return;
    }

    public function logout(): void
    {
        if (!(property_exists($this->request, 'access_token')))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token does not exist"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if ($this->access_token === $this->request->access_token)
        {
            $this->result = array(
                "message" => "Logout successful"
            );
            session_destroy();
            http_response_code(200);
            echo json_encode($this->result);
            return;
        }
        $this->result = array(
            "message" => "The access_token does not match"
        );
        http_response_code(400);
        echo json_encode($this->result);
        return;
    }
}
