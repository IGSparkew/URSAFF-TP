<?php

namespace App\Service;

use App\Entity\Company;
use App\Model\CompanyDTO;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CompanyService {

    public function __construct(private CompanyRepository $companyRepository, private EntityManagerInterface $entityManager) { }

    public function getAllCompany(string $siren=null): array {
        
        $dataCompanies = [];

        if ($siren == null) {
            $dataCompanies = $this->companyRepository->findAll();
            return $dataCompanies;  
        }
        
        $resultQuery = $this->companyRepository->findBy(['siren'=>$siren]);

        if (!empty($resultQuery)) {
            array_push($dataCompanies, $resultQuery);
        }

        return $dataCompanies;
    }

    public function upsertCompany(CompanyDTO $companyDTO, bool $isInsert) {
        try {
            $resultQuery = $this->companyRepository->findOneBy(['siren' => $companyDTO->getSiren()]);

            if (!empty($resultQuery)) {
                if($isInsert) {
                    return 409;
                }

                $adress = $companyDTO->getAdress();
                $gps = $adress->getGps();

                if ($resultQuery->getSocialRaison() != $companyDTO->getSocialRaison()) {
                    $resultQuery->setSocialRaison($companyDTO->getSocialRaison());
                }

                if ($resultQuery->getAdressNum() != $adress->getNumero()) {
                    $resultQuery->setAdressNum($adress->getNumero());
                }

                if ($resultQuery->getAdressVoie() != $adress->getVoie()) {
                    $resultQuery->setAdressVoie($adress->getVoie());
                }

                if ($resultQuery->getAdressCity() != $adress->getCity()) {
                    $resultQuery->setAdressCity($adress->getCity());
                }

                if ($resultQuery->getAdressCode() != $adress->getCodePostal()) {
                    $resultQuery->setAdressCode($adress->getCodePostal());
                }

                if ($resultQuery->getGpsLatitude() != $gps->getLatitude()) {
                    $resultQuery->setGpsLatitude($gps->getLatitude());
                }

                if ($resultQuery->getGpsLongitude() != $gps->getLongitude()) {
                    $resultQuery->setGpsLongitude($gps->getLongitude());
                }

                $this->entityManager->persist($resultQuery);
            } else {
                $company = Company::createFrom($companyDTO);
                $this->entityManager->persist($company);
            }

            $this->entityManager->flush();
            return 200;
        } catch(Exception $e) {
            dump($e);
            return 400;
        }
    }
}


?>