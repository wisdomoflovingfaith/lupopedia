<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "app/Services/ApiProviderChainService.php"
#   web_path: "https://www.lupopedia.com/lupopedia/app/Services/ApiProviderChainService.php"
#   status: "active"
#   when_updated: "20260417075457"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/apiproviderchainservice-php.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/apiproviderchainservice-php"
#   artifact_type: implementation
#   artifact_kind: service
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: null
#   content_slug: "apiproviderchainservice-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "ApiProviderChainService.php — runtime provider chain and spend tracking service"
#   summary: "Service for BYOK runtime provider ordering, fallback, path-safe config loading, and monthly spend tracking for independent installs."
# ---------------------------------------------------------------------
/**
 * API Provider Chain Service
 *
 * Runtime config loader + provider fallback contract for BYOK installs.
 * This class resolves provider order, validates provider availability,
 * and picks the next provider after rate-limit or transient failures.
 */
/*
 * Filesystem Rule (canonical):
 * - lupopedia-config.php lives INSIDE the web-accessible directory (protected 0600 + .htaccess deny).
 * - All other files, including lupo-memory/budgets/ and provider-usage/, live ABOVE the web root.
 * - This service forces memory_path / channels_path above DOCUMENT_ROOT and protects spend logs.
 */

namespace App\Services;

class ApiProviderChainService
{
    private $runtimeConfig = array();
    private $appBasePath = '';

    public function __construct($runtimeConfig = null)
    {
        $this->appBasePath = $this->resolveAppBasePath();
        $this->runtimeConfig = $this->loadRuntimeConfig($runtimeConfig);
    }

    public function getRuntimeConfigSanitized()
    {
        $sanitized = $this->runtimeConfig;
        $providers = isset($sanitized['providers']) && is_array($sanitized['providers'])
            ? $sanitized['providers']
            : array();

        foreach ($providers as $provider => $providerConfig) {
            if (isset($providerConfig['key'])) {
                $sanitized['providers'][$provider]['key'] = $this->maskApiKey($providerConfig['key']);
            }
            if (isset($sanitized['providers'][$provider]['api_key'])) {
                unset($sanitized['providers'][$provider]['api_key']);
            }
        }

        return $sanitized;
    }

    public function getProviderOrder($requestClass)
    {
        $classKey = is_string($requestClass) && $requestClass !== '' ? $requestClass : 'default';
        $requestClassOrder = isset($this->runtimeConfig['request_class_order']) && is_array($this->runtimeConfig['request_class_order'])
            ? $this->runtimeConfig['request_class_order']
            : array();

        if (isset($requestClassOrder[$classKey]) && is_array($requestClassOrder[$classKey])) {
            return $requestClassOrder[$classKey];
        }

        return isset($this->runtimeConfig['provider_order']) && is_array($this->runtimeConfig['provider_order'])
            ? $this->runtimeConfig['provider_order']
            : array();
    }

    public function getPrimaryProvider($requestClass)
    {
        $order = $this->getProviderOrder($requestClass);
        $count = count($order);
        $index = 0;

        while ($index < $count) {
            $provider = $order[$index];
            if ($this->providerIsEnabled($provider) && $this->providerHasKey($provider)) {
                return $provider;
            }
            $index++;
        }

        return null;
    }

    public function getBudgetSnapshot()
    {
        $spendData = $this->loadMonthlySpendData();
        return array(
            'current_ym' => gmdate('Ym'),
            'total_usd' => isset($spendData['total_usd']) ? (float) $spendData['total_usd'] : 0.0,
            'provider_spend' => isset($spendData['provider_spend']) && is_array($spendData['provider_spend']) ? $spendData['provider_spend'] : array(),
            'monthly_budget_cap_usd' => isset($this->runtimeConfig['monthly_budget_cap_usd']) ? (float) $this->runtimeConfig['monthly_budget_cap_usd'] : 0.0,
        );
    }

