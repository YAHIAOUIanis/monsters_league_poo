<?php
require __DIR__ . '/monster.php';

/**
 * récupérer les données stockées sur la base de données 
 */
function getMonstersObjet_BDD()
{    
    try{
        //dbname : database name
        $bdd = new PDO('mysql:host=localhost;dbname=monsters_phpoo;charset=utf8', 'root', 'newPass');
    }
    catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
    }
    $reponse = $bdd->query('SELECT * FROM monsters');
    $monstersAux = array();    
    foreach ($reponse->fetchAll() as $monster) {
        $monstersAux[] = new Monster($monster['name'],$monster['strength'],$monster['life'],$monster['type']);
    }
    return $monstersAux;
}

/**
 * initialiser l'âge du monster à 0
 * @param $name le nom de monster à initialiser son âge
 */
function resetLife($name)
{
    if(isset($_POST['modify'])){
        try {
            $bdo = new PDO('mysql:host=localhost;dbname=monsters_phpoo;charset=utf8', 'root', 'newPass');
            //préparer la requête
            $query=$bdo->prepare("UPDATE `monsters` SET `life`='0' where `name`=?");
            //exécuter la requête préparée
            $query->execute(array($name));    
        }
        catch(Exception $e ) {
            die('Erreur : ' . $e->getMessage());
        }    
    }     
}

/**
 * transforme le tableau de tableaux de monstre en un tableau d'objet monstre
 */
function getMonstersObjet()
{
    $monsters = getMonsters();
    $monstersAux = array();
    foreach ($monsters as $monster) {
        $monstersAux[] = new Monster($monster['name'],$monster['strength'],$monster['life'],$monster['type']);
    }
    return $monstersAux;
}

function getMonsters()
{
    return [
        [
            'name' => 'Domovoï',
            'strength' => 30,
            'life' => 300,
            'type' => 'water'
        ],
        [
            'name' => 'Wendigos',
            'strength' => 100,
            'life' => 450,
            'type' => 'earth'
        ],
        [
            'name' => 'Thunderbird',
            'strength' => 400,
            'life' => 500,
            'type' => 'air'
        ],
        [
            'name' => 'Sirrush',
            'strength' => 250,
            'life' => 1500,
            'type' => 'fire'
        ],
    ];
}

/**
 * Our complex fighting algorithm!
 *
 * @return array With keys winning_ship, losing_ship & used_jedi_powers
 */
function fight(object $firstMonster, object $secondMonster)
{
    $firstMonsterLife = $firstMonster->getLife();
    $secondMonsterLife = $secondMonster->getLife();

    while ($firstMonsterLife > 0 && $secondMonsterLife > 0) {
        $firstMonsterLife = $firstMonsterLife - $secondMonster->getStrength();
        $secondMonsterLife = $secondMonsterLife - $firstMonster->getStrength();
    }

    if ($firstMonsterLife <= 0 && $secondMonsterLife <= 0) {
        $winner = null;
        $looser = null;
    } elseif ($firstMonsterLife <= 0) {
        $winner = $secondMonster;
        $looser = $firstMonster;
    } else {
        $winner = $firstMonster;
        $looser = $secondMonster;
    }

    return array(
        'winner' => $winner,
        'looser' => $looser,
    );
}