<?php
    namespace App\Model;

    class CompanyDTO {

        public function __construct(private string $siren, private string $social_raison, private AdressDTO $adress, private GpsDTO $gps) {}

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
        public function getAdress(): AdressDTO {
                return $this->adress;
        }

        /**
         * Get the value of gps
         *
         * @return GpsDTO
         */
        public function getGps(): GpsDTO {
                return $this->gps;
        }
    }
?>