<?php
declare(strict_types=1);

use App\Core\Database;
use App\Models\AgencyModel;
use PHPUnit\Framework\TestCase;

final class AgencyModelTest extends TestCase
{
    public function testCreationModificationEtSuppressionDuneAgence(): void
    {
        // Refuse toute écriture si la base de test n'est pas configurée.
        self::assertTrue(defined('DB_NAME'), 'Le fichier de démarrage des tests manque.');
        self::assertSame('covoit_mvc_test', constant('DB_NAME'));

        $pdo = Database::getConnection();
        self::assertSame('covoit_mvc_test', $pdo->query('SELECT DATABASE()')->fetchColumn());

        $pdo->beginTransaction();

        try {
            $agencies = new AgencyModel();
            $name = 'Agence test ' . bin2hex(random_bytes(4));

            $id = $agencies->create($name);
            self::assertIsInt($id);
            self::assertSame($name, $agencies->getById($id)['name'] ?? null);

            $newName = $name . ' renommée';
            self::assertTrue($agencies->update($id, $newName));
            self::assertSame($newName, $agencies->getById($id)['name'] ?? null);

            self::assertTrue($agencies->delete($id));
            self::assertNull($agencies->getById($id));
        } finally {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
        }
    }
}