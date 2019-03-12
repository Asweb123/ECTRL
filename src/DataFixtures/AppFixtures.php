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

        $certificationIFS = new Certification();
        $certificationIFS->setTitle("IFS");
        $certificationIFS->setDescription("Norme Internationale pour la sécurité des Aliments");


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


        // Code for a company
        $company = new Company();
        $company->setName("Fleury Michon");


        $registerCodeD = new RegisterCode();
        $registerCodeD->setCodeContent("X29JTZ22");
        $registerCodeD->setMaxNbOfUsers(3);

        $registerCodeR = new RegisterCode();
        $registerCodeR->setCodeContent("UT28SK78");
        $registerCodeR->setMaxNbOfUsers(3);

        $registerCodeA = new RegisterCode();
        $registerCodeA->setCodeContent("AS59ET47");
        $registerCodeA->setMaxNbOfUsers(3);

        $registerCodeC = new RegisterCode();
        $registerCodeC->setCodeContent("VG87SY45");
        $registerCodeC->setMaxNbOfUsers(3);


        //Code for EKALIT
        $companyE = new Company();
        $companyE->setName("EKALIT");

        $registerCodeDE = new RegisterCode();
        $registerCodeDE->setCodeContent("E29JTZ22");
        $registerCodeDE->setMaxNbOfUsers(3);

        $registerCodeRE = new RegisterCode();
        $registerCodeRE->setCodeContent("ET28SK78");
        $registerCodeRE->setMaxNbOfUsers(3);

        $registerCodeAE = new RegisterCode();
        $registerCodeAE->setCodeContent("ES59ET47");
        $registerCodeAE->setMaxNbOfUsers(3);

        $registerCodeCE = new RegisterCode();
        $registerCodeCE->setCodeContent("EG87SY45");
        $registerCodeCE->setMaxNbOfUsers(10);



        $company->addCertification($certificationIFS);
        $companyE->addCertification($certificationIFS);


        $registerCodeD->setRole($roleD);
        $registerCodeR->setRole($roleR);
        $registerCodeA->setRole($roleA);
        $registerCodeC->setRole($roleC);

        $registerCodeDE->setRole($roleD);
        $registerCodeRE->setRole($roleR);
        $registerCodeAE->setRole($roleA);
        $registerCodeCE->setRole($roleC);

        $registerCodeD->setCompany($company);
        $registerCodeR->setCompany($company);
        $registerCodeA->setCompany($company);
        $registerCodeC->setCompany($company);

        $registerCodeDE->setCompany($companyE);
        $registerCodeRE->setCompany($companyE);
        $registerCodeAE->setCompany($companyE);
        $registerCodeCE->setCompany($companyE);


        $company->addRegisterCode($registerCodeD);
        $company->addRegisterCode($registerCodeR);
        $company->addRegisterCode($registerCodeA);
        $company->addRegisterCode($registerCodeC);

        $companyE->addRegisterCode($registerCodeD);
        $companyE->addRegisterCode($registerCodeR);
        $companyE->addRegisterCode($registerCodeA);
        $companyE->addRegisterCode($registerCodeC);




        $manager->persist($company);
        $manager->persist($companyE);

        $manager->persist($certificationIFS);

        $manager->persist($roleD);
        $manager->persist($roleR);
        $manager->persist($roleA);
        $manager->persist($roleC);

        $manager->persist($registerCodeD);
        $manager->persist($registerCodeR);
        $manager->persist($registerCodeA);
        $manager->persist($registerCodeC);
        $manager->persist($registerCodeDE);
        $manager->persist($registerCodeRE);
        $manager->persist($registerCodeAE);
        $manager->persist($registerCodeCE);


        $manager->flush();
    }
}
