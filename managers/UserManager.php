<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM users');
        $parameters = [];
        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $user = new User($result['email'], $result['password'], $result['first_name'], $result['last_name'], $result['id']);

            $users[] = $user;
        }

        return $users;
    }


    public function findOne(int $id): ?User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $parameters = [
            ':id' => $id
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $user = new User($result['email'], $result['password'], $result['first_name'], $result['last_name'], $result['id']);
            return $user;
        } else {
            return null;
        }
    }


    public function findByEmail(string $email): ?User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            ':email' => $email
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $user = new User($result['email'], $result['password'], $result['first_name'], $result['last_name'], $result['id']);
            return $user;
        } else {
            return null;
        }

    }


    public function create(User $user) : bool {

        $query = $this->db->prepare("INSERT INTO users (id, email, password, first_name, last_name) VALUES (NULL, :email, :password, :first_name, :last_name)");

        $parameters = [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName()
        ];

        $query->execute($parameters);

        if($this->db->lastInsertId())
            return true;
        else
            return false;
    }

    public function update(User $user) : bool {
        $query = $this->db->prepare
    }

    public function delete(int $id) : bool {
        $query = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        return true;
    }


}

