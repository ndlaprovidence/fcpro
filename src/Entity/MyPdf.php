<?php


namespace App\Entity;


class MyPdf extends \TCPDF {

private $formation;

public function setFormation($formation){
    $this->formation = $formation;
}

    //Page header
    public function Header() {
        //Colonne de gauche

        $textg = '
        <style> .blue { color: rgb(0, 63,144); } .link { color: rgb(100,0,0); }</style>
        <br>
        <p class="blue">
<b>Tarifs :</b></p>
'. $this->formation->getPrice() .' € net.
        <p class="blue">
<b>Modalités :</b>
        </p>
'. $this->formation->getFormat() .'
        <p class="blue">
<b>Accessibilité aux personnes handicapées :</b>
</p><p>
<b>Accès au lieu de formation</b> :<br>
Les locaux sont accessibles aux
personnes en situation de handicap,
merci de nous contacter.<br>
<br>
<b>Accès à la prestation</b> :<br>
Une adaptation de la formation est
possible pour les personnes en
situation de handicap, merci de nous
contacter.
        </p>
        <p class="blue">
<b>Contact :</b>
        </p><p>
<b>Alexia HEBERT, responsable de FCPRO</b><br>
Service de Formation Professionnelle<br>
Continue de l’OGEC Notre Dame de la Providence<br>
<br>
9, rue chanoine Bérenger BP 340, 50300 AVRANCHES.<br>
Tel 02 33 58 02 22<br>
mail : <span class="link">fcpro@ndlpavranches.fr</span><br>
<br>
N° activité 25500040250<br>
OF certifié QUALIOPI pour les actions de formations<br>
<br>
Site Web : <span class="link">https://ndlpavranches.fr/fc-pro/</span>
        </p>';

        $this->SetFont('helvetica', '', 10);
        $this->SetFillColor(225,225,230);
        $this->setY(45);
        $this->writeHTMLCell(65, 230, "", "", $textg, 0, 0, 1, true, '', true);

    }

}
