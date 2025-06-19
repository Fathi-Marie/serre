<?php

class Model {
    private static $instance = null;
    private $dbLocal;
    private $dbRemote;

    private function __construct() {
        // Connexion locale
        $this->dbLocal = new PDO("mysql:host=localhost;dbname=eclosia", 'root', '');
        $this->dbLocal->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbLocal->query("SET NAMES 'utf8'");

        // Connexion distante
        $remoteHost = '185.216.26.53';
        $remoteDB = 'app_g3';
        $remoteUser = 'g3';
        $remotePass = 'azertyg3';

        try {
            $this->dbRemote = new PDO("mysql:host=$remoteHost;dbname=$remoteDB", $remoteUser, $remotePass);
            $this->dbRemote->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbRemote->query("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            die("Erreur de connexion à la base distante : " . $e->getMessage());
        }
    }

    public static function getModel() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDBLocal() {
        return $this->dbLocal;
    }

    public function getDBRemote() {
        return $this->dbRemote;
    }

    public function insertPersonne($nom, $prenom, $email, $actif, $telephone, $mdp) {
        try {
            $hashedMdp = password_hash($mdp, PASSWORD_DEFAULT);

            // Insertion de la personne
            $sql = "INSERT INTO Personne (nom, prénom, email, telephone, mdp) 
                VALUES (:nom, :prenom, :email, :telephone, :mdp)";
            $stmt = $this->dbLocal->prepare($sql);
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'mdp' => $hashedMdp
            ]);

            // Récupération de l'ID de la personne insérée
            $idPersonne = $this->dbLocal->lastInsertId();

            // Attribution du rôle "Visiteur" (id = 2)
            $sqlRole = "INSERT INTO Personne_Role (id_personne, id_role) 
                    VALUES (:id_personne, :id_role)";
            $stmtRole = $this->dbLocal->prepare($sqlRole);
            $stmtRole->execute([
                'id_personne' => $idPersonne,
                'id_role' => 2
            ]);

            return true;
        } catch (PDOException $e) {
            echo "Erreur db : " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }


