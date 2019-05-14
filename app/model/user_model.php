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
        try {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table");
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function Get($id)
    {
        try {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function InsertOrUpdate($data)
    {
        try {
            $createData = date("Y-m-d H:i:s");

            if (isset($data['id'])) {
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
            } else {
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
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Delete($id)
    {
        try {
            $stm = $this->db
                ->prepare("DELETE FROM $this->table WHERE id = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function GetPublic($private)
    {
        try {
            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE private = ?");
            $stm->execute(array($private));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function getAllWithTag($tag)
    {
        try {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE content LIKE ?");

            $stm->execute(array($tag));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function addTagOnNote($tag, $note)
    {
        if (empty($note->tag1)) {
            $tagUpdate = "tag1";
        } elseif (empty($note->tag2)) {
            $tagUpdate = "tag2";
        } elseif (empty($note->tag3)) {
            $tagUpdate = "tag3";
        } elseif (empty($note->tag4)) {
            $tagUpdate = "tag4";
        } else {
            return 409;
        }
        $stm = $this->db->prepare("UPDATE $this->table SET $tagUpdate = ? WHERE id = ?");

        $stm->execute(array(
            $tag,
            $note->id
        ));

        $this->response->setResponse(true);

        return $this->response;
    }

    public function deleteTagOnNote($tag, $note)
    {
        if ($note->tag1 == $tag) {
            $tagUpdate = "tag1";
        } elseif ($note->tag2 == $tag) {
            $tagUpdate = "tag2";
        } elseif ($note->tag3 == $tag) {
            $tagUpdate = "tag3";
        } elseif ($note->tag4 == $tag) {
            $tagUpdate = "tag4";
        } else {
            return 409;
        }
        $stm = $this->db->prepare("UPDATE $this->table SET $tagUpdate = 'null' WHERE id = ?");

        $stm->execute(array(
            $note->id
        ));

        $this->response->setResponse(true);

        return $this->response;
    }

    public function flipPrivate($note)
    {
        if ($note->private == 0) {
            $flipUpdate = 1;
        } else {
            $flipUpdate = 0;
        }

        $stm = $this->db->prepare("UPDATE $this->table SET private = ? WHERE id = ?");

        $stm->execute(array(
            $flipUpdate,
            $note->id
        ));

        $this->response->setResponse(true);

        return $this->response;
    }
}