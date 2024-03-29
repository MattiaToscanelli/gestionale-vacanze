<?php

class Application
{
    /**
     * @var null Controlle da richiamare.
     */
    private $url_controller = null;

    /**
     * @var null Metodo da chiamre nel controller.
     */
    private $url_action = null;

    /**
     * @var null Primo parametro nel controller.
     */
    private $url_parameter_1 = null;

    /**
     * @var null Secondo parametro nel controller.
     */
    private $url_parameter_2 = null;

    /**
     * @var null Terzo parametro nel controller.
     */
    private $url_parameter_3 = null;

    /**
     * Application constructor. Metodo costruttore senza parametri, prepara il controller e il metodo in base all'url.
     */
    public function __construct()
    {
        $this->splitUrl(); //funzione da creare per dividere l'URL
        if (file_exists('./application/controller/' . $this->url_controller . '.php')) {
            require './application/controller/' . $this->url_controller . '.php';
            $this->url_controller = new $this->url_controller();
            if (method_exists($this->url_controller, $this->url_action)) {
                if (isset($this->url_parameter_3)) {
                    $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2,
                        $this->url_parameter_3);
                } elseif (isset($this->url_parameter_2)) {
                    $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2);
                } elseif (isset($this->url_parameter_1)) {
                    $this->url_controller->{$this->url_action}($this->url_parameter_1);
                } else {
                    $this->url_controller->{$this->url_action}();
                }
            } else {
                $this->url_controller->index();
            }
        } else if ($this->url_controller == "") {
            require './application/controller/home.php';
            $home = new Home();
            $home->index();
        }else {
            require './application/controller/errore.php';
            $errore = new Errore();
            $errore->index();
        }
    }

    /**
     * Splitto l'url URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // tolgo il carattere / dalla fine della stringa
            $url = rtrim($_GET['url'], '/');
            //rimuove tutti i caratteri illegali dall'URL
            $url = filter_var($url, FILTER_SANITIZE_URL);
            //divido in un array in base al carattere /
            $url = explode('/', $url);

            // divido le parti dell'utl in base a controller, azione e 3 parametri
            $this->url_controller = (isset($url[0]) ? $url[0] : null);
            $this->url_action = (isset($url[1]) ? $url[1] : null);
            $this->url_parameter_1 = (isset($url[2]) ? $url[2] : null);
            $this->url_parameter_2 = (isset($url[3]) ? $url[3] : null);
            $this->url_parameter_3 = (isset($url[4]) ? $url[4] : null);

            // Per debug
            // echo 'Controller: ' . $this->url_controller . '<br />';
            // echo 'Action: ' . $this->url_action . '<br />';
            // echo 'Parameter 1: ' . $this->url_parameter_1 . '<br />';
            // echo 'Parameter 2: ' . $this->url_parameter_2 . '<br />';
            // echo 'Parameter 3: ' . $this->url_parameter_3 . '<br />';
             
        }
    }

}
