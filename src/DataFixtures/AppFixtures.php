<?php

namespace App\DataFixtures;

use App\Entity\Audit;
use App\Entity\Certification;
use App\Entity\Company;
use App\Entity\RegisterCode;
use App\Entity\Requirement;
use App\Entity\Role;
use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Certification IFS Ligne de production n°1
        $certificationIFS = new Certification();
        $certificationIFS->setTitle("IFS (ligne de production n°1)");
        $certificationIFS->setDescription("Norme Internationale pour la sécurité des Aliments (ligne de production n°1)");


        $theme1 = new Theme();
        $theme1->setTitle("Responsabilité de la direction");
        $theme1->setDescription("Politique, principes généraux et organisation de la société");
        $theme1->setRankCertification(1);
        $theme1->setCertification($certificationIFS);

        $requirement1T1 = new Requirement();
        $requirement1T1->setDescription("La direction s’assure que l’atteinte des objectifs est régulièrement revue (mini 1fois/an)?");
     //   $requirement1T1->setRankCertification(1);
        $requirement1T1->setRankTheme(1);
        $requirement1T1->setCertification($certificationIFS);
        $requirement1T1->setTheme($theme1);

        $requirement2T1 = new Requirement();
        $requirement2T1->setDescription("La politique de l'entreprise est-elle communiquée à l’ensemble des employés?");
     //   $requirement2T1->setRankCertification(2);
        $requirement2T1->setRankTheme(2);
        $requirement2T1->setCertification($certificationIFS);
        $requirement2T1->setTheme($theme1);

        $requirement3T1 = new Requirement();
        $requirement3T1->setDescription("l'organigramme à jour est-il affiché dans les locaux?");
      //  $requirement3T1->setRankCertification(3);
        $requirement3T1->setRankTheme(3);
        $requirement3T1->setCertification($certificationIFS);
        $requirement3T1->setTheme($theme1);

        $requirement4T1 = new Requirement();
        $requirement4T1->setDescription("Les compétences, responsabilités et délégations de responsabilités sont-elles clairement établies?");
     //   $requirement4T1->setRankCertification(4);
        $requirement4T1->setRankTheme(4);
        $requirement4T1->setCertification($certificationIFS);
        $requirement4T1->setTheme($theme1);


        $theme2 = new Theme();
        $theme2->setTitle("Système de management de la qualité et de la sécurité des aliments");
        $theme2->setDescription("Exigences sur la documentation et conservation des enregistrements");
        $theme2->setRankCertification(2);
        $theme2->setCertification($certificationIFS);

        $requirement5T2 = new Requirement();
        $requirement5T2->setDescription("Tous les documents sont-ils lisibles, non ambigus, clairs et disponibles à tout moment pour le personnel concerné?");
     //   $requirement5T2->setRankCertification(5);
        $requirement5T2->setRankTheme(1);
        $requirement5T2->setCertification($certificationIFS);
        $requirement5T2->setTheme($theme2);

        $requirement6T2 = new Requirement();
        $requirement6T2->setDescription("Les documents nécessaires à la conformité des caractéristiques du produit sont-ils disponibles et à jour?");
     //   $requirement6T2->setRankCertification(6);
        $requirement6T2->setRankTheme(2);
        $requirement6T2->setCertification($certificationIFS);
        $requirement6T2->setTheme($theme2);

        $requirement7T2 = new Requirement();
        $requirement7T2->setDescription("Les enregistrements sont-ils lisibles, authentiques, et gérés de manière à empêcher toute modification ultérieure des données?");
     //   $requirement7T2->setRankCertification(7);
        $requirement7T2->setRankTheme(3);
        $requirement7T2->setCertification($certificationIFS);
        $requirement7T2->setTheme($theme2);

        $requirement8T2 = new Requirement();
        $requirement8T2->setDescription("La chambre froide est-elle à une température constante de +3°?");
     //   $requirement8T2->setRankCertification(8);
        $requirement8T2->setRankTheme(4);
        $requirement8T2->setCertification($certificationIFS);
        $requirement8T2->setTheme($theme2);


        $theme3 = new Theme();
        $theme3->setTitle("Gestion des ressources");
        $theme3->setDescription("Hygiène, formation et instruction du personnel");
        $theme3->setRankCertification(3);
        $theme3->setCertification($certificationIFS);

        $requirement9T3 = new Requirement();
        $requirement9T3->setDescription("Le respect des exigences concernant l’hygiène du personnel est-il régulièrement vérifié?");
     //   $requirement9T3->setRankCertification(9);
        $requirement9T3->setRankTheme(1);
        $requirement9T3->setCertification($certificationIFS);
        $requirement9T3->setTheme($theme3);

        $requirement10T3 = new Requirement();
        $requirement10T3->setDescription("Des vêtements de protection adaptés sont-ils disponibles en quantité suffisante pour chaque employé?");
     //   $requirement10T3->setRankCertification(10);
        $requirement10T3->setRankTheme(2);
        $requirement10T3->setCertification($certificationIFS);
        $requirement10T3->setTheme($theme3);

        $requirement11T3 = new Requirement();
        $requirement11T3->setDescription("Le contenu des formations et/ou instructions est-il régulièrement contrôlé et mis à jour?");
     //   $requirement11T3->setRankCertification(11);
        $requirement11T3->setRankTheme(3);
        $requirement11T3->setCertification($certificationIFS);
        $requirement11T3->setTheme($theme3);

        $requirement12T3 = new Requirement();
        $requirement12T3->setDescription("Les enregistrements de toutes les formations/instructions réalisés sont-ils disponibles?");
     //   $requirement12T3->setRankCertification(12);
        $requirement12T3->setRankTheme(4);
        $requirement12T3->setCertification($certificationIFS);
        $requirement12T3->setTheme($theme3);


        $theme4 = new Theme();
        $theme4->setTitle("Planification et procédé de fabrication");
        $theme4->setDescription("Emballage du produit, réception et stockage");
        $theme4->setRankCertification(4);
        $theme4->setCertification($certificationIFS);

        $requirement13T4 = new Requirement();
        $requirement13T4->setDescription("Existent-ils des spécifications détaillées respectant la législation en vigueur pour tous les matériaux d’emballage?");
     //   $requirement13T4->setRankCertification(13);
        $requirement13T4->setRankTheme(1);
        $requirement13T4->setCertification($certificationIFS);
        $requirement13T4->setTheme($theme4);

        $requirement14T4 = new Requirement();
        $requirement14T4->setDescription("Les informations sur l’étiquette sont-elles lisibles, indélébiles et conformes aux produits conditionnés?");
      //  $requirement14T4->setRankCertification(14);
        $requirement14T4->setRankTheme(2);
        $requirement14T4->setCertification($certificationIFS);
        $requirement14T4->setTheme($theme4);

        $requirement15T4 = new Requirement();
        $requirement15T4->setDescription("Les matières premières sont-elles stockées correctement afin de minimiser le risque de contamination croisée?");
      //  $requirement15T4->setRankCertification(15);
        $requirement15T4->setRankTheme(3);
        $requirement15T4->setCertification($certificationIFS);
        $requirement15T4->setTheme($theme4);

        $requirement16T4 = new Requirement();
        $requirement16T4->setDescription("la gestion des stocks respecte-elle les règles du FIFO/FEFO?");
     //   $requirement16T4->setRankCertification(16);
        $requirement16T4->setRankTheme(4);
        $requirement16T4->setCertification($certificationIFS);
        $requirement16T4->setTheme($theme4);


        $theme5 = new Theme();
        $theme5->setTitle("Mesures, Analyses et améliorations");
        $theme5->setDescription("Inspections d’usine et contrôles quantitatifs (poids, volume…)");
        $theme5->setRankCertification(5);
        $theme5->setCertification($certificationIFS);

        $requirement17T5 = new Requirement();
        $requirement17T5->setDescription("La traçabilité est-elle garantie à toutes les étapes( y compris les productions en cours, les retraitements et le recyclage)?");
      //  $requirement17T5->setRankCertification(17);
        $requirement17T5->setRankTheme(1);
        $requirement17T5->setCertification($certificationIFS);
        $requirement17T5->setTheme($theme5);

        $requirement18T5 = new Requirement();
        $requirement18T5->setDescription("Le système de traçabilité est-il testé à une fréquence définie, au moins annuellement et à chaque fois que le système change?");
     //   $requirement18T5->setRankCertification(18);
        $requirement18T5->setRankTheme(2);
        $requirement18T5->setCertification($certificationIFS);
        $requirement18T5->setTheme($theme5);

        $requirement19T5 = new Requirement();
        $requirement19T5->setDescription("Les Résultats sont-ils conformes aux critères définis par produits afin d'être livrés?");
     //   $requirement19T5->setRankCertification(19);
        $requirement19T5->setRankTheme(3);
        $requirement19T5->setCertification($certificationIFS);
        $requirement19T5->setTheme($theme5);

        $requirement20T5 = new Requirement();
        $requirement20T5->setDescription("Les appareils de mesure sont-ils vérifiés, ajustés et étalonnés dans le cadre d’un système de surveillance, à des fréquences spécifiées, conformément à des normes/méthodes définies et reconnues?");
     //   $requirement20T5->setRankCertification(20);
        $requirement20T5->setRankTheme(4);
        $requirement20T5->setCertification($certificationIFS);
        $requirement20T5->setTheme($theme5);


        $theme6 = new Theme();
        $theme6->setTitle("Food Defense");
        $theme6->setDescription("Sécurité du site et du personnel");
        $theme6->setRankCertification(6);
        $theme6->setCertification($certificationIFS);

        $requirement21T6 = new Requirement();
        $requirement21T6->setDescription("Les procédures sont-elles à jour pour empêcher et/ou identifier tout acte de malveillance?");
     //   $requirement21T6->setRankCertification(21);
        $requirement21T6->setRankTheme(1);
        $requirement21T6->setCertification($certificationIFS);
        $requirement21T6->setTheme($theme6);

        $requirement22T6 = new Requirement();
        $requirement22T6->setDescription("Des enregistrements ou des inspections sur site sont-elles réalisés à une fréquence définie (à minima annuellement?");
     //   $requirement22T6->setRankCertification(22);
        $requirement22T6->setRankTheme(2);
        $requirement22T6->setCertification($certificationIFS);
        $requirement22T6->setTheme($theme6);

        $requirement23T6 = new Requirement();
        $requirement23T6->setDescription("Le personnel est-il formé à la Food Defense?");
     //   $requirement23T6->setRankCertification(23);
        $requirement23T6->setRankTheme(3);
        $requirement23T6->setCertification($certificationIFS);
        $requirement23T6->setTheme($theme6);

        $requirement24T6 = new Requirement();
        $requirement24T6->setDescription("Les visiteurs et prestataires sont-ils identifiés et informés de la politique du site et de la vérification des accès?");
     //   $requirement24T6->setRankCertification(24);
        $requirement24T6->setRankTheme(4);
        $requirement24T6->setCertification($certificationIFS);
        $requirement24T6->setTheme($theme6);





        // Roles
    //    $roleD = new Role();
    //    $roleD->setTitle("Directeur");
    //    $roleD->setDescription("Suivi des résultats et validation des grilles vérifiées par le responsable qualité");
    //    $roleD->setRank(1);

        $roleR = new Role();
        $roleR->setTitle("Responsable qualité");
        $roleR->setDescription("Création et complétion des grilles et accès à l'espace d'administration");
        $roleR->setRank(2);

        $roleA = new Role();
        $roleA->setTitle("Assistant qualité");
        $roleA->setDescription("Création et complétion des grilles");
        $roleA->setRank(3);

        // Code for a company
        $company = new Company();
        $company->setName("Fleury Michon");


    //    $registerCodeD = new RegisterCode();
    //    $registerCodeD->setCodeContent("X29JTZ22");
    //    $registerCodeD->setMaxNbOfUsers(2);

        $registerCodeR = new RegisterCode();
        $registerCodeR->setCodeContent("UT28SK78");
        $registerCodeR->setMaxNbOfUsers(5);

        $registerCodeA = new RegisterCode();
        $registerCodeA->setCodeContent("AS59ET47");
        $registerCodeA->setMaxNbOfUsers(10);


        //Code for EKALIT
        $companyE = new Company();
        $companyE->setName("EkalitTestSociété");

        $registerCodeDE = new RegisterCode();
        $registerCodeDE->setCodeContent("E29JTZ22");
        $registerCodeDE->setMaxNbOfUsers(3);

        $registerCodeRE = new RegisterCode();
        $registerCodeRE->setCodeContent("ET28SK78");
        $registerCodeRE->setMaxNbOfUsers(3);

        $registerCodeAE = new RegisterCode();
        $registerCodeAE->setCodeContent("ES59ET47");
        $registerCodeAE->setMaxNbOfUsers(3);



        $company->addCertification($certificationIFS);
     //   $companyE->addCertification($certificationIFS);


    //    $registerCodeD->setRole($roleD);
        $registerCodeR->setRole($roleR);
        $registerCodeA->setRole($roleA);

    //    $registerCodeDE->setRole($roleD);
        $registerCodeRE->setRole($roleR);
        $registerCodeAE->setRole($roleA);

    //    $registerCodeD->setCompany($company);
        $registerCodeR->setCompany($company);
        $registerCodeA->setCompany($company);

        $registerCodeDE->setCompany($companyE);
        $registerCodeRE->setCompany($companyE);
        $registerCodeAE->setCompany($companyE);


     //   $company->addRegisterCode($registerCodeD);
        $company->addRegisterCode($registerCodeR);
        $company->addRegisterCode($registerCodeA);

        $companyE->addRegisterCode($registerCodeDE);
        $companyE->addRegisterCode($registerCodeRE);
        $companyE->addRegisterCode($registerCodeAE);


        $manager->persist($certificationIFS);

        $manager->persist($theme1);
        $manager->persist($theme2);
        $manager->persist($theme3);
        $manager->persist($theme4);
        $manager->persist($theme5);
        $manager->persist($theme6);

        $manager->persist($requirement1T1);
        $manager->persist($requirement2T1);
        $manager->persist($requirement3T1);
        $manager->persist($requirement4T1);
        $manager->persist($requirement5T2);
        $manager->persist($requirement6T2);
        $manager->persist($requirement7T2);
        $manager->persist($requirement8T2);
        $manager->persist($requirement9T3);
        $manager->persist($requirement10T3);
        $manager->persist($requirement11T3);
        $manager->persist($requirement12T3);
        $manager->persist($requirement13T4);
        $manager->persist($requirement14T4);
        $manager->persist($requirement15T4);
        $manager->persist($requirement16T4);
        $manager->persist($requirement17T5);
        $manager->persist($requirement18T5);
        $manager->persist($requirement19T5);
        $manager->persist($requirement20T5);
        $manager->persist($requirement21T6);
        $manager->persist($requirement22T6);
        $manager->persist($requirement23T6);
        $manager->persist($requirement24T6);


        $manager->persist($company);
        $manager->persist($companyE);

    //    $manager->persist($roleD);
        $manager->persist($roleR);
        $manager->persist($roleA);

    //    $manager->persist($registerCodeD);
        $manager->persist($registerCodeR);
        $manager->persist($registerCodeA);
        $manager->persist($registerCodeDE);
        $manager->persist($registerCodeRE);
        $manager->persist($registerCodeAE);


        $manager->flush();
    }
}
