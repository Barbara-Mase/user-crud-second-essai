<?php

class Router {

    private UserController $uc;

    public function __construct() {
        $this->uc = new UserController();
    }
    public function handleRequest(array $get)
    {
        if(!empty($get['route'])) { // route existe et n'est pas vide
            if($get['route'] ===  "list-users") {
                $this->uc->list();
            }
            else if($get['route'] === "details-user") {
                if(!empty($get["id"]))
                {
                    $this->uc->details(intval($get["id"]));
                }
            }
            else if($get['route'] === "update-user") {
                if(!empty($get["id"]))
                {
                    $this->uc->update(intval($get["id"]));
                }
            }
            else if($get['route'] === "check-update-user") {
                if(!empty($get["id"]))
                {
                    $this->uc->checkUpdate(intval($get["id"]));
                }
            }
            else if($get['route'] === "create-user") {
                $this->uc->create();
            }
            else if($get['route'] === "check-create-user") {
                $this->uc->checkCreate();
            }
            else if($get['route'] === "delete-user") {
                    if(!empty($get["id"])) {
                        $this->uc->delete(intval($get["id"]));
                    }
            }
            else // n'importe quelle route pas prévue : page 404
            {
                echo "La route demandée n'existe pas<br>";
            }
        }
        else // pas de route je renvoie vers la home
        {
            echo "Aucune route n'est demandée<br>";
        }
    }
}

