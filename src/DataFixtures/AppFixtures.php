<?php

namespace App\DataFixtures;

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
        $company->setCompanyPlan("Certification");

        $registerCodeD = new RegisterCode();
        $registerCodeD->setCodeContent("X29JTZ22");

        $registerCodeR = new RegisterCode();
        $registerCodeR->setCodeContent("UT28SK78");

        $registerCodeA = new RegisterCode();
        $registerCodeA->setCodeContent("AS59ET47");

        $roleD = new Role();
        $roleD->setRoleTitle("Directeur");
        $roleD->setRoleDescription("Suivi des résultats et validation des grilles vérifiées par le responsable qualité");

        $roleR = new Role();
        $roleR->setRoleTitle("Responsable qualité");
        $roleR->setRoleDescription("Validation et complétion des grilles");

        $roleA = new Role();
        $roleA->setRoleTitle("Assistant qualité");
        $roleA->setRoleDescription("Complétion des grilles");

        $company->addCompanyRegisterCode($registerCodeD);
        $company->addCompanyRegisterCode($registerCodeR);
        $company->addCompanyRegisterCode($registerCodeA);

        $registerCodeD->setRegisterCodeRole($roleD);
        $registerCodeR->setRegisterCodeRole($roleR);
        $registerCodeA->setRegisterCodeRole($roleA);



        $manager->persist($company);
        $manager->persist($registerCodeD);
        $manager->persist($registerCodeR);
        $manager->persist($registerCodeA);
        $manager->persist($roleD);
        $manager->persist($roleR);
        $manager->persist($roleA);

        $manager->flush();
    }
}
