<?php
    namespace App\Model;
    
    use Symfony\Component\Serializer\Annotation\SerializedName;

    class CompanyDTO {

        #[SerializedName('siren')]
        private ?string $siren;
        
        #[SerializedName('raison_sociale')]
        private string $social_raison; 

        #[SerializedName('adresse')]
        private ?AdressDTO $adress;
        
        
        public function __construct(string $siren="", string $social_raison, AdressDTO $adress = null) {
                $this->siren = $siren;
                $this->social_raison = $social_raison;
                $this->adress = $adress;
        }


        /**
         * Get the value of siren
         *
         * @return string
         */
        public function getSiren(): string {
                return $this->siren;
        }

        /**
         * Set the value of siren
         *
         * @param string $siren
         *
         * @return self
         */
        public function setSiren(string $siren): self {
                $this->siren = $siren;
                return $this;
        }

        /**
         * Get the value of social_raison
         *
         * @return string
         */
        public function getSocialRaison(): string {
                return $this->social_raison;
        }

        /**
         * Set the value of social_raison
         *
         * @param string $social_raison
         *
         * @return self
         */
        public function setSocialRaison(string $social_raison): self {
                $this->social_raison = $social_raison;
                return $this;
        }

        /**
         * Get the value of adress
         *
         * @return AdressDTO
         */
        public function getAdress(): AdressDTO | null {
                return $this->adress;
        }
    }
?>