    public function trackSpend($provider, $estimatedUsd, $details = array())
    {
        $providerKey = is_string($provider) ? strtolower(trim($provider)) : '';
        $amount = is_numeric($estimatedUsd) ? (float) $estimatedUsd : 0.0;
        if ($providerKey === '' || $amount <= 0.0) {
            return false;
        }

        $data = $this->loadMonthlySpendData();
        if (!isset($data['provider_spend']) || !is_array($data['provider_spend'])) {
            $data['provider_spend'] = array();
        }
        if (!isset($data['provider_spend'][$providerKey])) {
            $data['provider_spend'][$providerKey] = 0.0;
        }
        $data['provider_spend'][$providerKey] = (float) $data['provider_spend'][$providerKey] + $amount;
        $data['total_usd'] = isset($data['total_usd']) ? (float) $data['total_usd'] + $amount : $amount;
        $data['updated_ymdhis'] = gmdate('YmdHis');

        if (!$this->saveMonthlySpendData($data)) {
            return false;
        }

        $record = array(
            'provider' => $providerKey,
            'estimated_usd' => $amount,
            'details' => is_array($details) ? $details : array(),
            'logged_ymdhis' => gmdate('YmdHis'),
        );
        $this->appendUsageLog($record);
        return true;
    }

    public function getNextProvider($attemptedProviders, $requestClass, $errorContext, $currentSpendUsd)
    {
        if (!$this->shouldFallback($errorContext)) {
            return null;
        }

        $attempted = is_array($attemptedProviders) ? $attemptedProviders : array();
        $order = $this->getProviderOrder($requestClass);
        $count = count($order);
        $index = 0;

        while ($index < $count) {
            $provider = $order[$index];
            $alreadyAttempted = in_array($provider, $attempted, true);

            if (!$alreadyAttempted
                && $this->providerIsEnabled($provider)
                && $this->providerHasKey($provider)
                && $this->providerAllowedAtSpend($provider, $currentSpendUsd)) {
                return $provider;
            }

            $index++;
        }

        return null;
    }

    public function getProviderApiKey($provider)
    {
        $providerKey = is_string($provider) ? strtolower($provider) : '';

        if ($providerKey === '') {
            return '';
        }

        if (!isset($this->runtimeConfig['providers'][$providerKey])) {
            return '';
        }

        $providerConfig = $this->runtimeConfig['providers'][$providerKey];
        if (!is_array($providerConfig)) {
            return '';
        }

        if (isset($providerConfig['key']) && trim((string) $providerConfig['key']) !== '') {
            return trim((string) $providerConfig['key']);
        }
        if (isset($providerConfig['api_key']) && trim((string) $providerConfig['api_key']) !== '') {
            return trim((string) $providerConfig['api_key']);
        }

        return '';
    }

    public function buildAttemptRecord($provider, $result, $reason, $statusCode)
    {
        return array(
            'provider' => (string) $provider,
            'result' => (string) $result,
            'reason' => (string) $reason,
            'status_code' => (int) $statusCode,
            'attempted_ymdhis' => gmdate('YmdHis'),
            'estimated_usd' => 0.0,
            'retry_after_seconds' => 0,
        );
    }

    private function loadRuntimeConfig($runtimeConfig)
    {
        if (is_array($runtimeConfig)) {
            return $this->normalizeRuntimeConfig($runtimeConfig);
        }

        if (isset($GLOBALS['LUPO_API_PROVIDER_CONFIG']) && is_array($GLOBALS['LUPO_API_PROVIDER_CONFIG'])) {
            return $this->normalizeRuntimeConfig($GLOBALS['LUPO_API_PROVIDER_CONFIG']);
        }

        $configFromFile = $this->loadConfigFromWebRootFile();
        if (is_array($configFromFile)) {
            return $this->normalizeRuntimeConfig($configFromFile);
        }

        return $this->normalizeRuntimeConfig($this->buildDefaultRuntimeConfig());
    }

