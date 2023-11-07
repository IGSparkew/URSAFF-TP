<?php 
    namespace App\Model;

    use Symfony\Component\Serializer\Annotation\SerializedName;


    class GpsDTO {
        
        #[SerializedName('latitude')]
        private float $latitude;
        
        #[SerializedName('longitude')]
        private float $longitude;

        public function __construct(float $latitude, float $longitude) { 
                $this->longitude = $longitude;
                $this->latitude = $latitude;
        }

        /**
         * Get the value of latitude
         *
         * @return float
         */
        public function getLatitude(): float {
                return $this->latitude;
        }

        /**
         * Set the value of latitude
         *
         * @param float $latitude
         *
         * @return self
         */
        public function setLatitude(float $latitude): self {
                $this->latitude = $latitude;
                return $this;
        }

        /**
         * Get the value of longitude
         *
         * @return string
         */
        public function getLongitude(): float {
                return $this->longitude;
        }

        /**
         * Set the value of longitude
         *
         * @param float $longitude
         *
         * @return self
         */
        public function setLongitude(float $longitude): self {
                $this->longitude = $longitude;
                return $this;
        }
    }
?>