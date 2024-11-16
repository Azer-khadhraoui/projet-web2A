<?php
include (__DIR__.'/../config.php');
include (__DIR__.'/../model/products_mod.php');

class TravelOfferController {
    public function showoffer($offer) {
        public function list() {
            $sql="SELECT * FROM produits";
            $db=config :: getConnexion();
            try{
                $liste=$db->query($sql);
                return $liste ;

            }
            catch(Exception $e){
                die("error ".$e->getMessage());
            }
        }
        $offer->show();
    }
}
?>
