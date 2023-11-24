<?php

namespace App\Service;

use App\Entity\Company;
use App\Model\CompanyDTO;
use App\Repository\CompanyRepository;
use CompanyExistingException;
use CompanyNotExistException;
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

    public function insertCompany(CompanyDTO $companyDTO) {
        try {
            $resultQuery = $this->companyRepository->findOneBy(['siren' => $companyDTO->getSiren()]);
            $sirenToReturn = null;

            if (!empty($resultQuery)) {
                throw new CompanyExistingException();     
            } 

            $company = Company::createFrom($companyDTO);
            $this->entityManager->persist($company);
            $sirenToReturn = $companyDTO->getSiren();
            
            $this->entityManager->flush();
            return $sirenToReturn;
        } catch (CompanyExistingException $cee) {
           throw $cee;
           return null;
        }catch(Exception $e) {
            dump($e);
            return null;
        }
    }

    public function updateCompany(CompanyDTO $companyDTO, string $siren) {
        try {
            $resultQuery = $this->companyRepository->findOneBy(['siren' => $siren]);
            if (empty($resultQuery)) {
                throw new CompanyNotExistException();
            }

            $adress = $companyDTO->getAdress();
            

            if ($resultQuery->getSocialRaison() != $companyDTO->getSocialRaison()) {
                $resultQuery->setSocialRaison($companyDTO->getSocialRaison());
            }

            if (!empty($adress)) {
                $gps = $adress->getGps();

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

                if (!empty($gps)) {

                    if ($resultQuery->getGpsLatitude() != $gps->getLatitude()) {
                        $resultQuery->setGpsLatitude($gps->getLatitude());
                    }
        
                    if ($resultQuery->getGpsLongitude() != $gps->getLongitude()) {
                        $resultQuery->setGpsLongitude($gps->getLongitude());
                    }
                }
            }
            $this->entityManager->persist($resultQuery);
            $this->entityManager->flush();
            $sirenToReturn = $resultQuery->getSiren();

            return $sirenToReturn;
        } catch (CompanyNotExistException $cce) {
            throw $cce;
            return null;
        }catch(Exception $e) {
            dump($e);
            return null;
        }
    }

    public function remove(string $siren) {
        try {
            $finded = $this->companyRepository->findOneBy(["siren"=>$siren]);
            if (empty($finded)) {
                throw new CompanyNotExistException();
            }
            $isDelete = $this->companyRepository->delete($finded);
            return $isDelete;
        } catch(CompanyNotExistException $cee) {
            throw $cee;
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}


?>