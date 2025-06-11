<?php
//require_once 'Utils/config.php';

class Model {
    private $db;
    private static $instance = null;

    private function __construct() {
        /*
        * Connexion à la base de données avec les informations de connexion définies dans config.php
        */
        //global $servername, $username, $password, $dbname;
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'Aidappart';
        $this->db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->query("SET NAMES 'utf8'");
        /*
        echo DB_HOST;
        echo DB_NAME;
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        echo $dsn;
        $this->db = new db($dsn, DB_USER, DB_PASS);
        $this->db->setAttribute(db::ATTR_ERRMODE, db::ERRMODE_EXCEPTION);*/
    }

    public static function getModel() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }



    public function insertPersonne($nom, $prenom, $email, $actif, $telephone, $mdp) {
        /*
        * Insérer une nouvelle personne dans la table Personne
        * @param string $nom - Nom de la personne
        * @param string $prenom - Prénom de la personne
        * @param string $email - Email de la personne
        * @param bool $actif - Statut actif de la personne
        * @param string $telephone - Téléphone de la personne
        * @param string $mdp - Mot de passe de la personne
        * @return bool - Retourne true en cas de succès, false en cas d'échec
        */
        try {
            $hashedMdp = password_hash($mdp, PASSWORD_DEFAULT);
            $sql = "INSERT INTO Personne (nom, prénom, email, actif, telephone, mdp) VALUES (:nom, :prenom, :email, :actif, :telephone, :mdp)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'actif' => $actif,
                'telephone' => $telephone,
                'mdp' => $hashedMdp
            ]);
        } catch (PDOException $e) {
            echo "Erreur db : " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }

    }

    public function selectDistinctFromTable($table, $column, $order_by=null) {
        /*
        * Sélectionner des valeurs distinctes d'une colonne spécifiée dans une table spécifiée
        * @param string $table - Nom de la table
        * @param string $column - Nom de la colonne
        * @return array - Tableau contenant les valeurs distinctes de la colonne
        */
        if ($order_by) {
            $stmt = $this->db->query("SELECT DISTINCT $column FROM $table ORDER BY $order_by");
        } else {
            $stmt = $this->db->query("SELECT DISTINCT $column FROM $table");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectMinMaxFromTable($table, $column) {
        /*
        * Sélectionner les valeurs minimales et maximales d'une colonne spécifiée dans une table spécifiée
        * @param string $table - Nom de la table
        * @param string $column - Nom de la colonne
        * @return array - Tableau contenant les valeurs minimales et maximales de la colonne
        */
        $stmt = $this->db->query("SELECT MIN($column) as min, MAX($column) as max FROM $table");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function selectFieldsFromTable($table, $fields, $conditions = []) {
        /*
        * Sélectionner des champs spécifiques d'une table spécifiée avec des conditions optionnelles
        * @param string $table - Nom de la table
        * @param array $fields - Tableau des champs à sélectionner
        * @param array $conditions - Tableau des conditions (facultatif)
        * @return array - Tableau contenant les résultats de la sélection
        */
        $fieldsList = implode(", ", $fields);
        echo $fieldsList;
        $sql = "SELECT $fieldsList FROM $table";

        if (!empty($conditions)) {
            $conditionsList = [];
            foreach ($conditions as $key => $value) {
                $conditionsList[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $conditionsList);
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($conditions);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur db : " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    public function email_exist($email) {
        /*
        * Vérifie si un email existe dans la table Personne
        * @param string $email - L'email à vérifier
        * @return mixed - Retourne l'ID de l'utilisateur si l'email existe, sinon false
        */
        try {
            // Préparer la requête SQL
            $stmt = $this->db->prepare("SELECT id FROM Personne WHERE email = :email");

            // Lier le paramètre email
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            // Exécuter la requête
            $stmt->execute();

            // Récupérer le résultat
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si un utilisateur est trouvé, retourner son ID
            if ($user) {
                return $user['id'];
            }

            // Si l'email n'existe pas
            return false;

        } catch (PDOException $e) {
            // Gérer l'exception en cas d'erreur de la base de données
            echo "Erreur db : " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // Gérer l'exception générique
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    public function personneConnexion($email) {
        $stat = $this->db->prepare('SeLECT * FROM Personne WHERE email = :email');
        $stat-> execute(['email' => $email]);
        return $stat->fetch(PDO::FETCH_ASSOC);
    }

    public function doublon($email, $telephone) {
        $stmt = $this->db->prepare("SELECT * FROM Personne WHERE email = :email OR telephone = :telephone");
        $stmt->execute([":email" => $email, ":telephone"=> $telephone]);
        return $stmt->rowCount() > 0;
    }

    public function getdataById($table, $dataId) {
        /*
        * Récupérer les données d'une table spécifiée par l'ID
        * @param string $table - Nom de la table
        * @param int $dataId - ID de la donnée
        * @return array - Tableau contenant les données de la table
        */
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE id = :dataId");
        $stmt->execute(['dataId' => $dataId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function allUser(){
           $all = $this->db->prepare('SELECT nom, id FROM Personne');
           $all->execute();
           return $all->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdPersonne($email) {
        $stmt = $this->db->prepare("SELECT id FROM Personne WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn();
    }
}
?>