    private function buildDefaultRuntimeConfig()
    {
        return array(
            'provider_order' => array('gemini', 'deepseek', 'groq'),
            'request_class_order' => array(
                'default' => array('gemini', 'deepseek', 'groq'),
                'complex' => array('deepseek', 'gemini', 'openai'),
                'audit' => array('deepseek', 'gemini', 'openai'),
            ),
            'fallback_order' => array('gemini', 'deepseek', 'groq'),
            'monthly_budget_cap_usd' => 45.0,
            'premium_provider_block_threshold_usd' => 40.0,
            'premium_providers' => array('openai', 'anthropic'),
            'config_version' => '2026.04',
            'memory_path' => null,
            'channels_path' => null,
            'providers' => array(
                'gemini' => array(
                    'enabled' => true,
                    'key' => '',
                    'budget' => 15.0,
                    'name' => 'Google Gemini',
                ),
                'deepseek' => array(
                    'enabled' => true,
                    'key' => '',
                    'budget' => 15.0,
                    'name' => 'DeepSeek',
                ),
                'groq' => array(
                    'enabled' => true,
                    'key' => '',
                    'budget' => 15.0,
                    'name' => 'Groq',
                ),
                'openai' => array(
                    'enabled' => false,
                    'key' => '',
                    'budget' => 15.0,
                    'name' => 'OpenAI',
                ),
                'anthropic' => array(
                    'enabled' => false,
                    'key' => '',
                    'budget' => 15.0,
                    'name' => 'Anthropic',
                ),
            ),
        );
    }

    private function normalizeRuntimeConfig($config)
    {
        $normalized = is_array($config) ? $config : array();

        if (!isset($normalized['provider_order']) || !is_array($normalized['provider_order'])) {
            if (isset($normalized['fallback_order']) && is_array($normalized['fallback_order'])) {
                $normalized['provider_order'] = $normalized['fallback_order'];
            } else {
                $normalized['provider_order'] = array('gemini', 'deepseek', 'groq');
            }
        }

        if (!isset($normalized['request_class_order']) || !is_array($normalized['request_class_order'])) {
            $normalized['request_class_order'] = array(
                'default' => $normalized['provider_order'],
            );
        }

        if (!isset($normalized['providers']) || !is_array($normalized['providers'])) {
            $normalized['providers'] = array();
        }
        $normalizedProviders = array();
        foreach ($normalized['providers'] as $providerKey => $providerConfig) {
            $providerSlug = is_string($providerKey) ? strtolower(trim($providerKey)) : '';
            if ($providerSlug === '') {
                continue;
            }
            if (!is_array($providerConfig)) {
                $normalizedProviders[$providerSlug] = array('enabled' => false, 'key' => '', 'budget' => 15.0);
                continue;
            }
            $keyValue = '';
            if (isset($providerConfig['key']) && trim((string) $providerConfig['key']) !== '') {
                $keyValue = trim((string) $providerConfig['key']);
            } elseif (isset($providerConfig['api_key']) && trim((string) $providerConfig['api_key']) !== '') {
                $keyValue = trim((string) $providerConfig['api_key']);
            }
            $normalizedProviders[$providerSlug] = array(
                'enabled' => isset($providerConfig['enabled']) ? (bool) $providerConfig['enabled'] : ($keyValue !== ''),
                'key' => $keyValue,
                'budget' => isset($providerConfig['budget']) ? (float) $providerConfig['budget'] : 15.0,
                'name' => isset($providerConfig['name']) ? trim((string) $providerConfig['name']) : (isset($providerConfig['display_name']) ? trim((string) $providerConfig['display_name']) : ucwords(str_replace('_', ' ', $providerSlug))),
            );
        }
        $normalized['providers'] = $normalizedProviders;

        $normalized['provider_order'] = $this->normalizeProviderOrder($normalized['provider_order'], $normalized['providers']);
        if (isset($normalized['fallback_order']) && is_array($normalized['fallback_order'])) {
            $normalized['fallback_order'] = $this->normalizeProviderOrder($normalized['fallback_order'], $normalized['providers']);
        } else {
            $normalized['fallback_order'] = $normalized['provider_order'];
        }
        if (isset($normalized['request_class_order']) && is_array($normalized['request_class_order'])) {
            foreach ($normalized['request_class_order'] as $classKey => $order) {
                if (!is_array($order)) {
                    $normalized['request_class_order'][$classKey] = $normalized['provider_order'];
                    continue;
                }
                $normalized['request_class_order'][$classKey] = $this->normalizeProviderOrder($order, $normalized['providers']);
            }
        }
        if (!isset($normalized['request_class_order']['default']) || empty($normalized['request_class_order']['default'])) {
            $normalized['request_class_order']['default'] = $normalized['provider_order'];
        }

        if (!isset($normalized['premium_providers']) || !is_array($normalized['premium_providers'])) {
            $normalized['premium_providers'] = array('openai', 'anthropic');
        }
        $normalized['premium_providers'] = $this->normalizeProviderOrder($normalized['premium_providers'], $normalized['providers'], false);

        if (!isset($normalized['monthly_budget_cap_usd'])) {
            $normalized['monthly_budget_cap_usd'] = 45.0;
        }

        if (!isset($normalized['premium_provider_block_threshold_usd'])) {
            $normalized['premium_provider_block_threshold_usd'] = 40.0;
        }

        $normalized['memory_path'] = $this->resolveStoragePath(
            isset($normalized['memory_path']) ? $normalized['memory_path'] : null,
            $this->appBasePath . DIRECTORY_SEPARATOR . 'lupo-memory'
        );
        $normalized['channels_path'] = $this->resolveStoragePath(
            isset($normalized['channels_path']) ? $normalized['channels_path'] : null,
            $this->appBasePath . DIRECTORY_SEPARATOR . 'lupo-channels'
        );

        return $normalized;
    }

