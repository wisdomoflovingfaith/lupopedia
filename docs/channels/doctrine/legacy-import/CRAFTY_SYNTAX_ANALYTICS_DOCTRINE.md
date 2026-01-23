# CRAFTY SYNTAX ANALYTICS DOCTRINE

## Core Analytics Principles

### 1. **Path Tracking Doctrine**
- **Principle**: Every visitor movement must be tracked
- **Implementation**: Hierarchical path storage with parent-child relationships
- **Pattern**: Complete journey from entry to exit
- **Benefits**: Comprehensive visitor behavior analysis

### 2. **Data Persistence Doctrine**
- **Principle**: Analytics data must never be lost
- **Implementation**: Monthly table partitioning and backup procedures
- **Pattern**: Time-based data aggregation and archival
- **Benefits**: Historical analysis and trend identification

### 3. **Real-Time Statistics Doctrine**
- **Principle**: Statistics must be available in real-time
- **Implementation**: Live counters and dashboard updates
- **Pattern**: Incremental updates with caching
- **Benefits**: Immediate operational insights

### 4. **Performance Optimization Doctrine**
- **Principle**: Analytics must not impact system performance
- **Implementation**: Efficient queries and data structures
- **Pattern**: Indexed access and limited result sets
- **Benefits**: System responsiveness under load

## Analytics Architecture

### 1. **Data Collection Architecture**
```
Analytics Data Flow:
├── Visitor Tracking (page views, clicks, time)
├── Session Management (session start/end, duration)
├── Chat Interactions (initiation, messages, resolution)
├── Operator Performance (response time, chat count)
├── Department Statistics (volume, conversion rates)
├── System Metrics (load, errors, availability)
└── External Integration (referrers, keywords, campaigns)
```

### 2. **Storage Architecture**
```
Data Storage Strategy:
├── Live Data (current month - high performance)
├── Monthly Archives (historical data - compressed)
├── Summary Tables (pre-aggregated statistics)
├── Index Tables (fast lookup and filtering)
├── Backup Storage (redundant data copies)
└── Export Archives (offline data storage)
```

### 3. **Processing Architecture**
```
Analytics Processing Pipeline:
├── Real-Time Processing (immediate counters)
├── Batch Processing (hourly/daily aggregations)
├── Trend Analysis (pattern detection)
├── Anomaly Detection (unusual behavior alerts)
├── Report Generation (scheduled reports)
└── Data Export (custom format exports)
```

### 4. **Query Architecture**
```
Analytics Query Strategy:
├── Fast Queries (live dashboard data)
├── Complex Queries (historical analysis)
├── Cached Queries (frequently accessed data)
├── Scheduled Queries (background processing)
├── Custom Queries (user-defined reports)
└── Export Queries (data extraction)
```

## Data Collection Patterns

### 1. **Page Tracking Pattern**
```php
// Visitor page tracking
function track_page_view($sessionid, $page_url, $page_title, $referrer){
    global $mydatabase;
    
    $timeof = date("YmdHis");
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $ip_address = get_ipaddress();
    
    $query = "INSERT INTO livehelp_visits_monthly 
              (sessionid, pageurl, pagetitle, referrer, timestamp, 
               user_agent, ip_address) 
              VALUES ('$sessionid', '$page_url', '$page_title', 
                      '$referrer', '$timeof', '$user_agent', '$ip_address')";
    
    $mydatabase->insert($query);
    
    // Update visitor session
    update_visitor_session($sessionid, $page_url);
}
```

### 2. **Session Tracking Pattern**
```php
// Session lifecycle tracking
function track_session_event($sessionid, $event_type, $event_data){
    global $mydatabase;
    
    $timeof = date("YmdHis");
    $query = "INSERT INTO livehelp_sessions 
              (sessionid, event_type, event_data, timestamp) 
              VALUES ('$sessionid', '$event_type', '$event_data', '$timeof')";
    
    $mydatabase->insert($query);
}
```

### 3. **Chat Analytics Pattern**
```php
// Chat interaction tracking
function track_chat_event($channel_id, $event_type, $operator_id, $data){
    global $mydatabase;
    
    $timeof = date("YmdHis");
    $query = "INSERT INTO livehelp_chat_analytics 
              (channel_id, event_type, operator_id, event_data, timestamp) 
              VALUES ('$channel_id', '$event_type', '$operator_id', 
                      '$data', '$timeof')";
    
    $mydatabase->insert($query);
}
```

