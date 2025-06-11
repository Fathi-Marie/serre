<?php
use PHPUnit\Framework\TestCase;

require_once '/Utils/config.php';
require_once '/Models/Model.php';

class ModelTest extends TestCase {
    protected $model;

    protected function setUp(): void {
        $this->model = new model();
    }

    public function testSelectAllFromTable() {
        $result = $this->model->selectAllFromTable('Personne');
        $this->assertIsArray($result);
        $this->assertCount(20, $result);
    }

    public function testDeleteById() {
        $this->model->deleteById('Personne', 1);
        $result = $this->model->selectAllFromTable('Personne');
        $this->assertCount(19, $result);
    }

    public function testInsertPersonne() {
        $result = $this->model->insertPersonne('Test', 'User', 'test.user@example.com', 1, '0600000021', 'password21');
        $this->assertTrue($result);

        $personnes = $this->model->selectAllFromTable('Personne');
        $this->assertCount(21, $personnes);
    }

    public function testInsertLogement() {
        $result = $this->model->insertLogement('Appartement', 70, 1, 1500, 150, '2023-01-16', 1, true, true, true, 3, true, 'Appartement de test');
        $this->assertTrue($result);

        $logements = $this->model->selectAllFromTable('Logement');
        $this->assertCount(16, $logements);
    }

    public function testInsertAnnonce() {
        $result = $this->model->insertAnnonce(1, '2023-01-16', 1, true, '2023-09-01', 2, 'disponible', 'Annonce de test');
        $this->assertTrue($result);

        $annonces = $this->model->selectAllFromTable('Annonce');
        $this->assertCount(8, $annonces);
    }
}
?>