<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\User;
use App\Service\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:add:author',
    description: 'Add a short description for your command',
)]
class AddAuthorCommand extends Command
{
    private $validator;
    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct();
        $this->validator = $validator;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $user = new User();

        $user->setEmail($helper->ask($input, $output, new Question('Enter email: ', '')));

        $question = new Question('Enter password: ','');
        $question->setHidden(true)->setHiddenFallback(false);
        $user->setPassword($helper->ask($input, $output, $question));

        $violations = $this->validator->validate($user);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $output->writeln($violation->getMessage());
            }
            return Command::FAILURE;
        }

        $client = new Client();
        $client->login($user);

        if(!$client->hasToken()) {
            $output->writeln("Invalid username or password!");
            return Command::FAILURE;
        }
        $author = new Author();
        $author->setFirstName($helper->ask($input, $output, new Question('Enter first name: ', '')));
        $author->setLastName($helper->ask($input, $output, new Question('Enter last name: ', '')));

        $birthday = \DateTime::createFromFormat(
            'Y-m-d',
            $helper->ask($input, $output, new Question('Enter birthday (YYYY-MM-DD): '))
        );

        if(!$birthday) {
            $output->writeln("Invalid birthday date!");
            return Command::FAILURE;
        }

        $author->setBirthday($birthday);

        $author->setBiography($helper->ask($input, $output, new Question('Enter biography: ', '')));
        $author->setGender($helper->ask($input, $output, new Question('Enter gender: ', '')));
        $author->setPlaceOfBirth($helper->ask($input, $output, new Question('Enter place of birth: ', '')));

        $violations = $this->validator->validate($author);


        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $output->writeln($violation->getMessage());
            }
            return Command::FAILURE;
        }

        $success = $client->author_add($author);

        if(!$success) {
            $output->writeln("Cannot create author!");
            return Command::FAILURE;
        }

        $output->writeln("Author {$author->getFirstName()} {$author->getLastName()} created !");
        return Command::SUCCESS;
    }
}