### 4. **Performance Tracking Pattern**
```php
// System performance monitoring
function track_system_performance($metric_type, $metric_value, $context){
    global $mydatabase;
    
    $timeof = date("YmdHis");
    $query = "INSERT INTO livehelp_performance 
              (metric_type, metric_value, context, timestamp) 
              VALUES ('$metric_type', '$metric_value', '$context', '$timeof')";
    
    $mydatabase->insert($query);
}
```

## Data Aggregation Patterns

### 1. **Daily Summary Pattern**
```php
// Daily statistics aggregation
function generate_daily_summary($date){
    global $mydatabase;
    
    // Page views
    $query = "SELECT COUNT(*) as page_views, 
                     COUNT(DISTINCT sessionid) as unique_visitors
              FROM livehelp_visits_monthly 
              WHERE DATE(timestamp) = '$date'";
    
    $result = $mydatabase->query($query);
    $stats = $result->fetchRow(DB_FETCHMODE_ASSOC);
    
    // Store summary
    $query = "INSERT INTO livehelp_daily_summary 
              (date, page_views, unique_visitors) 
              VALUES ('$date', '{$stats['page_views']}', 
                      '{$stats['unique_visitors']}')
              ON DUPLICATE KEY UPDATE 
              page_views = '{$stats['page_views']}',
              unique_visitors = '{$stats['unique_visitors']}'";
    
    $mydatabase->query($query);
}
```

### 2. **Monthly Archive Pattern**
```php
// Monthly data archival
function archive_monthly_data($year, $month){
    global $mydatabase;
    
    $table_name = "livehelp_visits_" . sprintf("%04d%02d", $year, $month);
    
    // Create archive table
    $query = "CREATE TABLE IF NOT EXISTS $table_name 
              LIKE livehelp_visits_monthly";
    $mydatabase->query($query);
    
    // Move data to archive
    $query = "INSERT INTO $table_name 
              SELECT * FROM livehelp_visits_monthly 
              WHERE YEAR(timestamp) = '$year' 
              AND MONTH(timestamp) = '$month'";
    $mydatabase->query($query);
    
    // Remove from live table
    $query = "DELETE FROM livehelp_visits_monthly 
              WHERE YEAR(timestamp) = '$year' 
              AND MONTH(timestamp) = '$month'";
    $mydatabase->query($query);
}
```

### 3. **Path Analysis Pattern**
```php
// Visitor path analysis
function analyze_visitor_paths($sessionid){
    global $mydatabase;
    
    $query = "SELECT pageurl, timestamp, parent_id 
              FROM livehelp_visits_monthly 
              WHERE sessionid = '$sessionid' 
              ORDER BY timestamp ASC";
    
    $visits = $mydatabase->query($query);
    $path = array();
    
    while($visits->next()){
        $visit = $visits->getCurrentValuesAsHash();
        $path[] = array(
            'url' => $visit['pageurl'],
            'timestamp' => $visit['timestamp'],
            'parent' => $visit['parent_id']
        );
    }
    
    return analyze_path_flow($path);
}
```

### 4. **Conversion Tracking Pattern**
```php
// Conversion rate calculation
function calculate_conversion_rates($department_id, $date_range){
    global $mydatabase;
    
    // Total chats
    $query = "SELECT COUNT(*) as total_chats 
              FROM livehelp_chats 
              WHERE department_id = '$department_id' 
              AND chat_date BETWEEN '$date_start' AND '$date_end'";
    $result = $mydatabase->query($query);
    $total_chats = $result->getCurrentValueByKey('total_chats');
    
    // Conversions
    $query = "SELECT COUNT(*) as conversions 
              FROM livehelp_conversions 
              WHERE department_id = '$department_id' 
              AND conversion_date BETWEEN '$date_start' AND '$date_end'";
    $result = $mydatabase->query($query);
    $conversions = $result->getCurrentValueByKey('conversions');
    
    return array(
        'total_chats' => $total_chats,
        'conversions' => $conversions,
        'conversion_rate' => $total_chats > 0 ? ($conversions / $total_chats) * 100 : 0
    );
}
```

## Real-Time Analytics Patterns

### 1. **Live Counter Pattern**
```php
// Real-time visitor counter
function get_live_visitor_count(){
    global $mydatabase;
    
    $timeout_time = date("YmdHis", strtotime("-30 minutes"));
    
    $query = "SELECT COUNT(DISTINCT sessionid) as live_visitors 
              FROM livehelp_users 
              WHERE lastaction > '$timeout_time'";
    
    $result = $mydatabase->query($query);
    return $result->getCurrentValueByKey('live_visitors');
}
```

