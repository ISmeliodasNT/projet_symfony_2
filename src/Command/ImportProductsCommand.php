<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:import-products',
    description: 'Importe des produits à partir d\'un fichier CSV situé dans le dossier public.',
)]
class ImportProductsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $params
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        $this
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'Le nom du fichier CSV dans le dossier public', 'import.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getOption('filename');
        
        $publicDirectory = $this->params->get('kernel.project_dir') . '/public';
        $filepath = $publicDirectory . '/' . $filename;

        if (!file_exists($filepath)) {
            $io->error(sprintf('Le fichier "%s" est introuvable.', $filepath));
            return Command::FAILURE;
        }

        if (($handle = fopen($filepath, 'r')) !== false) {
            
            fgetcsv($handle, 1000, ';'); 
            
            $count = 0;
            
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                if (count($data) >= 3) {
                    $product = new Product();
                    
                    $product->setName($data[0]);
                    $product->setDescription($data[1]);
                    
                    $priceFormat = str_replace(',', '.', $data[2]);
                    $product->setPrice((float) $priceFormat);
                    
                    $product->setType('Non défini');
                    $product->setMarque('Non définie');
                    $product->setStock(0);

                    $this->entityManager->persist($product);
                    $count++;
                }
            }
            
            fclose($handle);

            $this->entityManager->flush();

            $io->success(sprintf('%d boissons ont été importées avec succès !', $count));
            return Command::SUCCESS;
        }

        $io->error('Impossible de lire le fichier CSV.');
        return Command::FAILURE;
    }
}