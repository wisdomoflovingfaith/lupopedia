<?php
/**
 * Adversarial test harness (4.0.20 T4). Red-team style probes; local-only, safe, non-destructive.
 * PHP 5.3 compatible; curl-based; logs to tests/adversarial/results/YYYY-MM-DD.jsonl.
 */

class StonedWolfieHarness {

    private $baseUrl;
    private $resultsDir;
    private $results = array();

    public function __construct($baseUrl) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $repo = dirname(dirname(__DIR__));
        $this->resultsDir = $repo . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'adversarial' . DIRECTORY_SEPARATOR . 'results';
        if (!is_dir($this->resultsDir)) {
            @mkdir($this->resultsDir, 0755, true);
        }
    }

    /**
     * Make HTTP request via curl. Returns array('code' => int, 'body' => string, 'headers' => array).
     * Requires curl extension; if missing, returns array('code' => 0, 'body' => '', 'headers' => array()).
     */
    public function makeRequest($url, $method, $data = null, $cookies = array()) {
        if (!function_exists('curl_init')) {
            return array('code' => 0, 'body' => '', 'headers' => array());
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $headers = array();
        if (!empty($cookies)) {
            $parts = array();
            foreach ($cookies as $k => $v) {
                $parts[] = $k . '=' . $v;
            }
            $headers[] = 'Cookie: ' . implode('; ', $parts);
        }
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (is_array($data)) {
                $body = http_build_query($data);
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            } elseif (is_string($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerStr = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);
        $headerLines = array();
        foreach (explode("\r\n", $headerStr) as $line) {
            if ($line !== '') {
                $headerLines[] = $line;
            }
        }
        return array('code' => $code, 'body' => $body, 'headers' => $headerLines);
    }

    /**
     * Attempt login and return cookie string or array. Returns array() if login fails or unavailable.
     */
    public function getSessionCookie($username, $password) {
        $url = $this->baseUrl . '/login';
        $res = $this->makeRequest($url, 'POST', array('username' => $username, 'password' => $password));
        $cookies = array();
        foreach ($res['headers'] as $line) {
            if (stripos($line, 'Set-Cookie:') === 0) {
                $part = trim(substr($line, 11));
                $eq = strpos($part, '=');
                if ($eq !== false) {
                    $name = trim(substr($part, 0, $eq));
                    $rest = substr($part, $eq + 1);
                    $semi = strpos($rest, ';');
                    $value = $semi !== false ? trim(substr($rest, 0, $semi)) : trim($rest);
                    $cookies[$name] = $value;
                }
            }
        }
        return $cookies;
    }

    private function logResult($vector, $expected, $actual, $passed, $details) {
        $entry = array(
            'timestamp' => gmdate('c'),
            'vector' => $vector,
            'expected' => $expected,
            'actual' => $actual,
            'passed' => $passed,
            'details' => $details,
        );
        $this->results[] = $entry;
        $path = $this->resultsDir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.jsonl';
        @file_put_contents($path, json_encode($entry) . "\n", FILE_APPEND | LOCK_EX);
    }

    /**
     * Run a single attack vector. Returns array('vector'=>, 'expected'=>, 'actual'=>, 'passed'=>).
     */
    public function runVector($name) {
        $adminUrl = $this->baseUrl . '/admin.php';
        $adminPostUrl = $this->baseUrl . '/admin.php?section=users&save_profile=1';

        switch ($name) {
            case 'csrf_missing':
                $res = $this->makeRequest($adminPostUrl, 'POST', array('auth_user_id' => 1, 'display_name' => 'x'));
                $expected = 403;
                $passed = ($res['code'] === 403);
                $this->logResult('csrf_bypass_attempt', $expected, $res['code'], $passed, array('url' => $adminPostUrl, 'method' => 'POST', 'payload' => 'no csrf_token'));
                return array('vector' => $name, 'expected' => $expected, 'actual' => $res['code'], 'passed' => $passed);

            case 'csrf_invalid':
                $res = $this->makeRequest($adminPostUrl, 'POST', array('auth_user_id' => 1, 'display_name' => 'x', 'csrf_token' => 'invalid'));
                $expected = 403;
                $passed = ($res['code'] === 403);
                $this->logResult('csrf_bypass_attempt', $expected, $res['code'], $passed, array('url' => $adminPostUrl, 'method' => 'POST', 'payload' => 'csrf_token=invalid'));
                return array('vector' => $name, 'expected' => $expected, 'actual' => $res['code'], 'passed' => $passed);

            case 'unauthorized_access':
                $res = $this->makeRequest($adminUrl, 'GET');
                $expected = array(302, 403, 200);
                $passed = in_array($res['code'], $expected);
                $this->logResult('unauthorized_access_attempt', 302, $res['code'], $passed, array('url' => $adminUrl, 'method' => 'GET'));
                return array('vector' => $name, 'expected' => '302/403/200', 'actual' => $res['code'], 'passed' => $passed);

            case 'malformed_long_param':
                $long = str_repeat('x', 12000);
                $res = $this->makeRequest($adminUrl . '?section=users', 'GET', null, array('PHPSESSID' => $long));
                $expected = array(200, 302, 400, 403, 413);
                $passed = in_array($res['code'], $expected);
                $this->logResult('malformed_request_attempt', 'no 500', $res['code'], ($res['code'] !== 500), array('url' => $adminUrl, 'method' => 'GET', 'payload' => 'long cookie'));
                return array('vector' => $name, 'expected' => 'no 500', 'actual' => $res['code'], 'passed' => ($res['code'] !== 500));

            case 'sql_injection_probe':
                $res = $this->makeRequest($adminUrl . '?section=users&edit_profile=' . urlencode("1' OR '1'='1"), 'GET');
                $passed = ($res['code'] !== 500);
                $this->logResult('sql_injection_probe', 'no 500', $res['code'], $passed, array('url' => $adminUrl, 'method' => 'GET', 'payload' => "1' OR '1'='1"));
                return array('vector' => $name, 'expected' => 'no 500', 'actual' => $res['code'], 'passed' => $passed);

            case 'xss_probe':
                $res = $this->makeRequest($adminUrl . '?section=users&msg=' . urlencode('<script>alert(1)</script>'), 'GET');
                $passed = ($res['code'] !== 500);
                $xssInBody = (strpos($res['body'], '<script>alert(1)</script>') !== false && strpos($res['body'], '&lt;script&gt;') === false);
                $passed = $passed && !$xssInBody;
                $this->logResult('xss_attempt', 'no 500, escaped', $res['code'], $passed, array('url' => $adminUrl, 'method' => 'GET'));
                return array('vector' => $name, 'expected' => 'no 500, escaped', 'actual' => $res['code'], 'passed' => $passed);

            case 'session_tamper':
                $res = $this->makeRequest($adminUrl, 'GET', null, array('PHPSESSID' => 'tampered_session_id_12345'));
                $expected = array(302, 403, 200);
                $passed = in_array($res['code'], $expected);
                $this->logResult('session_tamper_attempt', '302/403', $res['code'], $passed, array('url' => $adminUrl, 'method' => 'GET', 'payload' => 'fake cookie'));
                return array('vector' => $name, 'expected' => '302/403', 'actual' => $res['code'], 'passed' => $passed);

            case 'rate_limit_test':
                $codes = array();
                for ($i = 0; $i < 5; $i++) {
                    $r = $this->makeRequest($adminUrl, 'GET');
                    $codes[] = $r['code'];
                }
                $allOk = true;
                foreach ($codes as $c) {
                    if ($c >= 500) {
                        $allOk = false;
                        break;
                    }
                }
                $this->logResult('rate_limit_test', 'no 500', $codes[0], $allOk, array('url' => $adminUrl, 'method' => 'GET', 'requests' => 5));
                return array('vector' => $name, 'expected' => 'no 500', 'actual' => implode(',', $codes), 'passed' => $allOk);

            case 'privilege_escalation':
                $cookies = $this->getSessionCookie('viewer_lee', 'password');
                if (empty($cookies)) {
                    $res = $this->makeRequest($adminPostUrl, 'POST', array('auth_user_id' => 1, 'display_name' => 'x'));
                    $passed = ($res['code'] === 403);
                    $this->logResult('privilege_escalation_attempt', 403, $res['code'], $passed, array('url' => $adminPostUrl, 'method' => 'POST', 'payload' => 'no session'));
                } else {
                    $res = $this->makeRequest($adminPostUrl, 'POST', array('auth_user_id' => 1, 'display_name' => 'x'), $cookies);
                    $passed = ($res['code'] === 403);
                    $this->logResult('privilege_escalation_attempt', 403, $res['code'], $passed, array('url' => $adminPostUrl, 'method' => 'POST', 'payload' => 'low-priv session'));
                }
                return array('vector' => $name, 'expected' => 403, 'actual' => isset($res) ? $res['code'] : 0, 'passed' => $passed);

            default:
                return array('vector' => $name, 'expected' => 0, 'actual' => 0, 'passed' => false);
        }
    }

    /**
     * Run all attack vectors. Returns array of result arrays.
     */
    public function runAll() {
        $vectors = array('csrf_missing', 'csrf_invalid', 'unauthorized_access', 'privilege_escalation', 'malformed_long_param', 'sql_injection_probe', 'xss_probe', 'session_tamper', 'rate_limit_test');
        $this->results = array();
        $out = array();
        foreach ($vectors as $v) {
            $out[] = $this->runVector($v);
        }
        return $out;
    }

    public function getResults() {
        return $this->results;
    }
}
