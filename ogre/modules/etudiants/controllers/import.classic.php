<?php


class importCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        $rep->setTitle("Import d'étudiants");

        $form = jForms::create('import_apogee');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'etudiants~import:doimport');

        $rep->body->assign('MAIN', $tpl->fetch('etudiants~import_apogee'));

        return $rep;
    }
    
    function index2() {
        $rep = $this->getResponse('html');

        $rep->setTitle("Import de notes");

        $form = jForms::create('import_pv');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'etudiants~import:doimport_pv');

        $rep->body->assign('MAIN', $tpl->fetch('etudiants~import_apogee'));

        return $rep;
    }
    
    function geisha() {
        $rep = $this->getResponse('html');

        $rep->setTitle("Import du fichier geisha");

        $form = jForms::create('import_geisha');

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('submitAction', 'etudiants~import:doimport_geisha');

        $rep->body->assign('MAIN', $tpl->fetch('etudiants~import_apogee'));

        return $rep;
    }
    
    function doimport_geisha(){
        $rep = $this->getResponse('redirect');
        
        $form = jForms::fill('import_geisha');
        
        $name = $_FILES['csv_geisha']['tmp_name'];
       
       // jLog::dump($form);
        jClasses::inc('utils~Geisha');
        

        $csvParser = new Geisha($name);
        $csvParser->codeFormation(";",$form->getData('annee'));

      //  Logger::log('import_pv', $name,$nbAjout);

        jMessage::add("L'importation a reussie !");

        $rep->action = 'ogre~default:index';
        return $rep;
    }
    
    
    function doimport_pv(){
        $rep = $this->getResponse('redirect');
        

        $name = $_FILES['csv_pv']['tmp_name'];
       

        jClasses::inc('utils~NotePv');
        

        $csvParser = new NotePv($name);
        $nbAjout = $csvParser->parse();

        Logger::log('import_pv', $name,$nbAjout);

        jMessage::add("L'importation a reussie !");

        $rep->action = 'etudiants~import:index2';
        return $rep;
    }
    
    
    function doimport(){
        $rep = $this->getResponse('redirect');
        
        $form = jForms::fill('import_apogee');
        
        $realname = $_FILES['csv_apogee']['name'];
        
        $name = date('dMY') . '-' . time() . '.csv' ;
        
        if( !$form->saveFile('csv_apogee', JELIX_APP_VAR_PATH.'uploads/csv_apogee/', $name)) {
            jMessage::add("L'importation a echouée...");
            $rep->action = 'etudiants~import:index';
            return $rep;
        }

        jForms::destroy('import_apogee');


        jClasses::inc('utils~etudiantsApogee');
        jClasses::inc('utils~customSQL');
        jClasses::inc('utils~Moyenne');
        jClasses::inc('utils~NoteImport');

        $csvParser = new etudiantsApogee(JELIX_APP_VAR_PATH.'uploads/csv_apogee/'.$name);
        $etudiants = $csvParser->parse();

        $factory = jDao::get('etudiants~etudiants');
        
        $factoryformation = jDao::get('formations~formation');
        $factorysemestre = jDao::get('formations~semestre');
        $factoryetu_semestre = jDao::get('etudiants~etudiants_semestre');
        $etudiant_formation_factory = jDao::get('formations~etudiants_semestre_semestre_formation');
        
        $nb = 0;
        $dt = new jDateTime();
        
        foreach($etudiants as $etu){
            //Ajouts de l'etudiant si il n'existe pas
            if(!customSQL::etudiantsExisteDeja($etu->num_etudiant)) {
                $etudiant = jDao::createRecord('etudiants~etudiants');
                
                $etudiant->num_etudiant = $etu->num_etudiant;
                $etudiant->sexe = $etu->sexe;
                $etudiant->nom = utf8_encode(strtoupper($etu->nom));
                $etudiant->prenom = utf8_encode(ucfirst(strtolower($etu->prenom)));
                $etudiant->nom_usuel = utf8_encode(strtoupper($etu->nom_usuel));
                
                $etudiant->adresse = utf8_encode($etu->adresse);
                $etudiant->code_postal = $etu->code_postal;
                $etudiant->ville =  utf8_encode($etu->ville);
                $etudiant->email = utf8_encode($etu->email);
                $etudiant->telephone = $etu->telephone;
                
                //transformation de la date en format francais en format anglais
                $dt->setFromString($etu->date_naissance, jDateTime::LANG_DFORMAT);
                $etudiant->date_naissance = $dt->toString(jDateTime::DB_DFORMAT);
                
                $factory->insert($etudiant);
                
                //Creations des futur entree de la bd
                $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');
                
                $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                //Recuperation de l'id de la formation a partir de son code puis recuperation des id des semestres
                $idform = $factoryformation->getLastFormationByCode(utf8_encode($etu->formation));
                foreach($idform as $idformee)
                    $liste_semestre = $factorysemestre->getByFormation($idformee->id_formation);
                //Creation et insertion dans la table etudiant_semestre
                foreach($liste_semestre as $semestre){
                    $etudiant_semestre->id_semestre = $semestre->id_semestre;
                    $etudiant_semestre->statut = "ENC";
                    $factoryetu_semestre->insert($etudiant_semestre);
                }
            //Si L'etudiant existe deja
            }else{
                //Recuperation des derniers formations de l'etudiants
                $etu_sem_for = $etudiant_formation_factory->getLastFormationByEtudiant($etu->num_etudiant);
                $i=0;
                $countENC=0;
                $countAJO=0;
                $countADM=0;
                foreach($etu_sem_for as $instance ){
                    switch($instance->statut){
                        //TODO Verifier si pas deja fais !!!!
                        case "ENC":
                        case "DET":$countENC++;break;
                        case "AJO":$countAJO++;break;
                        case "ADM":$countADM++;break;
                        case "AJC":
                            $countADM++;
                            //import note et inscription semestre en DET,
                            //L'inscription a l'année suivant pris en charge par ADM
                            $liste_semestre = $factorysemestre->getByFormation($factoryformation->getOneLastFormationByCode($instance->code_formation)->id_formation);
                            foreach($liste_semestre as $semestre){
                                if($semestre->num_semestre == $instance->num_semestre){
                                    //Creations des futur entree de la bd     
                                    $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');
                                    $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                                    $etudiant_semestre->id_semestre = $semestre->id_semestre;
                                    $etudiant_semestre->statut = "DET";
                                    //Insertion dans la bd si necessaire
                                    if(!customSQL::etudiantSemestreExisteDeja($etu->num_etudiant,$semestre->id_semestre)){
                                        $factoryetu_semestre->insert($etudiant_semestre);
                                    }
                                }
                            }
                            //Calcul de la moyenne puis ajouts de la dispense si necessaire
                            $moyenne=Moyenne::calcAllMoyenne($instance->id_semestre,$instance->num_etudiant);
                            foreach($moyenne as $key => $moy){
                                //Si moyenne superieur a 10 on importe les notes dans la nouvelle ue ? et dispense
                                if($moy>=10){
                                    //Verifie qu'on prends bien la bonne ue
                                    $ue_factory=Jdao::get('ue~ue');
                                    $ue=$ue_factory->get($key);
                                    $tmp_ue_id = $ue_factory->getLastUeByCode($ue->code_ue)->id_ue;
                                    //IMPORT NOTE
                                    NoteImport::importAllNotes($instance->id_semestre,$instance->num_etudiant,$key);
                                    //Creation de la dispense si necessaire
                                    if(!customSQL::DispenseValideExiste($etudiant_semestre->id_semestre,$instance->num_etudiant,$tmp_ue_id)){
                                        $dispense_factory=Jdao::get('etudiants~dispense');
                                        $dispense = jDao::createRecord('etudiants~dispense');
                                        $dispense->num_etudiant = $instance->num_etudiant;
                                        $dispense->id_semestre = $etudiant_semestre->id_semestre;
                                        $dispense->valide = TRUE;
                                        $dispense->commentaire = "Ue deja valide l'année d'avant";
                                        //$dispense->endette ?
                                        $dispense->id_ue = $tmp_ue_id;
                                        //Insertion de la dispense
                                        $dispense_factory->insert($dispense);
                                    }
                                }else{
                                    //Verifie qu'on prends bien la bonne ue
                                    $ue_factory=Jdao::get('ue~ue');
                                    $ue=$ue_factory->get($key);
                                    $tmp_ue_id = $ue_factory->getLastUeByCode($ue->code_ue)->id_ue;
                                    //Creation de la dispense si necessaire
                                        if(!customSQL::DispensEndetteExiste($etudiant_semestre->id_semestre,$instance->num_etudiant,$tmp_ue_id)){
                                        $dispense_factory=Jdao::get('etudiants~dispense');
                                        $dispense = jDao::createRecord('etudiants~dispense');
                                        $dispense->num_etudiant = $instance->num_etudiant;
                                        $dispense->id_semestre = $etudiant_semestre->id_semestre;
                                        $dispense->endette = TRUE;
                                        $dispense->commentaire = "AJC";
                                        $dispense->id_ue = $tmp_ue_id;
                                        //Insertion de la dispense
                                        $dispense_factory->insert($dispense);
                                    }
                                }
                            }
                        break;
                        default:break;
                        
                    }
                    $i++;
                    
                    
                }
                //Si en cours on ne modifie rien
                if($i==$countENC){
                    //RIEN
                }else if ($i==$countAJO){
                    //Si l'etudiant est ajournée
                    //Creations des futur entree de la bd si necessaire
                    $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');                    
                    $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                    $idform = $factoryformation->getLastFormationByCode(utf8_encode($etu->formation));
                    foreach($idform as $idformee)
                        $liste_semestre = $factorysemestre->getByFormation($idformee->id_formation);
                    //Creation et insertion dans la table etudiant_semestre
                    foreach($liste_semestre as $semestre){                        
                        $etudiant_semestre->id_semestre = $semestre->id_semestre;
                        $etudiant_semestre->statut = "ENC";
                        if(!customSQL::etudiantSemestreExisteDeja($etu->num_etudiant,$semestre->id_semestre)){
                            $factoryetu_semestre->insert($etudiant_semestre);
                        }
                    }
                    //Garder ses notes en cas de Ajourne
                    //Calcul de la moyenne puis ajouts de la dispense si necessaire
                    $moyenne=Moyenne::calcAllMoyenne($instance->id_semestre,$instance->num_etudiant);
                    foreach($moyenne as $key => $moy){
                        //Si moyenne superieur a 10 on importe les notes dans la nouvelle ue et dispense
                        if($moy>=10){
                            //IMPORT NOTE
                            NoteImport::importAllNotes($instance->id_semestre,$instance->num_etudiant,$key);
                            //Creation de la dispense si necessaire
                            $ue_factory=Jdao::get('ue~ue');
                            $ue=$ue_factory->get($key);
                            $tmp_ue_id = $ue_factory->getLastUeByCode($ue->code_ue)->id_ue;
                            if(!customSQL::DispenseValideExiste($etudiant_semestre->id_semestre,$instance->num_etudiant,$tmp_ue_id)){
                                $dispense_factory=Jdao::get('etudiants~dispense');
                                $dispense = jDao::createRecord('etudiants~dispense');
                                $dispense->num_etudiant = $instance->num_etudiant;
                                $dispense->id_semestre = $etudiant_semestre->id_semestre;
                                $dispense->valide = TRUE;
                                $dispense->commentaire = "Ue deja valide l'année d'avant";
                                //$dispense->endette ?
                                //Verifie qu'on prends bien la bonne ue
                                $dispense->id_ue = $tmp_ue_id;
                                //Insertion de la dispense
                                $dispense_factory->insert($dispense);
                            }
                        }
                    }
                    
                }else if ($i==$countADM){
                    //Si il est admis
                    //Creations des futur entree de la bd
                    $etudiant_semestre = jDao::createRecord('etudiants~etudiants_semestre');                    
                    $etudiant_semestre->num_etudiant = $etu->num_etudiant;
                    //Passage a l'année suivante
                    $idform = $factoryformation->getLastFormationByCode(utf8_encode($etu->formation));
                    foreach($idform as $idformee)
                        $liste_semestre = $factorysemestre->getByFormation($idformee->id_formation);
                    //Creation et insertion dans la table etudiant_semestre
                    foreach($liste_semestre as $semestre){
                        $etudiant_semestre->id_semestre = $semestre->id_semestre;
                        $etudiant_semestre->statut = "ENC";
                        if(!customSQL::etudiantSemestreExisteDeja($etu->num_etudiant,$semestre->id_semestre)){
                            $factoryetu_semestre->insert($etudiant_semestre);
                        }
                    }
                    
                }
                
            }
                $nb++;
        }

        Logger::log('import_apogee', $realname,$nb);

        jMessage::add("L'importation a reussie !");

        $rep->action = 'etudiants~import:index';
        return $rep;
    }
    

    
    
}

