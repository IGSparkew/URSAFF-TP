<?php

namespace App\Service;

use App\Repository\CompanyRepository;

class CompanyService {

    public function __construct(private CompanyRepository $companyRepository) { }

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
}


?>