<?php
/**
 * User Migration Script for Unified Authentication
 * 
 * This script migrates existing users and creates mappings between
 * Lupopedia users and Crafty Syntax operators based on email matching.
 * 
 * HERITAGE-SAFE: This script is non-destructive and only creates
 * additive mappings without modifying existing data.
 */

require_once __DIR__ . '/../config/database.php';

class UserMappingMigrator
{
    private $db;
    private $dryRun;
    
    public function __construct($dryRun = true)
    {
        $this->dryRun = $dryRun;
        $this->db = $this->connectDatabase();
    }
    
    /**
     * Run the migration process
     */
    public function migrate()
    {
        echo "=== User Mapping Migration ===\n";
        echo "Mode: " . ($this->dryRun ? "DRY RUN" : "LIVE") . "\n\n";
        
        try {
            // Step 1: Get all Lupopedia users
            $lupoUsers = $this->getLupopediaUsers();
            echo "Found " . count($lupoUsers) . " Lupopedia users\n";
            
            // Step 2: Get all Crafty Syntax operators
            $craftyOperators = $this->getCraftyOperators();
            echo "Found " . count($craftyOperators) . " Crafty Syntax operators\n";
            
            // Step 3: Find potential matches by email
            $matches = $this->findEmailMatches($lupoUsers, $craftyOperators);
            echo "Found " . count($matches) . " potential email matches\n\n";
            
            // Step 4: Check existing mappings
            $existingMappings = $this->getExistingMappings();
            echo "Existing mappings: " . count($existingMappings) . "\n\n";
            
            // Step 5: Create new mappings for unmatched matches
            $newMappings = $this->createMappings($matches, $existingMappings);
            
            // Step 6: Update users table with crafty_operator_id
            $this->updateUsersTable($newMappings);
            
            // Step 7: Generate report
            $this->generateReport($newMappings);
            
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
        }
    }
    
    /**
     * Connect to database
     */
    private function connectDatabase()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get all Lupopedia users
     */
    private function getLupopediaUsers()
    {
        $stmt = $this->db->query("SELECT id, email, name, username FROM users ORDER BY email");
        return $stmt->fetchAll();
    }
    
    /**
     * Get all Crafty Syntax operators
     */
    private function getCraftyOperators()
    {
        $stmt = $this->db->query("SELECT operatorid, email, operatorname FROM livehelp_operators ORDER BY email");
        return $stmt->fetchAll();
    }
    
    /**
     * Find email matches between users and operators
     */
    private function findEmailMatches($lupoUsers, $craftyOperators)
    {
        $matches = [];
        
        foreach ($lupoUsers as $lupoUser) {
            $lupoEmail = strtolower(trim($lupoUser['email']));
            
            foreach ($craftyOperators as $operator) {
                $craftyEmail = strtolower(trim($operator['email']));
                
                if ($lupoEmail === $craftyEmail) {
                    $matches[] = [
                        'lupo_user_id' => $lupoUser['id'],
                        'crafty_operator_id' => $operator['operatorid'],
                        'lupo_email' => $lupoUser['email'],
                        'crafty_email' => $operator['email'],
                        'lupo_name' => $lupoUser['name'] ?? $lupoUser['username'],
                        'crafty_name' => $operator['operatorname'],
                        'match_type' => 'email_exact'
                    ];
                    break; // Stop after first match
                }
            }
        }
        
        return $matches;
    }
    
    /**
     * Get existing mappings
     */
    private function getExistingMappings()
    {
        $stmt = $this->db->query("SELECT lupo_user_id, crafty_operator_id FROM lupo_crafty_user_mapping");
        $existing = [];
        
        while ($row = $stmt->fetch()) {
            $key = $row['lupo_user_id'] . '_' . $row['crafty_operator_id'];
            $existing[$key] = true;
        }
        
        return $existing;
    }
    
    /**
     * Create new mappings for unmatched matches
     */
    private function createMappings($matches, $existingMappings)
    {
        $newMappings = [];
        
        foreach ($matches as $match) {
            $key = $match['lupo_user_id'] . '_' . $match['crafty_operator_id'];
            
            if (!isset($existingMappings[$key])) {
                $newMappings[] = $match;
            }
        }
        
        if (!$this->dryRun && !empty($newMappings)) {
            $stmt = $this->db->prepare("
                INSERT INTO lupo_crafty_user_mapping 
                (lupo_user_id, crafty_operator_id, mapping_type, notes, created_at, updated_at) 
                VALUES (?, ?, 'auto', 'Auto-generated by migration script', NOW(), NOW())
            ");
            
            foreach ($newMappings as $mapping) {
                $stmt->execute([
                    $mapping['lupo_user_id'],
                    $mapping['crafty_operator_id']
                ]);
            }
        }
        
        return $newMappings;
    }
    
    /**
     * Update users table with crafty_operator_id
     */
    private function updateUsersTable($newMappings)
    {
        if (empty($newMappings)) {
            return;
        }
        
        if (!$this->dryRun) {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET crafty_operator_id = ? 
                WHERE id = ?
            ");
            
            foreach ($newMappings as $mapping) {
                $stmt->execute([
                    $mapping['crafty_operator_id'],
                    $mapping['lupo_user_id']
                ]);
            }
        }
    }
    
    /**
     * Generate migration report
     */
    private function generateReport($newMappings)
    {
        echo "\n=== MIGRATION REPORT ===\n";
        echo "New mappings created: " . count($newMappings) . "\n\n";
        
        if (!empty($newMappings)) {
            echo "New Mappings:\n";
            echo str_repeat("-", 80) . "\n";
            printf("%-10s %-30s %-30s %-15s\n", "Lupo ID", "Lupopedia User", "Crafty Operator", "Match Type");
            echo str_repeat("-", 80) . "\n";
            
            foreach ($newMappings as $mapping) {
                printf("%-10d %-30s %-30s %-15s\n", 
                    $mapping['lupo_user_id'],
                    substr($mapping['lupo_email'], 0, 30),
                    substr($mapping['crafty_email'], 0, 30),
                    $mapping['match_type']
                );
            }
        }
        
        echo "\n=== NEXT STEPS ===\n";
        echo "1. Review the mappings above\n";
        echo "2. Run with --execute flag to apply changes\n";
        echo "3. Verify mappings in admin console\n";
        echo "4. Test unified authentication\n";
    }
}

// Command line interface
if (php_sapi_name() === 'cli') {
    $options = getopt('', ['execute', 'dry-run', 'help']);
    
    if (isset($options['help'])) {
        echo "User Mapping Migration Script\n\n";
        echo "Usage: php migrate_user_mappings.php [options]\n\n";
        echo "Options:\n";
        echo "  --execute    Execute the migration (default: dry run)\n";
        echo "  --dry-run    Show what would be done without executing\n";
        echo "  --help        Show this help message\n\n";
        echo "Examples:\n";
        echo "  php migrate_user_mappings.php --dry-run\n";
        echo "  php migrate_user_mappings.php --execute\n";
        exit(0);
    }
    
    $dryRun = !isset($options['execute']);
    $migrator = new UserMappingMigrator($dryRun);
    $migrator->migrate();
} else {
    echo "This script must be run from the command line.\n";
    echo "Use --help for usage information.\n";
    exit(1);
}
?>
