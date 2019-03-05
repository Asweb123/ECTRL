<?php

namespace App\DataFixtures;

use App\Entity\Certification\Certification;
use App\Entity\Company;
use App\Entity\RegisterCode;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName("Fleury Michon");


        $certificationIFS = new Certification();
        $certificationIFS->setTitle("IFS");
        $certificationIFS->setDescription("Norme Internationale pour la sécurité des Aliments");


        $registerCodeD = new RegisterCode();
        $registerCodeD->setCodeContent("X29JTZ22");

        $registerCodeR = new RegisterCode();
        $registerCodeR->setCodeContent("UT28SK78");

        $registerCodeA = new RegisterCode();
        $registerCodeA->setCodeContent("AS59ET47");

        $registerCodeC = new RegisterCode();
        $registerCodeC->setCodeContent("VG87SY45");


        $roleD = new Role();
        $roleD->setTitle("Directeur");
        $roleD->setDescription("Suivi des résultats et validation des grilles vérifiées par le responsable qualité");

        $roleR = new Role();
        $roleR->setTitle("Responsable qualité");
        $roleR->setDescription("Création, complétion et validation des grilles");

        $roleA = new Role();
        $roleA->setTitle("Assistant qualité");
        $roleA->setDescription("Création et complétion des grilles");

        $roleC = new Role();
        $roleC->setTitle("Client Distributeur");
        $roleC->setDescription("Consultation des résultats émis par le directeur de l’entreprise");


        $company->addCertification($certificationIFS);

        $company->addRegisterCode($registerCodeD);
        $company->addRegisterCode($registerCodeR);
        $company->addRegisterCode($registerCodeA);
        $company->addRegisterCode($registerCodeC);

        $registerCodeD->setRole($roleD);
        $registerCodeR->setRole($roleR);
        $registerCodeA->setRole($roleA);
        $registerCodeC->setRole($roleC);


        $manager->persist($company);
        $manager->persist($certificationIFS);
        $manager->persist($registerCodeD);
        $manager->persist($registerCodeR);
        $manager->persist($registerCodeA);
        $manager->persist($registerCodeC);
        $manager->persist($roleD);
        $manager->persist($roleR);
        $manager->persist($roleA);
        $manager->persist($roleC);

        $manager->flush();
    }
}
