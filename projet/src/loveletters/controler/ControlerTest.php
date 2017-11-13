<?php

namespace loveletters\controler;

use \loveletters\model\Carte;
use \loveletters\model\DBConnection;

class ControlerTest {
    public function index(){
        DBConnection::getInstance();
        $carte=Carte::first();
        echo('<h1>'.$carte->nom.'</h1>');
        echo('<h2>'.$carte->rang.'</h1>');
        echo('<img src="'.$carte->url_illus.'" />');
        echo('<p>'.$carte->effet.'</p>');
        var_dump($carte);
    }
}