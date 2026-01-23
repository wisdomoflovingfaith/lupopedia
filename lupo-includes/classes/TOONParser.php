<?php

/**
 * TOON Parser - Textual Object-Oriented Notation
 * 
 * Parses TOON files to generate database schemas and migrations.
 * Human-readable format for database structure definition.
 * 
 * @package Lupopedia\Classes
 * @version 1.0.0
 * @author Captain Wolfie
 */

class TOONParser
{
    private $parsed_schema = [];
    
    /**
     * Parse TOON file content
     */
    public function parseFile($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception("TOON file not found: $filename");
        }
        
        $content = file_get_contents($filename);
        return $this->parseContent($content);
    }
    
    /**
     * Parse TOON content string
     */
    public function parseContent($content)
    {
        $lines = explode("\n", $content);
        $current_table = null;
        $current_section = null;
        
        foreach ($lines as $line_number => $line) {
            $line = trim($line);
            
            // Skip empty lines and comments
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            
            // Parse table definition
            if (strpos($line, 'table:') === 0) {
                $current_table = substr($line, 7);
                $this->parsed_schema[$current_table] = [
                    'name' => $current_table,
                    'columns' => [],
                    'indexes' => [],
                    'engine' => 'InnoDB',
                    'charset' => 'utf8mb4',
                    'collate' => 'utf8mb4_unicode_ci',
                    'comment' => ''
                ];
                $current_section = 'columns';
                continue;
            }
            
            // Parse section headers
            if (strpos($line, 'columns:') === 0) {
                $current_section = 'columns';
                continue;
            }
            
            if (strpos($line, 'indexes:') === 0) {
                $current_section = 'indexes';
                continue;
            }
            
            if (strpos($line, 'engine:') === 0) {
                $this->parsed_schema[$current_table]['engine'] = trim(substr($line, 8));
                continue;
            }
            
            if (strpos($line, 'charset:') === 0) {
                $this->parsed_schema[$current_table]['charset'] = trim(substr($line, 9));
                continue;
            }
            
            if (strpos($line, 'collate:') === 0) {
                $this->parsed_schema[$current_table]['collate'] = trim(substr($line, 9));
                continue;
            }
            
            if (strpos($line, 'comment:') === 0) {
                $this->parsed_schema[$current_table]['comment'] = trim(substr($line, 9));
                continue;
            }
            
            // Parse columns
            if ($current_section === 'columns' && $current_table) {
                $column_def = $this->parseColumn($line);
                if ($column_def) {
                    $this->parsed_schema[$current_table]['columns'][] = $column_def;
                }
            }
            
            // Parse indexes
            if ($current_section === 'indexes' && $current_table) {
                $index_def = $this->parseIndex($line);
                if ($index_def) {
                    $this->parsed_schema[$current_table]['indexes'][] = $index_def;
                }
            }
        }
        
        return $this->parsed_schema;
    }
    
    /**
     * Parse column definition
     */
    private function parseColumn($line)
    {
        // Remove leading spaces
        $line = ltrim($line);
        
        // Split by comma for multiple columns
        $columns = explode(',', $line);
        $parsed_columns = [];
        
        foreach ($columns as $column_line) {
            $column_line = trim($column_line);
            if (empty($column_line)) continue;
            
            $parts = explode(':', $column_line);
            if (count($parts) < 2) continue;
            
            $name = trim($parts[0]);
            $properties = trim($parts[1]);
            
            $column = [
                'name' => $name,
                'type' => null,
                'length' => null,
                'primary' => false,
                'auto_increment' => false,
                'unique' => false,
                'nullable' => false,
                'default' => null,
                'comment' => ''
            ];
            
            // Parse properties
            $props = explode(',', $properties);
            foreach ($props as $prop) {
                $prop = trim($prop);
                
                if ($prop === 'primary') {
                    $column['primary'] = true;
                } elseif ($prop === 'auto_increment') {
                    $column['auto_increment'] = true;
                } elseif ($prop === 'unique') {
                    $column['unique'] = true;
                } elseif ($prop === 'null' || $prop === 'nullable') {
                    $column['nullable'] = true;
                } elseif (strpos($prop, 'default:') === 0) {
                    $column['default'] = substr($prop, 8);
                } elseif (strpos($prop, 'comment:') === 0) {
                    $column['comment'] = substr($prop, 8);
                } else {
                    // Type and length
                    if (strpos($prop, '(') !== false) {
                        list($type, $length) = explode('(', $prop);
                        $column['type'] = trim($type);
                        $column['length'] = trim(substr($length, 0, -1)); // Remove closing parenthesis
                    } else {
                        $column['type'] = $prop;
                    }
                }
            }
            
            $parsed_columns[] = $column;
        }
        
        return $parsed_columns;
    }
    
    /**
     * Parse index definition
     */
    private function parseIndex($line)
    {
        $line = ltrim($line);
        $parts = explode(':', $line);
        
        if (count($parts) < 2) return null;
        
        $name = trim($parts[0]);
        $properties = trim($parts[1]);
        
        $index = [
            'name' => $name,
            'type' => 'INDEX',
            'columns' => [],
            'unique' => false
        ];
        
        $props = explode(',', $properties);
        foreach ($props as $prop) {
            $prop = trim($prop);
            
            if ($prop === 'unique') {
                $index['unique'] = true;
                $index['type'] = 'UNIQUE INDEX';
            } elseif ($prop === 'primary') {
                $index['type'] = 'PRIMARY KEY';
            } else {
                // Column names
                $index['columns'][] = $prop;
            }
        }
        
        return $index;
    }
    
    /**
     * Generate SQL CREATE TABLE from parsed schema
     */
    public function generateSQL($table_name)
    {
        if (!isset($this->parsed_schema[$table_name])) {
            throw new Exception("Table not found in schema: $table_name");
        }
        
        $table = $this->parsed_schema[$table_name];
        $sql = "CREATE TABLE `{$table_name}` (\n";
        
        // Add columns
        $column_definitions = [];
        foreach ($table['columns'] as $column) {
            $def = "  `{$column['name']}` " . $this->getColumnDefinition($column);
            $column_definitions[] = $def;
        }
        
        $sql .= implode(",\n", $column_definitions);
        
        // Add indexes
        if (!empty($table['indexes'])) {
            foreach ($table['indexes'] as $index) {
                $sql .= ",\n  " . $this->getIndexDefinition($index);
            }
        }
        
        // Add primary key if not explicitly defined
        $primary_columns = $this->getPrimaryColumns($table['columns']);
        if (!empty($primary_columns) && !$this->hasExplicitPrimaryKey($table['indexes'])) {
            $sql .= ",\n  PRIMARY KEY (`" . implode('`, `', $primary_columns) . "`)";
        }
        
        $sql .= "\n) ENGINE={$table['engine']} DEFAULT CHARSET={$table['charset']} COLLATE={$table['collate']}";
        
        if (!empty($table['comment'])) {
            $sql .= " COMMENT='{$table['comment']}'";
        }
        
        return $sql . ";";
    }
    
    /**
     * Get column definition SQL
     */
    private function getColumnDefinition($column)
    {
        $def = strtoupper($column['type']);
        
        if ($column['length']) {
            $def .= "({$column['length']})";
        }
        
        if ($column['auto_increment']) {
            $def .= " NOT NULL AUTO_INCREMENT";
        } elseif ($column['nullable']) {
            $def .= " DEFAULT NULL";
        } else {
            $def .= " NOT NULL";
        }
        
        if ($column['default'] !== null) {
            $def .= " DEFAULT " . $this->formatDefaultValue($column['default']);
        }
        
        if ($column['unique'] && !$column['primary']) {
            $def .= " UNIQUE";
        }
        
        if (!empty($column['comment'])) {
            $def .= " COMMENT '{$column['comment']}'";
        }
        
        return $def;
    }
    
    /**
     * Get index definition SQL
     */
    private function getIndexDefinition($index)
    {
        $columns = array_map(function($col) {
            return "`$col`";
        }, $index['columns']);
        
        $def = $index['type'] . " (`" . implode('`, `', $columns) . "`)";
        
        if ($index['name'] !== 'PRIMARY') {
            $def .= " KEY `{$index['name']}`";
        }
        
        return $def;
    }
    
    /**
     * Format default value
     */
    private function formatDefaultValue($value)
    {
        if (is_numeric($value)) {
            return $value;
        } elseif (strtoupper($value) === 'NULL') {
            return 'NULL';
        } elseif (strtoupper($value) === 'CURRENT_TIMESTAMP') {
            return 'CURRENT_TIMESTAMP';
        } else {
            return "'$value'";
        }
    }
    
    /**
     * Get primary key columns
     */
    private function getPrimaryColumns($columns)
    {
        $primary = [];
        foreach ($columns as $column) {
            if ($column['primary']) {
                $primary[] = $column['name'];
            }
        }
        return $primary;
    }
    
    /**
     * Check if explicit primary key is defined
     */
    private function hasExplicitPrimaryKey($indexes)
    {
        foreach ($indexes as $index) {
            if ($index['type'] === 'PRIMARY KEY') {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Generate migration file content
     */
    public function generateMigration($table_name, $version = '1.0.0')
    {
        $sql = $this->generateSQL($table_name);
        
        $migration = "-- ============================================================\n";
        $migration .= "-- Migration for table: $table_name\n";
        $migration .= "-- Version: $version\n";
        $migration .= "-- Generated from TOON file\n";
        $migration .= "-- ============================================================\n\n";
        $migration .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $migration .= "START TRANSACTION;\n";
        $migration .= "SET time_zone = \"+00:00\";\n\n";
        $migration .= $sql . "\n\n";
        $migration .= "COMMIT;\n";
        
        return $migration;
    }
    
    /**
     * Validate TOON schema against doctrine rules
     */
    public function validateAgainstDoctrine($table_name)
    {
        if (!isset($this->parsed_schema[$table_name])) {
            return ['valid' => false, 'errors' => ["Table not found: $table_name"]];
        }
        
        $table = $this->parsed_schema[$table_name];
        $errors = [];
        
        // Check for timestamp columns
        $has_created_ymdhis = false;
        $has_updated_ymdhis = false;
        
        foreach ($table['columns'] as $column) {
            if ($column['name'] === 'created_ymdhis') {
                $has_created_ymdhis = true;
                if ($column['type'] !== 'BIGINT') {
                    $errors[] = "created_ymdhis must be BIGINT type";
                }
            }
            
            if ($column['name'] === 'updated_ymdhis') {
                $has_updated_ymdhis = true;
                if ($column['type'] !== 'BIGINT') {
                    $errors[] = "updated_ymdhis must be BIGINT type";
                }
            }
        }
        
        if (!$has_created_ymdhis) {
            $errors[] = "Table must have created_ymdhis column";
        }
        
        if (!$has_updated_ymdhis) {
            $errors[] = "Table must have updated_ymdhis column";
        }
        
        // Check for foreign key constraints (should not exist in TOON)
        // This is more of a code review check
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'table' => $table_name
        ];
    }
}
