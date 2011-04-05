<?php


class testUtilsFormule extends UnitTestCase {
 
    function testParseFormuleUe() {
        jClasses::inc('utils~Formule');
        
        //Test1
        $this->assertIdentical(Formule::parseFormuleUe(78), null);
       
       
        //Test2
        $formule = '2PA1 + 3 PA2 + EvC';
        $resultat_attendu = array(
            array('2PA1', '3 PA2', ' EvC'),
            array('2', '3', ''),
            array('PA1', 'PA2', 'EvC')
        );
        $this->assertIdentical(Formule::parseFormuleUe($formule), $resultat_attendu);
    
        //Test3
        $formule = ' PA + 2*EvC + 1* TP';
        $resultat_attendu = array(
            array(' PA', '2*EvC', '1* TP'),
            array('', '2', '1'),
            array('PA', 'EvC', 'TP')
        );
        $this->assertIdentical(Formule::parseFormuleUe($formule), $resultat_attendu);

    
    }
}