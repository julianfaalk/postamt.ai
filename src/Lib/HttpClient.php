<?php
/**
 * Simple HTTP client for API requests
 */

class HttpClient
{
    private array $defaultHeaders = [];
    private int $timeout = 30;

    public function setDefaultHeaders(array $headers): self
    {
        $this->defaultHeaders = $headers;
        return $this;
    }

    public function setTimeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }

    public function get(string $url, array $headers = []): array
    {
        return $this->request('GET', $url, null, $headers);
    }

    public function post(string $url, $body = null, array $headers = []): array
    {
        return $this->request('POST', $url, $body, $headers);
    }

    public function put(string $url, $body = null, array $headers = []): array
    {
        return $this->request('PUT', $url, $body, $headers);
    }

    public function delete(string $url, array $headers = []): array
    {
        return $this->request('DELETE', $url, null, $headers);
    }

    public function request(string $method, string $url, $body = null, array $headers = []): array
    {
        $ch = curl_init();

        $allHeaders = array_merge($this->defaultHeaders, $headers);
        $headerStrings = [];
        foreach ($allHeaders as $key => $value) {
            $headerStrings[] = "{$key}: {$value}";
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headerStrings,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
        ]);

        if ($body !== null) {
            if (is_array($body)) {
                $body = json_encode($body);
                $headerStrings[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headerStrings);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("HTTP request failed: {$error}");
        }

        $decoded = json_decode($response, true);

        return [
            'status' => $httpCode,
            'body' => $decoded ?? $response,
            'raw' => $response,
        ];
    }

    public function uploadFile(string $url, string $filePath, string $fieldName, array $headers = []): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: {$filePath}");
        }

        $ch = curl_init();

        $allHeaders = array_merge($this->defaultHeaders, $headers);
        $headerStrings = [];
        foreach ($allHeaders as $key => $value) {
            $headerStrings[] = "{$key}: {$value}";
        }

        $cfile = new CURLFile($filePath);

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120, // Longer timeout for uploads
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headerStrings,
            CURLOPT_POSTFIELDS => [$fieldName => $cfile],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Upload failed: {$error}");
        }

        return [
            'status' => $httpCode,
            'body' => json_decode($response, true) ?? $response,
        ];
    }
}
