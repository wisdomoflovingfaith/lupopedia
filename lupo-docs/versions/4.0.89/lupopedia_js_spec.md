---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: specification
  file_path_from_root: "lupo-docs/versions/4.0.89/lupopedia_js_spec.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/lupopedia_js_spec.md"
  last_modified_utc: "20260328103000"
  system_version: "4.0.89"
  channel_id: 42
  thread_id: "4.0.89-lupopedia-js"
  actor_id: 26
  delegation_chain: "26:1"
  artifact_type: specification
  artifact_kind: technical_specification
  purpose: Technical specification for lupopedia_js.php tracking system
  mood_rgb: "666666"
  traits: ["lupopedia_js", "tracking_system", "technical_specification", "softaculous"]
  tags: ["4.0.89", "lupopedia_js", "specification", "tracking", "javascript"]

lupopedia.edges:
  outbound_edges:
    - { to: "crafty_syntax_backlog.md", type: "specifies", weight: 1.0 }
    - { to: "TODO.md", type: "generates", weight: 1.0 }
    - { to: "lupo-docs/versions/4.0.88/prd/04_lupopedia_js_foundation.md", type: "extends", weight: 0.9 }
    - { to: "lupo-archive/legacy/craftysyntax-3.7.5/livehelp_js.php", type: "analyzes", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_paths.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_visits.toon", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260328103000"
  verified_by:
    identity_type: "actor"
    actor_id: 26
    agent_name_identity: "THOTH"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "windsurf"
  orchestrator: "26:1"
  next_action:
    - "Begin implementation of lupopedia_js.php system"
    - "Complete database schema for tracking data"
    - "Test integration with existing systems"
---

# lupopedia_js.php Technical Specification

**Version**: 4.0.89  
**Thread**: 4.0.89-lupopedia-js  
**Actor**: THOTH (actor_id 26)  
**Status**: Ready for Implementation  
**Priority**: Critical  

---

## Executive Summary

This specification defines the lupopedia_js.php tracking system, a critical component for visitor analytics and navigation intelligence. The system addresses Softaculous feedback requirements while providing comprehensive tracking capabilities for the Lupopedia platform.

---

## System Overview

### Purpose
- **Visitor Tracking**: Monitor visitor navigation and behavior patterns
- **Analytics Collection**: Gather real-time interaction data
- **Navigation Intelligence**: Provide insights into user journeys
- **Performance Monitoring**: Track system performance and user experience

### Scope
- **Frontend Tracking**: JavaScript-based client-side tracking
- **Data Collection**: Real-time data capture and transmission
- **Backend Integration**: Server-side data processing and storage
- **Analytics Engine**: Data analysis and reporting capabilities

---

## Technical Architecture

### System Components

#### 1. Client-Side JavaScript (lupopedia_js.php)
**Purpose**: Frontend tracking and data collection  
**Technology**: Vanilla JavaScript (compatible with PHP 7.4+)  
**Dependencies**: None (self-contained)  

**Core Functions**:
```javascript
// Session tracking
lupopedia.trackSession(visitorId, sessionId);

// Page navigation tracking
lupopedia.trackPage(pageUrl, pageTitle, referrer);

// Click event tracking
lupopedia.trackClick(elementId, elementType, targetUrl);

// Form interaction tracking
lupopedia.trackForm(formId, fieldName, action);

// Custom event tracking
lupopedia.trackEvent(eventName, eventData);
```

#### 2. Data Transmission Layer
**Purpose**: Secure data transmission to backend  
**Technology**: AJAX/HTTP requests  
**Protocol**: HTTPS with authentication tokens  

**Data Format**:
```json
{
  "visitor_id": "unique_visitor_identifier",
  "session_id": "session_identifier",
  "timestamp": "2026-03-28T10:30:00Z",
  "event_type": "page_view|click|form_submit|custom",
  "page_url": "current_page_url",
  "referrer": "referrer_url",
  "user_agent": "browser_user_agent",
  "event_data": {
    "element_id": "clicked_element",
    "element_type": "button|link|form",
    "target_url": "destination_url"
  }
}
```

#### 3. Backend Processing (lupo_js_handler.php)
**Purpose**: Data processing and storage  
**Technology**: PHP 7.4+ compatible  
**Database**: lupo_paths, lupo_visits, lupo_events  

**Processing Steps**:
1. Authentication token validation
2. Data sanitization and validation
3. Database record creation/updates
4. Real-time analytics updates
5. Response generation

---

## Database Schema

### Existing Tables Integration

#### lupo_visits
**Purpose**: Visitor session tracking  
**Key Fields**:
- `visit_id` (PRIMARY KEY)
- `visitor_id` (UNIQUE INDEX)
- `session_id` (INDEX)
- `first_visit` (DATETIME)
- `last_activity` (DATETIME)
- `page_count` (INT)
- `total_time` (INT)

#### lupo_paths
**Purpose**: Page navigation tracking  
**Key Fields**:
- `path_id` (PRIMARY KEY)
- `visit_id` (FOREIGN KEY)
- `page_url` (VARCHAR)
- `page_title` (VARCHAR)
- `referrer` (VARCHAR)
- `timestamp` (DATETIME)
- `time_on_page` (INT)

### New Tables Required

#### lupo_events
**Purpose**: Event tracking (clicks, forms, custom events)  
**Schema**:
```sql
CREATE TABLE lupo_events (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    visit_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    event_type ENUM('page_view', 'click', 'form_submit', 'custom') NOT NULL,
    page_url VARCHAR(2048) NOT NULL,
    element_id VARCHAR(255),
    element_type VARCHAR(50),
    target_url VARCHAR(2048),
    event_data JSON,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_visit_id (visit_id),
    INDEX idx_session_id (session_id),
    INDEX idx_timestamp (timestamp),
    INDEX idx_event_type (event_type)
);
```

#### lupo_visitor_data
**Purpose**: Extended visitor information  
**Schema**:
```sql
CREATE TABLE lupo_visitor_data (
    visitor_id VARCHAR(255) PRIMARY KEY,
    first_visit DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_visit DATETIME DEFAULT CURRENT_TIMESTAMP,
    visit_count INT DEFAULT 1,
    total_pages INT DEFAULT 0,
    total_time INT DEFAULT 0,
    user_agent TEXT,
    browser VARCHAR(100),
    platform VARCHAR(100),
    country VARCHAR(2),
    city VARCHAR(100),
    language VARCHAR(10),
    screen_resolution VARCHAR(20),
    INDEX idx_last_visit (last_visit),
    INDEX idx_visit_count (visit_count)
);
```

---

## Implementation Details

### Client-Side Implementation

#### File Structure
```
lupopedia_js.php (main tracking script)
├── Core tracking functions
├── Event handlers
├── Data transmission
└── Configuration management
```

#### Configuration Options
```javascript
var lupopedia_config = {
    tracking_enabled: true,
    endpoint_url: '/lupo-js-handler.php',
    session_timeout: 1800, // 30 minutes
    page_view_threshold: 5, // seconds
    click_tracking: true,
    form_tracking: true,
    custom_events: true,
    debug_mode: false,
    respect_do_not_track: true
};
```

#### Session Management
```javascript
// Generate unique visitor ID
function getVisitorId() {
    var visitorId = localStorage.getItem('lupopedia_visitor_id');
    if (!visitorId) {
        visitorId = 'visitor_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('lupopedia_visitor_id', visitorId);
    }
    return visitorId;
}

// Generate session ID
function getSessionId() {
    var sessionId = sessionStorage.getItem('lupopedia_session_id');
    if (!sessionId) {
        sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        sessionStorage.setItem('lupopedia_session_id', sessionId);
    }
    return sessionId;
}
```

### Server-Side Implementation

#### Data Handler (lupo_js_handler.php)
```php
<?php
// Authentication and security
require_once 'lupo-includes/bootstrap.php';

// Validate request
if (!validateTrackingRequest()) {
    http_response_code(403);
    exit('Unauthorized');
}

// Process tracking data
$trackingData = json_decode(file_get_contents('php://input'), true);

if ($trackingData) {
    // Sanitize and validate data
    $sanitizedData = sanitizeTrackingData($trackingData);
    
    // Update visitor information
    updateVisitorData($sanitizedData);
    
    // Update session information
    updateSessionData($sanitizedData);
    
    // Record page view
    if ($sanitizedData['event_type'] === 'page_view') {
        recordPageView($sanitizedData);
    }
    
    // Record event
    recordEvent($sanitizedData);
    
    // Return success response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Tracking data recorded']);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
```

---

## Security Considerations

### Data Privacy
- **GDPR Compliance**: Implement consent management
- **Data Minimization**: Collect only necessary data
- **Data Retention**: Define clear retention policies
- **User Rights**: Provide data access and deletion

### Security Measures
- **Authentication**: Token-based request validation
- **Data Sanitization**: Input validation and sanitization
- **Rate Limiting**: Prevent abuse and data flooding
- **HTTPS Only**: Secure data transmission

### Privacy Controls
```javascript
// Respect Do Not Track
if (navigator.doNotTrack === '1') {
    lupopedia_config.tracking_enabled = false;
}

// Consent management
function checkConsent() {
    return localStorage.getItem('lupopedia_tracking_consent') === 'granted';
}
```

---

## Performance Optimization

### Client-Side Optimization
- **Async Loading**: Non-blocking script execution
- **Event Delegation**: Efficient event handling
- **Batching**: Group multiple events before transmission
- **Caching**: Cache configuration and settings

### Server-Side Optimization
- **Database Indexing**: Optimized query performance
- **Connection Pooling**: Efficient database connections
- **Data Archiving**: Archive old data to maintain performance
- **Caching**: Cache frequent queries and results

### Monitoring
```javascript
// Performance monitoring
lupopedia.trackPerformance = function() {
    if (window.performance && window.performance.timing) {
        var timing = window.performance.timing;
        var loadTime = timing.loadEventEnd - timing.navigationStart;
        lupopedia.trackEvent('page_load_time', {load_time: loadTime});
    }
};
```

---

## Integration Points

### Existing System Integration
- **Authentication**: Use existing user authentication
- **Database**: Integrate with existing lupo_* tables
- **Channels**: Use channel-based coordination for events
- **Context**: Integrate with context model for enhanced analytics

### Third-Party Integration
- **Google Analytics**: Optional integration with external analytics
- **CRM Systems**: Export visitor data to CRM systems
- **Marketing Automation**: Integration with marketing platforms
- **A/B Testing**: Integration with testing frameworks

---

## Testing Strategy

### Unit Testing
- **JavaScript Functions**: Test all tracking functions
- **Data Validation**: Test data sanitization and validation
- **Database Operations**: Test database interactions
- **Error Handling**: Test error scenarios

### Integration Testing
- **End-to-End Tracking**: Test complete tracking workflow
- **Database Integration**: Test database operations
- **API Integration**: Test server-side handlers
- **Performance Testing**: Test under load conditions

### User Acceptance Testing
- **Browser Compatibility**: Test across browsers
- **Device Compatibility**: Test across devices
- **Performance Impact**: Measure performance impact
- **User Experience**: Test user interface impact

---

## Deployment Strategy

### Phase 1: Core Implementation
- **Week 1**: Client-side tracking implementation
- **Week 1**: Server-side data handler implementation
- **Week 1**: Database schema creation
- **Week 1**: Basic testing and validation

### Phase 2: Advanced Features
- **Week 2**: Event tracking implementation
- **Week 2**: Analytics dashboard integration
- **Week 2**: Performance optimization
- **Week 2**: Security implementation

### Phase 3: Integration and Testing
- **Week 3**: Third-party integration
- **Week 3**: Comprehensive testing
- **Week 3**: Performance optimization
- **Week 3**: Documentation completion

### Phase 4: Production Deployment
- **Week 4**: Production deployment
- **Week 4**: Monitoring and optimization
- **Week 4**: User training and documentation
- **Week 4**: Post-deployment support

---

## Maintenance and Support

### Ongoing Maintenance
- **Performance Monitoring**: Continuous performance monitoring
- **Data Quality**: Regular data quality checks
- **Security Updates**: Regular security updates
- **Feature Updates**: Feature enhancements and bug fixes

### Support Procedures
- **Issue Tracking**: Track and resolve issues
- **User Support**: Provide user support and training
- **Documentation**: Maintain up-to-date documentation
- **Monitoring**: Continuous system monitoring

---

## Success Metrics

### Technical Metrics
- **Data Accuracy**: 99%+ data accuracy
- **Performance**: <100ms page load impact
- **Reliability**: 99.9% uptime
- **Security**: Zero security breaches

### Business Metrics
- **User Adoption**: 90%+ user adoption rate
- **Data Insights**: Actionable insights generated
- **Conversion Impact**: 5%+ conversion improvement
- **Support Efficiency**: 20%+ support efficiency improvement

---

## Conclusion

The lupopedia_js.php tracking system provides comprehensive visitor analytics and navigation intelligence while maintaining security, performance, and privacy standards. The system is designed for scalability and extensibility, supporting future enhancements and integrations.

**Implementation Priority**: Critical - Required for Softaculous feedback  
**Estimated Timeline**: 4 weeks for complete implementation  
**Resource Requirements**: 1 developer (HEPHAESTUS) + 1 reviewer (LILITH)  
**Success Criteria**: All metrics met, full integration complete

---

**THOTH (actor_id 26)** — Technical specification complete. Ready for implementation.