### 2. **Active Chat Counter Pattern**
```php
// Real-time active chat counter
function get_active_chat_count($department_id = null){
    global $mydatabase;
    
    $where_clause = "";
    if($department_id){
        $where_clause = "AND department_id = '$department_id'";
    }
    
    $query = "SELECT COUNT(*) as active_chats 
              FROM livehelp_users 
              WHERE status = 'chatting' 
              $where_clause";
    
    $result = $mydatabase->query($query);
    return $result->getCurrentValueByKey('active_chats');
}
```

### 3. **Operator Status Pattern**
```php
// Real-time operator status
function get_operator_status($operator_id){
    global $mydatabase;
    
    $query = "SELECT status, lastaction, current_chats 
              FROM livehelp_operators 
              WHERE user_id = '$operator_id'";
    
    $result = $mydatabase->query($query);
    return $result->fetchRow(DB_FETCHMODE_ASSOC);
}
```

### 4. **System Load Pattern**
```php
// Real-time system metrics
function get_system_metrics(){
    global $mydatabase;
    
    $metrics = array();
    
    // Database query count
    $metrics['query_count'] = get_query_count();
    
    // Active sessions
    $metrics['active_sessions'] = get_active_session_count();
    
    // Memory usage
    $metrics['memory_usage'] = memory_get_usage(true);
    
    // CPU usage (if available)
    $metrics['cpu_usage'] = get_cpu_usage();
    
    return $metrics;
}
```

## Reporting Patterns

### 1. **Daily Report Pattern**
```php
// Generate daily analytics report
function generate_daily_report($date){
    global $mydatabase;
    
    $report = array();
    
    // Visitor statistics
    $report['visitors'] = get_daily_visitor_stats($date);
    
    // Chat statistics
    $report['chats'] = get_daily_chat_stats($date);
    
    // Operator performance
    $report['operators'] = get_daily_operator_stats($date);
    
    // Department performance
    $report['departments'] = get_daily_department_stats($date);
    
    // System performance
    $report['system'] = get_daily_system_stats($date);
    
    return $report;
}
```

### 2. **Trend Analysis Pattern**
```php
// Trend analysis over time
function analyze_trends($metric, $date_range, $granularity = 'daily'){
    global $mydatabase;
    
    $group_by = $granularity == 'hourly' ? 'DATE_FORMAT(timestamp, "%Y-%m-%d %H:00:00")' : 'DATE(timestamp)';
    
    $query = "SELECT $group_by as period, 
                     SUM($metric) as total_value,
                     AVG($metric) as avg_value,
                     MIN($metric) as min_value,
                     MAX($metric) as max_value
              FROM livehelp_analytics 
              WHERE timestamp BETWEEN '$date_start' AND '$date_end'
              GROUP BY $group_by
              ORDER BY period ASC";
    
    return $mydatabase->query($query);
}
```

### 3. **Comparison Report Pattern**
```php
// Period-over-period comparison
function compare_periods($metric, $current_period, $previous_period){
    global $mydatabase;
    
    $current_stats = get_period_stats($metric, $current_period);
    $previous_stats = get_period_stats($metric, $previous_period);
    
    return array(
        'current' => $current_stats,
        'previous' => $previous_stats,
        'change' => $current_stats - $previous_stats,
        'percent_change' => $previous_stats > 0 ? 
            (($current_stats - $previous_stats) / $previous_stats) * 100 : 0
    );
}
```

### 4. **Custom Report Pattern**
```php
// User-defined custom reports
function generate_custom_report($report_config){
    global $mydatabase;
    
    $report_data = array();
    
    foreach($report_config['metrics'] as $metric){
        $query = build_custom_query($metric, $report_config);
        $result = $mydatabase->query($query);
        $report_data[$metric] = $result->fetchAll();
    }
    
    return $report_data;
}
```

## Data Export Patterns

### 1. **CSV Export Pattern**
```php
// Export data to CSV format
function export_to_csv($query, $filename, $headers = null){
    global $mydatabase;
    
    $result = $mydatabase->query($query);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers if provided
    if($headers){
        fputcsv($output, $headers);
    }
    
    // Write data
    while($result->next()){
        $row = $result->getCurrentValuesAsHash();
        fputcsv($output, $row);
    }
    
    fclose($output);
}
```

### 2. **JSON Export Pattern**
```php
// Export data to JSON format
function export_to_json($query, $filename){
    global $mydatabase;
    
    $result = $mydatabase->query($query);
    $data = array();
    
    while($result->next()){
        $data[] = $result->getCurrentValuesAsHash();
    }
    
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    echo json_encode($data, JSON_PRETTY_PRINT);
}
```