    public function email_exist($email) {
        try {
            $stmt = $this->dbLocal->prepare("SELECT id FROM Personne WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? $user['id'] : false;
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    public function getActiveUsersWithRoles() {
        $stmt = $this->dbLocal->prepare("SELECT * FROM Personne WHERE etat = 'inactif'");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as &$user) {
            $roles = $this->getUserRoles($user['id']);
            $user['roles'] = array_column($roles, 'nom');
        }
        return $users;
    }

    public function personneConnexion($email) {
        $sql = "SELECT p.*, r.nom AS role
                FROM Personne p
                JOIN Personne_Role pr ON p.id = pr.id_personne
                JOIN Role r ON pr.id_role = r.id
                WHERE p.email = :email";

        $stmt = $this->dbLocal->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function doublon($email, $telephone) {
        $stmt = $this->dbLocal->prepare("SELECT * FROM Personne WHERE email = :email OR telephone = :telephone");
        $stmt->execute([":email" => $email, ":telephone" => $telephone]);
        return $stmt->rowCount() > 0;
    }

    public function getIdPersonne($email) {
        $stmt = $this->dbLocal->prepare("SELECT id FROM Personne WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn();
    }

    public function selectAllFromTable($table) {
        // Tables gérées en local
        $localTables = ['Personne', 'Role'];

        // Choisir la bonne connexion selon la table
        $db = in_array($table, $localTables) ? $this->dbLocal : $this->dbRemote;

        // Préparer et exécuter la requête
        $stmt = $db->prepare("SELECT * FROM `$table`");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUserRoles($userId) {
        $sql = "SELECT Role.nom FROM Personne_Role
                JOIN Role ON Personne_Role.id_role = Role.id
                WHERE Personne_Role.id_personne = :userId";
        $stmt = $this->dbLocal->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function disableUser($userId, $etat) {
        $stmt = $this->dbLocal->prepare("UPDATE Personne SET etat = :etat WHERE id = :userId");
        return $stmt->execute(['etat' => $etat, 'userId' => $userId]);
    }

    public function assignRole($userId, $roleId) {
        $existingRoles = $this->getUserRoles($userId);
        if (count($existingRoles) >= 1) {
            $sql = "UPDATE Personne_Role SET id_role = :roleId WHERE id_personne = :userId";
            $stmt = $this->dbLocal->prepare($sql);
            return $stmt->execute(['userId' => $userId, 'roleId' => $roleId[0]]);
        } else {
            if (!$this->hasRole($userId, $roleId)) {
                $sql = "INSERT INTO Personne_Role (id_personne, id_role) VALUES (:userId, :roleId)";
                $stmt = $this->dbLocal->prepare($sql);
                return $stmt->execute(['userId' => $userId, 'roleId' => $roleId[0]]);
            }
        }
        return false;
    }

    public function getUserById($id) {
        $stmt = $this->dbLocal->prepare("SELECT * FROM Personne WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserPassword($id, $hashedPassword) {
        $sql = "UPDATE Personne SET mdp = :mdp WHERE id = :id";
        $stmt = $this->dbLocal->prepare($sql);
        return $stmt->execute(['mdp' => $hashedPassword, 'id' => $id]);
    }

    public function getHistoricalDataByType($type_sensor) {
        $sql = "SELECT m.valeur, m.date_heure
            FROM mesures m
            JOIN capteurs c ON m.capteur_id = c.id
            WHERE c.nom = :type_sensor
            ORDER BY m.date_heure ASC";
        $stmt = $this->dbRemote->prepare($sql);
        $stmt->execute(['type_sensor' => $type_sensor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Récupère un tableau associatif avec 'valeur' et 'date_heure'
    }





    public function getAllActionneursWithCurrentState() {
        $sql = "
                SELECT a.id AS id_actuator, a.nom AS name, ea.etat AS state, ea.date_heure
        FROM actionneurs a
        LEFT JOIN (
            SELECT ea1.actionneur_id, ea1.etat, ea1.date_heure
            FROM etats_actionneurs ea1
            INNER JOIN (
                SELECT actionneur_id, MAX(date_heure) AS max_date
                FROM etats_actionneurs
                GROUP BY actionneur_id
            ) latest ON ea1.actionneur_id = latest.actionneur_id AND ea1.date_heure = latest.max_date
        ) ea ON a.id = ea.actionneur_id

    ";

        $stmt = $this->dbRemote->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getActionneursState() {
        $sql = "SELECT a.id AS id_actuator, a.nom AS name, ea.etat AS state
            FROM actionneurs a
            JOIN (
                SELECT ea1.actionneur_id, ea1.etat
                FROM etats_actionneurs ea1
                INNER JOIN (
                    SELECT actionneur_id, MAX(date_heure) AS max_date
                    FROM etats_actionneurs
                    GROUP BY actionneur_id
                ) latest ON ea1.actionneur_id = latest.actionneur_id AND ea1.date_heure = latest.max_date
            ) ea ON a.id = ea.actionneur_id";

        $stmt = $this->dbRemote->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addCapteur($type, $name, $unit) {
        $stmt = $this->dbRemote->prepare("INSERT INTO capteurs (nom, unite, is_actif) VALUES (?, ?, ?)");
        return $stmt->execute([$type, $name, $unit]);
    }

    public function deleteCapteur($id_sensor) {
        $stmt = $this->dbRemote->prepare("DELETE FROM capteurs WHERE id = ?");
        return $stmt->execute([$id_sensor]);
    }

    public function addActuator($nom, $etat) {
        $sql1 = "INSERT INTO actionneurs (nom) VALUES (?)";
        $stmt1 = $this->dbRemote->prepare($sql1);
        $result1 = $stmt1->execute([$nom]);
        if (!$result1) {
            return false;
        }
        $actionneurId = $this->dbRemote->lastInsertId();

        $sql2 = "INSERT INTO etats_actionneurs (actionneur_id, date_heure, etat) VALUES (?, NOW(), ?)";
        $stmt2 = $this->dbRemote->prepare($sql2);
        $result2 = $stmt2->execute([$actionneurId, $etat]);

        return $result2;
    }


    public function deleteActuator($id) {
        $sql = "DELETE FROM actionneurs WHERE id = ?";
        $stmt = $this->dbRemote->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getDataBySensorId($id_sensor) {
        $stmt = $this->dbRemote->prepare("SELECT valeur, date_heure FROM mesures WHERE capteur_id = ? ORDER BY date_heure ASC");
        $stmt->execute([$id_sensor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCapteursWithLimites() {
        $sql = "SELECT c.*, l.lim_min, l.lim_max 
            FROM capteurs c
            LEFT JOIN limites l ON c.id = l.id_capteur";
        $stmt = $this->dbRemote->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateLimites($id_sensor, $lim_min, $lim_max) {
        $check = $this->dbRemote->prepare("SELECT COUNT(*) FROM limites WHERE id_capteur = :id");
        $check->execute(['id' => $id_sensor]);
        $exists = $check->fetchColumn();

        if ($exists) {
            $stmt = $this->dbRemote->prepare("UPDATE limites SET lim_min = :min, lim_max = :max WHERE id_capteur = :id");
        } else {
            $stmt = $this->dbRemote->prepare("INSERT INTO limites (id_capteur, lim_min, lim_max) VALUES (:id, :min, :max)");
        }

        return $stmt->execute([
            'id' => $id_sensor,
            'min' => $lim_min,
            'max' => $lim_max
        ]);
    }

    public function getGraphDataById($id) {
        // Récupère la limite max
        $sqlLimite = "SELECT lim_max FROM limite WHERE id_capteur = :id";
        $stmtLimite = $this->dbRemote->prepare($sqlLimite);
        $stmtLimite->execute(['id' => $id]);
        $limite = $stmtLimite->fetch(PDO::FETCH_ASSOC);

        $lim_max = $limite['lim_max'] ?? null;

        // Récupère les mesures (valeurs + dates)
        $sqlMesures = "SELECT valeur, date_heure FROM mesures WHERE capteur_id = :id ORDER BY date_heure ASC";
        $stmtMesures = $this->dbRemote->prepare($sqlMesures);
        $stmtMesures->execute(['id' => $id]);
        $mesures = $stmtMesures->fetchAll(PDO::FETCH_ASSOC);

        // Prépare les labels (dates) et les values
        $labels = [];
        $values = [];

        foreach ($mesures as $mesure) {
            $labels[] = $mesure['date_heure'];
            $values[] = (float)$mesure['valeur'];
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'lim_max' => $lim_max
        ];
    }

    public function getSensorLimitById($id) {
        $sql = "SELECT lim_min, lim_max FROM limites WHERE id_capteur = :id_capteur";
        $stmt = $this->dbRemote->prepare($sql);
        $stmt->execute(['id_capteur' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMesuresByCapteurId($id) {
        $sql = "SELECT valeur, date_heure FROM mesures WHERE capteur_id = :id ORDER BY date_heure ASC";
        $stmt = $this->dbRemote->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserRole($userId, $newRoleName) {
        $stmt = $this->dbLocal->prepare("SELECT id FROM Role WHERE nom = :nom");
        $stmt->execute(['nom' => $newRoleName]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$role) return false;

        $roleId = $role['id'];

        try {
            $this->dbLocal->beginTransaction();

            $stmtDel = $this->dbLocal->prepare("DELETE FROM Personne_Role WHERE id_personne = :userId");
            $stmtDel->execute(['userId' => $userId]);

            $stmtIns = $this->dbLocal->prepare("INSERT INTO Personne_Role (id_personne, id_role) VALUES (:userId, :roleId)");
            $stmtIns->execute(['userId' => $userId, 'roleId' => $roleId]);

            $this->dbLocal->commit();
            return true;
        } catch (Exception $e) {
            $this->dbLocal->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function getLastValueByCapteur($capteurId) {
        try {
            $sql = "SELECT valeur 
                FROM mesures 
                WHERE capteur_id = :capteur_id 
                ORDER BY date_heure DESC 
                LIMIT 1";
            $stmt = $this->dbRemote->prepare($sql);
            $stmt->execute(['capteur_id' => $capteurId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
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

        $mesure = json_decode($response, true);

        if ($mesure === null) {
            file_put_contents($logFile, "Erreur JSON : " . json_last_error_msg() . "\n", FILE_APPEND);
            return null;
        }

        if (!isset($mesure['main']['temp'])) {
            file_put_contents($logFile, "Clé 'main.temp' non trouvée dans la réponse\n", FILE_APPEND);
            return null;
        }

        file_put_contents($logFile, "Température récupérée : " . $mesure['main']['temp'] . "\n", FILE_APPEND);

        return floatval($mesure['main']['temp']);
    }

}
?>