<?php

class UserController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function list(): void
    {
        $um = new UserManager();
        $users = $um->findAll();

        $this->render('admin/users/list.html.twig', [
            'users' => $users
        ]);
    }

    public function details(int $id): void
    {
        $um = new UserManager();
        $user = $um->findOne($id);

        $this->render('admin/users/details.html.twig', [
            'user' => $user
        ]);
    }

    public function update(int $id) : void
    {
        $um = new UserManager();
        $user = $um->findOne($id);

        if(!empty($_SESSION["errors"]))
        {
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('admin/users/update.html.twig', [
                'user' => $user,
                "errors" => $errors
            ]);
        }
        else
        {
            $this->render('admin/users/update.html.twig', [
                'user' => $user
            ]);
        }
    }



    public function checkUpdate(int $id): void
    {
        $_SESSION["errors"] = [];

        $um = new UserManager();

        if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["first_name"]) && !empty($_POST["last_name"])) {

            // Vérifier si l'email appartient déjà à un autre utilisateur
            $existingUser = $um->findByEmail($_POST["email"]);

            if ($existingUser === null || $existingUser->getId() === $id) {
                $user = new User($_POST["email"], $_POST["password"], $_POST["first_name"], $_POST["last_name"]);
                $user->setId($id); // ← manquait dans ta version !

                $ret = $um->update($user);

                if ($ret) {
                    unset($_SESSION["errors"]);
                    header("Location: index.php?route=list-users");
                } else {
                    $_SESSION["errors"][] = "La mise à jour a échoué lors de l'écriture dans la base de données.";
                    header("Location: index.php?route=update-user&id=$id");
                }
            } else {
                $_SESSION["errors"][] = "Un utilisateur avec cet email existe déjà.";
                header("Location: index.php?route=update-user&id=$id");
            }

        } else {
            $_SESSION["errors"][] = "Au moins un champ obligatoire est manquant.";
            header("Location: index.php?route=update-user&id=$id");
        }
    }

    public function create(): void
    {
        if (!empty($_SESSION["errors"])) {
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('admin/users/create.html.twig', [
                "errors" => $errors
            ]);
        } else {
            $this->render('admin/users/create.html.twig', [

            ]);
        }
    }


    public function checkCreate(): void
    {
        $_SESSION["errors"] = [];

        if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["first_name"]) && !empty($_POST["last_name"])) {
            // tous les champs sont remplis
            $um = new UserManager();
            $user = $um->findByEmail($_POST["email"]);

            if ($user === null) {
                // il n'existe pas, on peut le créer
                $user = new User($_POST["email"], $_POST["password"], $_POST["first_name"], $_POST["last_name"]);
                $ret = $um->create($user);

                if ($ret) {
                    unset($_SESSION["errors"]);
                    header("Location: index.php?route=list-users");
                } else {
                    $_SESSION["errors"][] = "La création a échoué lors de l'écriture dans la base de données.";
                    header("Location: index.php?route=create-user");
                }
            } else {
                $_SESSION["errors"][] = "Un utilisateur avec cet email existe déjà.";
                header("Location: index.php?route=create-user");
            }
        } else {
            $_SESSION["errors"][] = "Au moins un champ obligatoire est manquant.";
            header("Location: index.php?route=create-user");
        }
    }


    public function delete(int $id) : void
    {
        $um = new UserManager();
        $um->delete($id);
        header("Location: index.php?route=list-users");
    }



}