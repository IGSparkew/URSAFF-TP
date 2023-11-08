<?php 

    class DefaultMessage {

        

         public function __construct(private string $message, private ?string $uri = null) { }
        
        /**
         * Get the value of message
         *
         * @return string
         */
        public function getMessage(): string {
                return $this->message;
        }

        /**
         * Set the value of message
         *
         * @param string $message
         *
         * @return self
         */
        public function setMessage(string $message): self {
                $this->message = $message;
                return $this;
        }

        /**
         * Get the value of uri
         *
         * @return ?string
         */
        public function getUri(): ?string {
                return $this->uri;
        }

        /**
         * Set the value of uri
         *
         * @param ?string $uri
         *
         * @return self
         */
        public function setUri(?string $uri): self {
                $this->uri = $uri;
                return $this;
        }
     }
?>