    private function providerIsEnabled($provider)
    {
        $providerKey = is_string($provider) ? strtolower($provider) : '';

        if ($providerKey === '') {
            return false;
        }

        if (!isset($this->runtimeConfig['providers'][$providerKey])) {
            return false;
        }

        $providerConfig = $this->runtimeConfig['providers'][$providerKey];
        if (!is_array($providerConfig)) {
            return false;
        }

        if (!isset($providerConfig['enabled'])) {
            return true;
        }

        return (bool) $providerConfig['enabled'];
    }

    private function providerHasKey($provider)
    {
        $apiKey = $this->getProviderApiKey($provider);
        return $apiKey !== '';
    }

    private function providerAllowedAtSpend($provider, $currentSpendUsd)
    {
        $providerKey = is_string($provider) ? strtolower($provider) : '';
        if ($providerKey === '') {
            return false;
        }

        $snapshot = $this->loadMonthlySpendData();
        $providerSpendStored = isset($snapshot['provider_spend'][$providerKey]) ? (float) $snapshot['provider_spend'][$providerKey] : 0.0;
        $providerSpendInput = $this->extractProviderSpend($providerKey, $currentSpendUsd);
        $spend = max($providerSpendStored, $providerSpendInput);

        $totalSpendStored = isset($snapshot['total_usd']) ? (float) $snapshot['total_usd'] : 0.0;
        $totalSpendInput = $this->extractTotalSpend($currentSpendUsd);
        $totalSpend = max($totalSpendStored, $totalSpendInput);

        $monthlyBudgetCap = isset($this->runtimeConfig['monthly_budget_cap_usd'])
            ? (float) $this->runtimeConfig['monthly_budget_cap_usd']
            : 0.0;
        if ($monthlyBudgetCap > 0.0 && $totalSpend >= $monthlyBudgetCap) {
            return false;
        }

        $premiumProviders = isset($this->runtimeConfig['premium_providers']) && is_array($this->runtimeConfig['premium_providers'])
            ? $this->runtimeConfig['premium_providers']
            : array();

        $isPremium = in_array($providerKey, $premiumProviders, true);
        if (!$isPremium) {
            return true;
        }

        $threshold = isset($this->runtimeConfig['premium_provider_block_threshold_usd'])
            ? (float) $this->runtimeConfig['premium_provider_block_threshold_usd']
            : 40.0;
        if ($spend >= $threshold) {
            return false;
        }

        if (!isset($this->runtimeConfig['providers'][$providerKey]) || !is_array($this->runtimeConfig['providers'][$providerKey])) {
            return true;
        }
        $providerBudget = isset($this->runtimeConfig['providers'][$providerKey]['budget'])
            ? (float) $this->runtimeConfig['providers'][$providerKey]['budget']
            : 0.0;
        if ($providerBudget <= 0.0) {
            return true;
        }

        return $spend < $providerBudget;
    }

