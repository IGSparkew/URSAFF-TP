<?php
    namespace App\Model;

    use Symfony\Component\Serializer\Annotation\SerializedName;
    
    class AdressDTO {

        #[SerializedName('num')]
        private int $numero;

        #[SerializedName('voie')]
         private string $voie;
         
        #[SerializedName('ville')]
         private string $city;

         #[SerializedName('code_postale')]
         private int $code_postal;

         #[SerializedName('gps')]
         private GpsDTO $gps;

        public function __construct(int $numero, string $voie, string $city, int $code_postal, GpsDTO $gps) {
                $this->numero = $numero;
                $this->voie = $voie;
                $this->city = $city;
                $this->code_postal = $code_postal;
                $this->gps = $gps;
         }

        /**
         * Get the value of numero
         *
         * @return int
         */
        public function getNumero(): int {
                return $this->numero;
        }

        /**
         * Set the value of numero
         *
         * @param int $numero
         *
         * @return self
         */
        public function setNumero(int $numero): self {
                $this->numero = $numero;
                return $this;
        }

        /**
         * Get the value of voie
         *
         * @return string
         */
        public function getVoie(): string {
                return $this->voie;
        }

        /**
         * Set the value of voie
         *
         * @param string $voie
         *
         * @return self
         */
        public function setVoie(string $voie): self {
                $this->voie = $voie;
                return $this;
        }

        /**
         * Get the value of city
         *
         * @return string
         */
        public function getCity(): string {
                return $this->city;
        }

        /**
         * Set the value of city
         *
         * @param string $city
         *
         * @return self
         */
        public function setCity(string $city): self {
                $this->city = $city;
                return $this;
        }

         /**
          * Get the value of code_postal
          *
          * @return int
          */
         public function getCodePostal(): int {
                  return $this->code_postal;
         }

         /**
          * Set the value of code_postal
          *
          * @param int $code_postal
          *
          * @return self
          */
         public function setCodePostal(int $code_postal): self {
                  $this->code_postal = $code_postal;
                  return $this;
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