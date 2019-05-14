<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;
use DateTime;

class UserModel
{
    private $db;
    private $table = 'Notes';
    private $response;

    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function GetAll()
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table");
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function Get($id)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function InsertOrUpdate($data)
    {
        try
        {
            $createData = date("Y-m-d H:i:s");

            if(isset($data['id']))
            {
                $sql = "UPDATE $this->table SET 
                            title          = ?, 
                            content        = ?,
                            private        = ?,
                            tag1           = ?,
                            tag2           = ?,
                            tag3           = ?,
                            tag4           = ?,
                            book           = ?,
                            creationDate            = ?,
                            lastModificationDate    = ?,
                            user           = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['title'],
                            $data['content'],
                            $data['private'],
                            $data['tag1'],
                            $data['tag2'],
                            $data['tag3'],
                            $data['tag4'],
                            $data['book'],
                            $createData,
                            $data['user'],
                            $data['id']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (title, content, private, tag1, tag2, tag3, tag4, book, creationDate, user)
                            VALUES (?,?,?,?,?,?,?,?,?,?)";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['title'],
                            $data['content'],
                            $data['private'],
                            $data['tag1'],
                            $data['tag2'],
                            $data['tag3'],
                            $data['tag4'],
                            $data['book'],
                            $createData,
                            $data['user']
                        )
                    );
            }

            $this->response->setResponse(true);
            return $this->response;
        }catch (Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Delete($id)
    {
        try
        {
            $stm = $this->db
                ->prepare("DELETE FROM $this->table WHERE id = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function GetPublic($private)
    {
        try
        {
            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE private = ?");
            $stm->execute(array($private));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function getAllWithTag($tag)
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE content LIKE ?");

            $stm->execute(array($tag));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
}