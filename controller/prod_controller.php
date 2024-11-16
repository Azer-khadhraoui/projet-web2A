<?php
include (__DIR__.'/../config.php');
include (__DIR__.'/../model/product_mod.php');

class TravelOfferController {
    public function showTravelOffer($offer) {
        public function listoffre() {
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