    private function extractProviderSpend($providerKey, $currentSpendUsd)
    {
        if (is_array($currentSpendUsd)) {
            if (isset($currentSpendUsd['provider_spend']) && is_array($currentSpendUsd['provider_spend']) && isset($currentSpendUsd['provider_spend'][$providerKey])) {
                return is_numeric($currentSpendUsd['provider_spend'][$providerKey]) ? (float) $currentSpendUsd['provider_spend'][$providerKey] : 0.0;
            }
            if (isset($currentSpendUsd[$providerKey])) {
                return is_numeric($currentSpendUsd[$providerKey]) ? (float) $currentSpendUsd[$providerKey] : 0.0;
            }
            if (isset($currentSpendUsd['total'])) {
                return is_numeric($currentSpendUsd['total']) ? (float) $currentSpendUsd['total'] : 0.0;
            }
            return 0.0;
        }

        return is_numeric($currentSpendUsd) ? (float) $currentSpendUsd : 0.0;
    }

    private function extractTotalSpend($currentSpendUsd)
    {
        if (is_array($currentSpendUsd)) {
            if (isset($currentSpendUsd['total'])) {
                return is_numeric($currentSpendUsd['total']) ? (float) $currentSpendUsd['total'] : 0.0;
            }
            return 0.0;
        }

        return is_numeric($currentSpendUsd) ? (float) $currentSpendUsd : 0.0;
    }

    private function shouldFallback($errorContext)
    {
        $statusCode = 0;
        $errorCode = '';
        $retryAfterSeconds = 0;

        if (is_array($errorContext)) {
            if (isset($errorContext['status_code'])) {
                $statusCode = (int) $errorContext['status_code'];
            }
            if (isset($errorContext['error_code'])) {
                $errorCode = strtolower((string) $errorContext['error_code']);
            }
            if (isset($errorContext['retry_after_seconds'])) {
                $retryAfterSeconds = (int) $errorContext['retry_after_seconds'];
            } elseif (isset($errorContext['retry_after'])) {
                $retryAfterSeconds = (int) $errorContext['retry_after'];
            }
        }

        if ($statusCode === 429 && $retryAfterSeconds > 0 && $retryAfterSeconds <= 2) {
            return false;
        }
        if ($statusCode === 429 || $statusCode === 408 || $statusCode === 500 || $statusCode === 502 || $statusCode === 503 || $statusCode === 504) {
            return true;
        }

        if ($errorCode === 'timeout' || $errorCode === 'network_error' || $errorCode === 'rate_limited') {
            return true;
        }

        return false;
    }

    private function loadConfigFromWebRootFile()
    {
        if (defined('LUPOPEDIA_CONFIG_LOADED') && LUPOPEDIA_CONFIG_LOADED && isset($GLOBALS['LUPO_API_PROVIDER_CONFIG']) && is_array($GLOBALS['LUPO_API_PROVIDER_CONFIG'])) {
            return $GLOBALS['LUPO_API_PROVIDER_CONFIG'];
        }

        $configPath = $this->detectConfigPath();
        if ($configPath === '') {
            return null;
        }

        $loaded = @include $configPath;
        if (is_array($loaded)) {
            return $loaded;
        }
        if (isset($GLOBALS['LUPO_API_PROVIDER_CONFIG']) && is_array($GLOBALS['LUPO_API_PROVIDER_CONFIG'])) {
            return $GLOBALS['LUPO_API_PROVIDER_CONFIG'];
        }

        return null;
    }

    private function detectConfigPath()
    {
        $paths = array();
        if (defined('LUPOPEDIA_CONFIG_PATH')) {
            $paths[] = LUPOPEDIA_CONFIG_PATH;
        }
        if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== '') {
            $paths[] = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, (string) $_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
        }
        $paths[] = $this->appBasePath . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
        $paths[] = dirname($this->appBasePath) . DIRECTORY_SEPARATOR . 'lupopedia-config.php';

