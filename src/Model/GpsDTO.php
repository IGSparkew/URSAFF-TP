<?php 
    namespace App\Model;

    class GpsDTO {

        public function __construct(private string $latitude, private string $longitude) { }

        /**
         * Get the value of latitude
         *
         * @return string
         */
        public function getLatitude(): string {
                return $this->latitude;
        }

        /**
         * Set the value of latitude
         *
         * @param string $latitude
         *
         * @return self
         */
        public function setLatitude(string $latitude): self {
                $this->latitude = $latitude;
                return $this;
        }

        /**
         * Get the value of longitude
         *
         * @return string
         */
        public function getLongitude(): string {
                return $this->longitude;
        }

        /**
         * Set the value of longitude
         *
         * @param string $longitude
         *
         * @return self
         */
        public function setLongitude(string $longitude): self {
                $this->longitude = $longitude;
                return $this;
        }
    }
?>