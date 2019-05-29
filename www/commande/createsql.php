<?php
require_once '/var/www/vendor/autoload.php';

// Connexion à une bdd
$pdo = NEW PDO('mysql:host=blog.mysql;dbname=blog;','userblog','blogpwd');

// Création tables :

$pdo->exec("CREATE TABLE post (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    content TEXT(650000) NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id)
)");

$pdo->exec("CREATE TABLE category (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)");
$pdo->exec("CREATE TABLE user (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)");
$pdo->exec("CREATE TABLE post_category (
    post_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, category_id),
    CONSTRAINT fk_post
        FOREIGN KEY (post_id)
        REFERENCES post (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
    CONSTRAINT fk_category
        FOREIGN KEY (category_id)
        REFERENCES category (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)");

// Vidage tables :

// Executions dans la bdd :
// Désactive la vérification de clés étrangères
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
// Vide la table post_category
$pdo->exec('TRUNCATE TABLE post_categoty');
// Vide la table post
$pdo->exec('TRUNCATE TABLE post');
// Vide la table category
$pdo->exec('TRUNCATE TABLE category');
// Vide la table user
$pdo->exec('TRUNCATE TABLE user');
// Active la vérification de clés étrangères
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

// Créer une instance de la librairie faker
$faker = Faker\Factory::create('fr-FR');

$posts = [];
$categories = [];

// Boucle déterminant le nombre d'articles
for ($i=0; $i < 50; $i++){
    $pdo->exec("INSERT INTO post SET
        name='{$faker->sentence()}',                        
        slug='{$faker->slug}',                              
        content='{$faker->paragraph(rand(3,15), true)}',    
        created_at='{$faker->date} {$faker->time}'");       
    $posts[] = $pdo->lastInsertId();
}

for ($i=0; $i <5; $i++){
    // Insert dans la table category
    $pdo->exec("INSERT INTO category SET
    name='{$faker->sentence($nbWords = 3, $variableNbWords = false)}',/* La colonne Name contiendra une ligne d'une phrase de 3 mots */
    slug='{$faker->slug}'");
    $categories[]=$pdo->lastInsertId();
    /* La colonne Slug contiendra une ligne "slug" */
}


foreach($posts as $post){
    $randomCategories = $faker->randomElements($categories,  2);
    foreach($randomCategories as $category){
    $pdo->exec("INSERT INTO post_category SET
        post_id={$post},
        category_id={$category}");
    }
}


    // On déclare la variable $password qui prendra un mdp différent à chaque itération
    $password = password_hash('admin', PASSWORD_BCRYPT);
    // Insert dans la table user
    $pdo->exec("INSERT INTO user SET
    username='admin',
    password='{$password}'");
