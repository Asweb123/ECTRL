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
        $company->setCompanyName("Fleury Michon");


        $certificationIFS = new Certification();
        $certificationIFS->setCertifTitle("IFS");
        $certificationIFS->setCertifDescription("Norme Internationale pour la sécurité des Aliments");


        $registerCodeD = new RegisterCode();
        $registerCodeD->setCodeContent("X29JTZ22");

        $registerCodeR = new RegisterCode();
        $registerCodeR->setCodeContent("UT28SK78");

        $registerCodeA = new RegisterCode();
        $registerCodeA->setCodeContent("AS59ET47");

        $registerCodeC = new RegisterCode();
        $registerCodeC->setCodeContent("VG87SY45");


        $roleD = new Role();
        $roleD->setRoleTitle("Directeur");
        $roleD->setRoleDescription("Suivi des résultats et validation des grilles vérifiées par le responsable qualité");

        $roleR = new Role();
        $roleR->setRoleTitle("Responsable qualité");
        $roleR->setRoleDescription("Création, complétion et validation des grilles");

        $roleA = new Role();
        $roleA->setRoleTitle("Assistant qualité");
        $roleA->setRoleDescription("Création et complétion des grilles");

        $roleC = new Role();
        $roleC->setRoleTitle("Client Distributeur");
        $roleC->setRoleDescription("Consultation des résultats émis par le directeur de l’entreprise");


        $company->addCertification($certificationIFS);

        $company->addCompanyRegisterCode($registerCodeD);
        $company->addCompanyRegisterCode($registerCodeR);
        $company->addCompanyRegisterCode($registerCodeA);
        $company->addCompanyRegisterCode($registerCodeC);

        $registerCodeD->setRegisterCodeRole($roleD);
        $registerCodeR->setRegisterCodeRole($roleR);
        $registerCodeA->setRegisterCodeRole($roleA);
        $registerCodeC->setRegisterCodeRole($roleC);


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
