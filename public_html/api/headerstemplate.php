<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    header('Location: /logowanie.php');
}

if($_SESSION['group'] == 'admin') {

} else {
    return;
}

require($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR.'db.php');

// Pobierz dane z żądania
$dane = json_decode(file_get_contents('php://input'));

// Sprawdź czy `project_id` jest ustawione
if (!isset($dane->project_id)) {
    http_response_code(400); // Błędne żądanie
    echo json_encode(['error' => 'Brakujący project_id']);
    exit();
}

$project_id = $dane->project_id;
$headers = [
    'Nr budowlany',
    'Adres administracyjny',
    'Nr admin.',
    'Kontakt do klienta',
    'Data zgłoszenia przez klienta',
    'Zgłoszony opis usterki',
    'Uwagi inwestora'
];

// Zakładamy, że mamy już instancję PDO w zmiennej $dbh
try {
    // Rozpocznij transakcję
    $dbh->beginTransaction();

    // Przygotuj zapytanie SQL
    $stmt = $dbh->prepare("INSERT INTO `headers` (`project_id`, `header`) VALUES (:project_id, :header)");

    // Wstawiaj dane w pętli
    foreach ($headers as $header) {
        $stmt->execute([':project_id' => $project_id, ':header' => $header]);
    }

    // Zatwierdź transakcję
    $dbh->commit();
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    // W razie błędu cofnij transakcję
    $dbh->rollBack();
    http_response_code(500); // Wewnętrzny błąd serwera
    echo json_encode(['error' => $e->getMessage()]);
}
