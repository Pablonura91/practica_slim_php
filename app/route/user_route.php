<?php

use App\Model\UserModel;

$app->group('/user/', function () {

    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
            ->write('Hello Users');
    });


    $this->get('root', function ($req, $res, $args) {

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                '{"code": "200", "msg": "LSNote API v0.1"}'
            );
    });

    $this->get('getAll', function ($req, $res, $args) {
        $um = new UserModel();

        $result = $um->GetAll();
        if (count($result->result) == 0) {
            $arr = array('code' => 204, 'msg' => 'No notes found!');
            return $res
                ->withJson($arr, 204);
        } else {
            $arr = array('code' => 200, 'resp' => $result);
            return $res
                ->withJson($arr, 200);
        }
    });

    $this->get('getOne/{id}', function ($req, $res, $args) {
        $um = new UserModel();
        $result = $um->Get($args['id']);

        if (count($result->result) == 0) {
            $arr = array('code' => 204, 'msg' => 'This note does not exist!');
            return $res
                ->withJson($arr, 204);
        } else {
            $arr = array('code' => 200, 'resp' => $result);
            return $res
                ->withJson($arr, 200);
        }
    });

    $this->post('insert', function ($req, $res) {
        $um = new UserModel();

        $um->InsertOrUpdate(
            $req->getParsedBody()
        );

        $arr = array('code' => 200, 'msg' => 'Note inserted!');
        return $res
            ->withJson($arr, 200);

    });

    $this->post('delete/{id}', function ($req, $res, $args) {
        $um = new UserModel();

        $um->Delete($args['id']);

        $arr = array('code' => 200, 'msg' => 'Note deleted!');
        return $res
            ->withJson($arr, 200);
    });

    $this->get('getPublic', function ($req, $res, $args) {
        $um = new UserModel();

        $result = $um->GetPublic(false);
        if (count($result->result) == 0) {
            $arr = array('code' => 204, 'msg' => 'No notes found');
            return $res
                ->withJson($arr, 204);
        } else {
            $arr = array('code' => 200, 'resp' => $result);
            return $res
                ->withJson($arr, 200);
        }

    });

    $this->get('getAllWithTag/{tag}', function ($req, $res, $args) {
        $um = new UserModel();

        $result = $um->getAllWithTag('%' . $args['tag'] . '%');

        if (count($result->result) == 0) {
            $arr = array('code' => 204, 'msg' => 'No notes found!');
            return $res
                ->withJson($arr, 204);
        } else {
            $arr = array('code' => 200, 'resp' => $result);
            return $res
                ->withJson($arr, 200);
        }
    });

});