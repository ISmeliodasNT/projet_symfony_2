<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:add-client',
    description: 'Ajoute un client interactivement',
)]
class AddClientCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Création d\'un nouveau client');

        $client = new Client();

        $client->setFirstname($io->ask('Prénom du client'));
        $client->setLastname($io->ask('Nom du client'));
        $client->setEmail($io->ask('Adresse email (ex: xxx@xxx.xx)'));
        $client->setPhoneNumber($io->ask('Numéro de téléphone'));
        $client->setAddress($io->ask('Adresse postale complète'));

        $errors = $this->validator->validate($client);

        if (count($errors) > 0) {
            $io->error('Des erreurs de validation ont été trouvées :');
            foreach ($errors as $error) {
                $io->writeln('- ' . $error->getMessage());
            }
            return Command::FAILURE;
        }

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $io->success('Le client ' . $client->getFirstname() . ' ' . $client->getLastname() . ' a été créé avec succès !');

        return Command::SUCCESS;
    }
}