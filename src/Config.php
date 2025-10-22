<?php

namespace Burst;

class Config
{
 
    /**
     * The config instance for the application.
     *
     * @var Config|null
     */
    private static ?Config $instance = null;

    /**
     * The config values are kept in this array.
     *
     * @var array
     */
    private array $settings = [];


    /**
     * The path for the config file. can be changed if you want to use a different location.
     */
    private const CONFIG_FILE_PATH = __DIR__ . '/../config.json';

    /**
     * Private constructor to prevent direct instantiation.
     * The config is loaded here when the class is first instantiated.
     */
    private function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Prevents cloning of the instance.
     */
    private function __clone() {}

    /**
     * Prevents unserializing/restoring the instance from a string.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * The public static method to get the single instance of the Config class.
     *
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Loads and parses the configuration from the JSON file.
     */
    private function loadConfig(): void
    {
        if (!file_exists(self::CONFIG_FILE_PATH)) {
            throw new \Exception("Config file not found: " . self::CONFIG_FILE_PATH);
            return;
        }

        $content = file_get_contents(self::CONFIG_FILE_PATH);
        $config = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decoding error: " . json_last_error_msg());
            return;
        }

        $this->settings = $config ?? [];
    }

    /**
     * Public static method to retrieve a configuration setting.
     *
     * @param string $key The key (e.g., 'app_name' or 'database.host')
     * @param mixed $default The default value to return if the key is not found
     * @return mixed
     */
    public static function get(string $key, $default = null): mixed
    {
        $instance = self::getInstance();
        $keys = explode('.', $key);
        $value = $instance->settings;

        foreach ($keys as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Return all the config values.
     *
     * @return array
     */
    public static function all(): array
    {
        return self::getInstance()->settings ?? [];
    }
}