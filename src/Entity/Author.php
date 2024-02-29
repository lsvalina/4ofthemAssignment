<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
class Author
{

    #[Assert\NotBlank(message: "First name should not be blank")]
    private string $first_name;

    #[Assert\NotBlank(message: "Last name should not be blank")]
    private string $last_name;

    #[Assert\LessThanOrEqual('now', message: "Birthday should be in past")]
    private \DateTime $birthday;


    private string $biography;

    #[Assert\NotBlank(message: "Gender should not be blank")]
    #[Assert\Choice(['male','female'], message: "Gender must be either 'male' or 'female'")]
    private string $gender;
    #[Assert\NotBlank]
    private string $place_of_birth;

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getPlaceOfBirth(): string
    {
        return $this->place_of_birth;
    }

    public function setPlaceOfBirth(string $place_of_birth): void
    {
        $this->place_of_birth = $place_of_birth;
    }
}
