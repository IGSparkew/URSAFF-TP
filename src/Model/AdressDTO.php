<?php
    namespace App\Model;

    class AdressDTO {

        public function __construct(private int $numero, private string $voie, private string $city) { }

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
    }


?>