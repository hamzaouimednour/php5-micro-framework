    <section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $pwd = ['checkout' => 'Valider la commande', 
                    'address' => 'Mes Adresses', 'orders' => 'Liste des commandes', 'restaurants' => 'Liste des restaurants', 'menu' => 'Menu du restaurant', '404' => 'Page non trouvée']
                    ?>
                    <a href="<?=HTML_PATH_ROOT?>"><strong><span class="mdi mdi-home"></span> Accueil</strong></a> <span class="mdi mdi-chevron-right"></span> <a href="#"><?=$pwd[mb_strtolower($this->getComponent())]?></a>
                </div>
            </div>
        </div>
    </section>