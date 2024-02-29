<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    #[Assert\NotBlank(message: "Email should not be blank")]
    #[Assert\Email]
    private string $email;

    #[Assert\Length(min: 8, minMessage: "Password is too short. It should have 8 characters or more.")]
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


}