### 3. **XML Export Pattern**
```php
// Export data to XML format
function export_to_xml($query, $filename, $root_element){
    global $mydatabase;
    
    $result = $mydatabase->query($query);
    
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo "<$root_element>";
    
    while($result->next()){
        $row = $result->getCurrentValuesAsHash();
        echo "<record>";
        foreach($row as $key => $value){
            echo "<$key>" . htmlspecialchars($value) . "</$key>";
        }
        echo "</record>";
    }
    
    echo "</$root_element>";
}
```

### 4. **Scheduled Export Pattern**
```php
// Schedule automatic data exports
function schedule_export($export_config){
    global $mydatabase;
    
    $schedule_time = $export_config['schedule'];
    $export_format = $export_config['format'];
    $export_query = $export_config['query'];
    $export_filename = $export_config['filename'];
    
    // Create scheduled job
    $query = "INSERT INTO livehelp_scheduled_exports 
              (schedule_time, export_format, export_query, export_filename, 
               last_run, next_run) 
              VALUES ('$schedule_time', '$export_format', '$export_query', 
                      '$export_filename', NULL, '$next_run')";
    
    $mydatabase->insert($query);
}
```

## Performance Optimization Patterns

### 1. **Query Optimization Pattern**
```php
// Optimized analytics query
function get_optimized_stats($date_range, $filters = array()){
    global $mydatabase;
    
    // Use indexed columns first
    $where_clause = "timestamp BETWEEN '$date_start' AND '$date_end'";
    
    // Add filters with proper indexing
    if(!empty($filters['department_id'])){
        $where_clause .= " AND department_id = '{$filters['department_id']}'";
    }
    
    if(!empty($filters['operator_id'])){
        $where_clause .= " AND operator_id = '{$filters['operator_id']}'";
    }
    
    // Use summary tables when possible
    $query = "SELECT * FROM livehelp_daily_summary 
              WHERE $where_clause 
              ORDER BY date DESC 
              LIMIT 1000";
    
    return $mydatabase->query($query);
}
```

### 2. **Caching Pattern**
```php
// Analytics data caching
function get_cached_stats($cache_key, $query, $cache_time = 300){
    global $mydatabase;
    
    // Check cache first
    $cached_data = get_cache($cache_key);
    if($cached_data && (time() - $cached_data['timestamp']) < $cache_time){
        return $cached_data['data'];
    }
    
    // Generate fresh data
    $result = $mydatabase->query($query);
    $data = $result->fetchAll();
    
    // Store in cache
    set_cache($cache_key, array(
        'data' => $data,
        'timestamp' => time()
    ));
    
    return $data;
}
```

### 3. **Batch Processing Pattern**
```php
// Process large datasets in batches
function process_analytics_batch($batch_size = 1000){
    global $mydatabase;
    
    $offset = 0;
    
    while(true){
        $query = "SELECT * FROM livehelp_raw_analytics 
                  LIMIT $offset, $batch_size";
        
        $result = $mydatabase->query($query);
        
        if($result->numrows() == 0){
            break;
        }
        
        // Process batch
        while($result->next()){
            $record = $result->getCurrentValuesAsHash();
            process_analytics_record($record);
        }
        
        $offset += $batch_size;
    }
}
```

### 4. **Index Optimization Pattern**
```php
// Ensure proper indexing for analytics queries
function optimize_analytics_indexes(){
    global $mydatabase;
    
    // Time-based queries
    $mydatabase->query("CREATE INDEX IF NOT EXISTS idx_timestamp 
                        ON livehelp_visits_monthly (timestamp)");
    
    // Session-based queries
    $mydatabase->query("CREATE INDEX IF NOT EXISTS idx_sessionid 
                        ON livehelp_visits_monthly (sessionid)");
    
    // Composite indexes for common queries
    $mydatabase->query("CREATE INDEX IF NOT EXISTS idx_dept_time 
                        ON livehelp_chats (department_id, chat_date)");
    
    // Analytics summary indexes
    $mydatabase->query("CREATE INDEX IF NOT EXISTS idx_date_dept 
                        ON livehelp_daily_summary (date, department_id)");
}
```

---

**This analytics doctrine represents the accumulated wisdom of 25 years of web analytics and visitor tracking. These patterns have proven their reliability in production environments and must be preserved in all future development to maintain data integrity and system performance.**
