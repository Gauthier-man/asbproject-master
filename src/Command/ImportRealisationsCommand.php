<?php

namespace App\Command;

use App\Entity\Realisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportRealisationsCommand extends Command
{


    private $em;

    // Dossier à scanner
    private $baseDir;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
        // Le dossier public/uploads/realisations (à adapter si besoin)
        $this->baseDir = __DIR__ . '/../../public/uploads/realisations';
    }

    protected function configure()
    {
        $this->setName('app:import-realisations')
            ->setDescription('Importe toutes les images du dossier realisations en base de données.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // On va parcourir récursivement tous les fichiers
        $files = $this->getFilesRecursive($this->baseDir);

        $count = 0;

        foreach ($files as $file) {
            // $file est le chemin complet, on veut le chemin relatif à realisations/
            $relativePath = substr($file, strlen($this->baseDir) + 1); // +1 pour enlever le slash

            // Extraction de la catégorie : premier dossier dans $relativePath
            $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
            $category = strtolower($parts[0] ?? 'unknown');

            // Vérifier si la réalisation existe déjà pour éviter doublons
            $existing = $this->em->getRepository(Realisation::class)
                ->findOneBy(['imagePath' => $relativePath]);

            if ($existing) {
                $output->writeln("Ignore déjà importé : $relativePath");
                continue;
            }

            $realisation = new Realisation();
            $realisation->setCategory($category);
            $realisation->setImagePath(str_replace(DIRECTORY_SEPARATOR, '/', $relativePath)); // Toujours slash pour URL
            $realisation->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($realisation);
            $count++;

            $output->writeln("Importé : $relativePath");
        }

        $this->em->flush();

        $output->writeln("Import terminé. $count réalisations ajoutées.");

        return Command::SUCCESS;
    }

    private function getFilesRecursive(string $dir): array
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        $files = [];
        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }

            // Optionnel : filtre sur extensions d'image
            $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }
}