        $seen = array();
        foreach ($paths as $candidate) {
            if (!is_string($candidate) || $candidate === '' || isset($seen[$candidate])) {
                continue;
            }
            $seen[$candidate] = true;
            if (@is_file($candidate) && @is_readable($candidate)) {
                return $candidate;
            }
        }
        return '';
    }

    private function resolveAppBasePath()
    {
        if (defined('LUPOPEDIA_PATH') && LUPOPEDIA_PATH !== '') {
            return rtrim(str_replace('\\', DIRECTORY_SEPARATOR, LUPOPEDIA_PATH), DIRECTORY_SEPARATOR);
        }
        if (defined('LUPOPEDIA_ABSPATH') && LUPOPEDIA_ABSPATH !== '') {
            return rtrim(str_replace('\\', DIRECTORY_SEPARATOR, LUPOPEDIA_ABSPATH), DIRECTORY_SEPARATOR);
        }
        return rtrim(str_replace('\\', DIRECTORY_SEPARATOR, dirname(dirname(__DIR__))), DIRECTORY_SEPARATOR);
    }

    private function resolveStoragePath($pathValue, $defaultPath)
    {
        $defaultNormalized = $this->normalizeAbsolutePath($defaultPath);
        if (!is_string($pathValue) || trim($pathValue) === '' || strtolower(trim($pathValue)) === 'null') {
            return $this->ensureTrailingSlash($defaultNormalized);
        }

        $path = trim((string) $pathValue);
        if (strpos($path, '__DIR__') !== false) {
            $path = str_replace('__DIR__', $this->appBasePath, $path);
        }
        if (!$this->isAbsolutePath($path)) {
            $path = $this->appBasePath . DIRECTORY_SEPARATOR . $path;
        }

        $normalized = $this->normalizeAbsolutePath($path);
        $docRoot = $this->getDocumentRootPath();
        if ($docRoot !== '' && $this->pathStartsWith($normalized, $docRoot)) {
            return $this->ensureTrailingSlash($defaultNormalized);
        }

        return $this->ensureTrailingSlash($normalized);
    }

    private function normalizeProviderOrder($order, $providers, $requireKnown = true)
    {
        $normalized = array();
        $known = is_array($providers) ? array_keys($providers) : array();
        if (!is_array($order)) {
            $order = array();
        }
        foreach ($order as $provider) {
            $slug = is_string($provider) ? strtolower(trim($provider)) : '';
            if ($slug === '' || in_array($slug, $normalized, true)) {
                continue;
            }
            if ($requireKnown && !isset($providers[$slug])) {
                continue;
            }
            $normalized[] = $slug;
        }
        if (empty($normalized)) {
            foreach ($known as $slug) {
                $normalized[] = $slug;
            }
        }
        return $normalized;
    }

    private function loadMonthlySpendData()
    {
        $filePath = $this->getSpendFilePath();
        $data = array(
            'provider_spend' => array(),
            'total_usd' => 0.0,
            'updated_ymdhis' => gmdate('YmdHis'),
        );

        if (!@is_file($filePath)) {
            return $data;
        }
        $raw = '';
        $fh = @fopen($filePath, 'rb');
        if ($fh === false) {
            return $data;
        }
        if (function_exists('flock')) {
            @flock($fh, LOCK_SH);
        }
        while (!feof($fh)) {
            $chunk = fread($fh, 8192);
            if ($chunk === false) {
                break;
            }
            $raw .= $chunk;
        }
        if (function_exists('flock')) {
            @flock($fh, LOCK_UN);
        }
        @fclose($fh);
        if ($raw === false || trim($raw) === '') {
            return $data;
        }
        $decoded = @json_decode($raw, true);
        if (!is_array($decoded)) {
            return $data;
        }
        if (isset($decoded['provider_spend']) && is_array($decoded['provider_spend'])) {
            $data['provider_spend'] = $decoded['provider_spend'];
        }
        if (isset($decoded['total_usd'])) {
            $data['total_usd'] = (float) $decoded['total_usd'];
        }
        if (isset($decoded['updated_ymdhis'])) {
            $data['updated_ymdhis'] = (string) $decoded['updated_ymdhis'];
        }
        return $data;
    }

    private function saveMonthlySpendData($data)
    {
        $filePath = $this->getSpendFilePath();
        $directory = dirname($filePath);
        if (!@is_dir($directory) && !@mkdir($directory, 0755, true)) {
            return false;
        }
        $this->ensureDirectoryProtected($directory);
        $encoded = @json_encode($data);
        if (!is_string($encoded)) {
            return false;
        }
        $fh = @fopen($filePath, 'c+');
        if ($fh === false) {
            return false;
        }
        if (function_exists('flock') && !@flock($fh, LOCK_EX)) {
            @fclose($fh);
            return false;
        }
        @ftruncate($fh, 0);
        @rewind($fh);
        if (@fwrite($fh, $encoded) === false) {
            if (function_exists('flock')) {
                @flock($fh, LOCK_UN);
            }
            @fclose($fh);
            return false;
        }
        if (function_exists('fflush')) {
            @fflush($fh);
        }
        if (function_exists('flock')) {
            @flock($fh, LOCK_UN);
        }
        @fclose($fh);
        return true;
    }

    private function appendUsageLog($record)
    {
        $logDir = $this->getUsageLogDirectory();
        if (!@is_dir($logDir) && !@mkdir($logDir, 0755, true)) {
            return false;
        }
        $this->ensureDirectoryProtected($logDir);
        $logPath = $logDir . DIRECTORY_SEPARATOR . gmdate('Ymd') . '.log';
        $line = @json_encode($record);
        if (!is_string($line)) {
            return false;
        }
        $fh = @fopen($logPath, 'ab');
        if ($fh === false) {
            return false;
        }
        if (function_exists('flock')) {
            @flock($fh, LOCK_EX);
        }
        $ok = (@fwrite($fh, $line . "\n") !== false);
        if (function_exists('fflush')) {
            @fflush($fh);
        }
        if (function_exists('flock')) {
            @flock($fh, LOCK_UN);
        }
        @fclose($fh);
        return $ok;
    }

    private function getSpendFilePath()
    {
        $budgetDir = $this->ensureTrailingSlash($this->runtimeConfig['memory_path']) . 'budgets' . DIRECTORY_SEPARATOR . gmdate('Ym');
        $this->ensureDirectoryProtected($budgetDir);
        return $budgetDir . DIRECTORY_SEPARATOR . 'spend.json';
    }

    private function getUsageLogDirectory()
    {
        $logDir = $this->ensureTrailingSlash($this->runtimeConfig['memory_path']) . 'provider-usage' . DIRECTORY_SEPARATOR . gmdate('Ym');
        $this->ensureDirectoryProtected($logDir);
        return $logDir;
    }

    private function ensureDirectoryProtected($directory)
    {
        $dir = is_string($directory) ? rtrim($directory, DIRECTORY_SEPARATOR) : '';
        if ($dir === '') {
            return;
        }
        if (!@is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (!@is_dir($dir) || !@is_writable($dir)) {
            return;
        }

        $htaccessPath = $dir . DIRECTORY_SEPARATOR . '.htaccess';
        $rule = "<Files \"*\">\n    Require all denied\n    # Legacy Apache 2.2 fallback:\n    # Order Allow,Deny\n    # Deny from all\n</Files>\n";
        $existing = @file_get_contents($htaccessPath);
        if ($existing === false) {
            @file_put_contents($htaccessPath, $rule);
            return;
        }
        if (strpos($existing, 'Require all denied') === false && strpos($existing, 'Deny from all') === false) {
            @file_put_contents($htaccessPath, rtrim($existing) . "\n\n" . $rule);
        }
    }

    private function getDocumentRootPath()
    {
        if (!isset($_SERVER['DOCUMENT_ROOT']) || $_SERVER['DOCUMENT_ROOT'] === '') {
            return '';
        }
        return $this->normalizeAbsolutePath((string) $_SERVER['DOCUMENT_ROOT']);
    }

    private function normalizeAbsolutePath($path)
    {
        return rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    }

    private function ensureTrailingSlash($path)
    {
        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    private function isAbsolutePath($path)
    {
        if (!is_string($path) || $path === '') {
            return false;
        }
        return (bool) preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) || strpos($path, '/') === 0;
    }

    private function pathStartsWith($path, $prefix)
    {
        $p = strtolower($this->normalizeAbsolutePath($path));
        $x = strtolower($this->normalizeAbsolutePath($prefix));
        if ($x === '') {
            return false;
        }
        if ($p === $x) {
            return true;
        }
        return strpos($p, $x . DIRECTORY_SEPARATOR) === 0;
    }

    private function maskApiKey($apiKey)
    {
        $raw = trim((string) $apiKey);
        $length = strlen($raw);

        if ($length <= 8) {
            return '***';
        }

        return substr($raw, 0, 4) . '...' . substr($raw, $length - 4, 4);
    }
}
