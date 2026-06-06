<?php
// Visit.php
include_once '../config/config.php';

class Visit {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Record a visit check-in
    public function createVisit($visitor_id, $user_id, $location_id, $purpose) {
        $stmt = $this->pdo->prepare("INSERT INTO visits (visitor_id, user_id, check_in, location_id, purpose) 
                                     VALUES (:visitor_id, :user_id, NOW(), :location_id, :purpose)");
        $stmt->execute([
            'visitor_id' => $visitor_id,
            'user_id' => $user_id,
            'location_id' => $location_id,
            'purpose' => $purpose
        ]);
    }

    // Record a visit check-out
    public function updateVisit($visit_id) {
        $stmt = $this->pdo->prepare("UPDATE visits SET check_out = NOW(), status = 'checked-out' WHERE visit_id = :visit_id");
        $stmt->execute(['visit_id' => $visit_id]);
    }
}
?>
