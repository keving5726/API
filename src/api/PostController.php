<?php

namespace Src\Api;

use Src\Model\Post;
use Src\Model\User;

class PostController extends Post
{
    private $obj;
    private $result;
    private $access_token;
    private $entity;
    private $container_id;
    private $title;
    private $description;
    private $datetime;
    private $id;

    public function __construct()
    {
        $this->obj = new Post();
        $this->request = json_decode(file_get_contents("php://input"));
        session_start();
    }

    public function validateRequest(): ?bool
    {
        if (!isset($this->request))
        {
            $this->result = array(
                "message" => "The request is empty"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return true;
        }
        return false;
    }

    public function validateSession(): ?bool
    {
        if (!isset($_SESSION['id']) ||
            !isset($_SESSION['email']) ||
            !isset($_SESSION['access_token']))
        {
            $this->result = array(
                "message" => "You must be logged in to create post"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return true;
        }
        return false;
    }

    public function validateToken(string $token): ?bool
    {
        if ($token != $_SESSION['access_token'])
        {
            $this->result = array(
                "message" => "The access_token does not match"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return true;
        }
        return false;
    }

    public function showPost(): void
    {
        if($this->validateSession())
        {
            return;
        }

        if($this->validateRequest())
        {
            return;
        }

        if (!(property_exists($this->request, 'access_token')))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token does not exist"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->access_token = $this->request->access_token;

        if (empty($this->access_token))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token is empty"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if($this->validateToken($this->access_token))
        {
            return;
        }

        $this->result = $this->obj->findAll();

        if (isset($this->result))
        {
            foreach($this->result as $value)
            {
                $this->result += $this->result;
            }
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

    public function createPost(): void
    {
        if($this->validateSession())
        {
            return;
        }

        if($this->validateRequest())
        {
            return;
        }

        if (!(property_exists($this->request, 'access_token')) ||
            !(property_exists($this->request, 'entity')) ||
            !(property_exists($this->request, 'container_id')) ||
            !(property_exists($this->request, 'title')) ||
            !(property_exists($this->request, 'description')))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token, entity, container_id, title or description does not exist"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->access_token = $this->request->access_token;
        $this->entity = $this->request->entity;
        $this->container_id = $this->request->container_id;
        $this->title = $this->request->title;
        $this->description = $this->request->description;

        if (empty($this->access_token) ||
            empty($this->entity) ||
            empty($this->container_id) ||
            empty($this->title) ||
            empty($this->description))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token, entity, container_id, title or description is empty"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if($this->validateToken($this->access_token))
        {
            return;
        }

        if ($this->entity != "post")
        {
            $this->result = array(
                "message" => "The entity does not match"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if ($this->container_id != $_SESSION['id'])
        {
            $this->result = array(
                "message" => "The container_id does not match"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->datetime = new \DateTime();

        $this->result = $this->obj->create($this->container_id, $this->title, $this->description, $this->datetime->format("Y-m-d H:i:s"), $this->datetime->format("Y-m-d H:i:s"));

        if (isset($this->result))
        {
            $this->result = $this->obj->findByCreated($this->container_id, $this->datetime->format("Y-m-d H:i:s"));
            $this->id = $this->result->id;
            $this->datetime = $this->result->created_at;
            $this->result = array(
                "message" => "The post was created successfully",
                "id" => "$this->id",
                "container_id" => "$this->container_id",
                "title" => "$this->title",
                "description" => "$this->description",
                "created_at" => "$this->datetime"
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

    public function updatePost(): void
    {
        if($this->validateSession())
        {
            return;
        }

        if($this->validateRequest())
        {
            return;
        }

        if (!(property_exists($this->request, 'access_token')) ||
            !(property_exists($this->request, 'id')) ||
            !(property_exists($this->request, 'title')) ||
            !(property_exists($this->request, 'description')))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token, id, title or description does not exist"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->access_token = $this->request->access_token;
        $this->id = $this->request->id;
        $this->container_id = $_SESSION['id'];
        $this->title = $this->request->title;
        $this->description = $this->request->description;

        if (empty($this->access_token) ||
            empty($this->id) ||
            empty($this->title) ||
            empty($this->description))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token, id, title or description is empty"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if($this->validateToken($this->access_token))
        {
            return;
        }

        $this->result = $this->obj->findById($this->container_id, $this->id);
        $this->id;

        if (isset($this->result))
        {
            $this->datetime = new \DateTime();
            $this->result = $this->obj->update($this->id, $this->title, $this->description, $this->datetime->format("Y-m-d H:i:s"));
            if (isset($this->result))
            {
                $this->result = array(
                    "message" => "The post was updated successfully",
                    "id" => "$this->id",
                    "container_id" => "$this->container_id",
                    "title" => "$this->title",
                    "description" => "$this->description"
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
        $this->result = array(
            "message" => "The id does not match",
        );
        http_response_code(400);
        echo json_encode($this->result);
        return;
    }

    public function deletePost(): void
    {
        if($this->validateSession())
        {
            return;
        }

        if($this->validateRequest())
        {
            return;
        }

        if (!(property_exists($this->request, 'access_token')) ||
            !(property_exists($this->request, 'entities_ids')))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token or entities_id does not exist"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        $this->access_token = $this->request->access_token;
        $this->id = $this->request->entities_ids;
        $this->container_id = $_SESSION['id'];

        if (empty($this->access_token) || empty($this->id))
        {
            $this->result = array(
                "message" => "Please complete the form. The access_token or entities_ids is empty"
            );
            http_response_code(400);
            echo json_encode($this->result);
            return;
        }

        if($this->validateToken($this->access_token))
        {
            return;
        }

        foreach($this->id as $value)
        {
            $this->result = $this->obj->findById($this->container_id, $value);

            if (!$this->result)
            {
                $this->result = array(
                    "message" => "The post with the id: $value does not exist or you don't have privileges to eliminate the post"
                );
                http_response_code(400);
                echo json_encode($this->result);
                return;
            }
        }

        foreach($this->id as $value)
        {
            if (null != $this->obj->delete($this->container_id, $value))
            {
                $this->result = array(
                    "message" => "The post with the id = $value was deleted successfully"
                );
                http_response_code(200);
                echo json_encode($this->result);
                continue;
            }
            $this->result = array(
                "message" => "There was an error deleting the post with the id: $value"
            );
            http_response_code(500);
            echo json_encode($this->result);
            continue;
        }
    }
}
