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
        $sql = "SELECT 
                p.*, 
                r.nom AS role
            FROM 
                Personne p
            JOIN 
                Personne_Role pr ON p.id = pr.id_personne
            JOIN 
                Role r ON pr.id_role = r.id
            WHERE 
                p.email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function selectAllFromTable($table) {
        /*
        * Sélectionner toutes les entrées de la table spécifiée
        * @param string $table - Nom de la table
        * @return array - Tableau contenant toutes les entrées de la table
        */
        if ($table == 'Personne') {
            $stmt = $this->db->query("SELECT * FROM " . $table . " WHERE etat='inactif'");
        } else {
            $stmt = $this->db->query("SELECT * FROM " . $table);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserRoles($userId) {
        $sql = "SELECT Role.nom FROM Personne_Role
                JOIN Role ON Personne_Role.id_role = Role.id
                WHERE Personne_Role.id_personne = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function disableUser($userId, $etat) {
        /*
        * Désactiver un utilisateur
        * @param int $userId - ID de l'utilisateur
        */
        $stmt = $this->db->prepare("UPDATE Personne SET etat = :etat WHERE id = :userId");
        return $stmt->execute([
            'etat' => $etat,
            'userId' => $userId
        ]);
    }


    public function assignRole($userId, $roleId) {
        // Vérifier si l'utilisateur a déjà des rôles
        $existingRoles = $this->getUserRoles($userId);

        if (count($existingRoles) >= 1) {
            // Mettre à jour le rôle existant
            $sql = "UPDATE Personne_Role SET id_role = :roleId WHERE id_personne = :userId";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['userId' => $userId, 'roleId' => $roleId[0]]);
        } else {
            // Vérifier si le rôle existe déjà
            if (!$this->hasRole($userId, $roleId)) {
                // Ajouter le nouveau rôle
                $sql = "INSERT INTO Personne_Role (id_personne, id_role) VALUES (:userId, :roleId)";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(['userId' => $userId, 'roleId' => $roleId[0]]);
            }
        }
        return false;
    }

    public function getLastValue($id_sensor) {
        $sql = "SELECT value, date FROM data WHERE id_sensor = :id_sensor ORDER BY date DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_sensor' => $id_sensor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getHistoricalDataByType($type_sensor) {
        $sql = "SELECT d.value, d.date FROM data d
            JOIN capteurs c ON d.id_sensor = c.id_sensor
            WHERE c.type = :type_sensor
            ORDER BY d.date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['type_sensor' => $type_sensor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActuatorsState() {
        $sql = "SELECT id_actuator, name, state FROM actuators";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Personne WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateUserPassword($id, $hashedPassword) {
        $sql = "UPDATE Personne SET mdp = :mdp WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':mdp' => $hashedPassword,
            ':id' => $id
        ]);
    }

    public function addCapteur($type, $name, $unit) {
        $stmt = $this->db->prepare("INSERT INTO capteurs (type, name, unit) VALUES (?, ?, ?)");
        return $stmt->execute([$type, $name, $unit]);
    }

    public function deleteCapteur($id_sensor) {
        $stmt = $this->db->prepare("DELETE FROM capteurs WHERE id_sensor = ?");
        return $stmt->execute([$id_sensor]);
    }
    public function addActuator($type, $name, $state) {
        $sql = "INSERT INTO actuators (type, name, state) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$type, $name, $state]);
    }

    public function deleteActuator($id) {
        $sql = "DELETE FROM actuators WHERE id_actuator = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getDataBySensorId($id_sensor) {
        $stmt = $this->db->prepare("SELECT value, date FROM data WHERE id_sensor = ? ORDER BY date ASC");
        $stmt->execute([$id_sensor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCapteursWithLimites() {
        $sql = "SELECT c.*, l.lim_min, l.lim_max 
            FROM capteurs c
            LEFT JOIN limites l ON c.id_sensor = l.id_sensor";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateLimites($id_sensor, $lim_min, $lim_max) {
        // Vérifie si une entrée existe déjà
        $check = $this->db->prepare("SELECT COUNT(*) FROM limites WHERE id_sensor = :id");
        $check->execute(['id' => $id_sensor]);
        $exists = $check->fetchColumn();

        if ($exists) {
            $stmt = $this->db->prepare("UPDATE limites SET lim_min = :min, lim_max = :max WHERE id_sensor = :id");
        } else {
            $stmt = $this->db->prepare("INSERT INTO limites (id_sensor, lim_min, lim_max) VALUES (:id, :min, :max)");
        }

        $stmt->execute([
            'id' => $id_sensor,
            'min' => $lim_min,
            'max' => $lim_max
        ]);
    }
    public function getSensorLimitById($id_sensor) {
        $req = $this->db->prepare("SELECT lim_max FROM limites WHERE id_sensor = ?");
        $req->execute([$id_sensor]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserRole($userId, $newRoleName) {
        // 1. Récupérer l'ID du rôle depuis son nom
        $stmt = $this->db->prepare("SELECT id FROM Role WHERE nom = :nom");
        $stmt->execute(['nom' => $newRoleName]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$role) {
            return false; // rôle non trouvé
        }

        $roleId = $role['id'];

        try {
            $this->db->beginTransaction();

            // 2. Supprimer les rôles actuels de l'utilisateur
            $stmtDel = $this->db->prepare("DELETE FROM Personne_Role WHERE id_personne = :userId");
            $stmtDel->execute(['userId' => $userId]);

            // 3. Insérer le nouveau rôle
            $stmtIns = $this->db->prepare("INSERT INTO Personne_Role (id_personne, id_role) VALUES (:userId, :roleId)");
            $stmtIns->execute([
                'userId' => $userId,
                'roleId' => $roleId
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    public function envoyerMessage($id_expediteur, $id_destinataire, $contenu) {
        $stmt = $this->db->prepare("INSERT INTO Messagerie (id_personne, id_personne_destinataire, message, creer_a) VALUES (:exp, :dest, :msg, NOW())");
        return $stmt->execute([
            "exp" => $id_expediteur,
            "dest" => $id_destinataire,
            "msg" => $contenu
        ]);
    }
    public function getAdmins() {
        $sql = "SELECT p.id FROM Personne p
            JOIN Personne_Role pr ON p.id = pr.id_personne
            JOIN Role r ON pr.id_role = r.id
            WHERE r.nom = 'Admin'";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCapteursAvecDepassement() {
        $sql = "SELECT * FROM Capteur WHERE valeur > seuil_max";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getDerniereTemperatureInterieure() {

        $logFile = __DIR__ . '/../logs/logs.txt';
        file_put_contents($logFile, "Début de getDerniereTemperatureInterieure()\n", FILE_APPEND);

        $apiKey = '93cce3bd8a6c4fb25548a56df17d5962';
        $city = 'Paris,fr';

        $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

        file_put_contents($logFile, "URL appelée : $url\n", FILE_APPEND);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            file_put_contents($logFile, "Erreur cURL : " . curl_error($curl) . "\n", FILE_APPEND);
            curl_close($curl);
            return null;
        }

        curl_close($curl);

        file_put_contents($logFile, "Réponse API brute : " . $response . "\n", FILE_APPEND);

        $data = json_decode($response, true);

        if ($data === null) {
            file_put_contents($logFile, "Erreur JSON : " . json_last_error_msg() . "\n", FILE_APPEND);
            return null;
        }

        if (!isset($data['main']['temp'])) {
            file_put_contents($logFile, "Clé 'main.temp' non trouvée dans la réponse\n", FILE_APPEND);
            return null;
        }

        file_put_contents($logFile, "Température récupérée : " . $data['main']['temp'] . "\n", FILE_APPEND);

        return floatval($data['main']['temp']);
    }




}
?>