<?php
class DataCache {
    private $cachePath;
    private $apcuEnabled;

    public function __construct($cachePath) {
        $this->apcuEnabled = extension_loaded('apcu');

        if ($this->apcuEnabled) {
            $this->cachePath = null;
        } else {
            if (!empty($cachePath)) {
                $this->cachePath = 'assets/cache/' . $cachePath;
                if (!is_dir($this->cachePath)) {
                    mkdir($this->cachePath, 0777, true);
                }
            }
        }
    }

    public function set($key, $value, $method = 'file') {
        if (($method === 'apcu' && $this->apcuEnabled) || $this->apcuEnabled) {
            $this->setApcuCache($key, $value);
        } else {
            if ($method === 'file' && $this->cachePath !== null) {
                $this->setFileCache($key, $value);
            }
        }
    }

    public function get($key, $method = 'file') {
        if (($method === 'apcu' && $this->apcuEnabled) || $this->apcuEnabled) {
            $value = $this->getApcuCache($key);
            if ($value !== false) {
                return $value;
            }
        } else {
            if ($method === 'file' && $this->cachePath !== null) {
                $value = $this->getFileCache($key);
                if ($value !== null) {
                    if (($method === 'apcu' && $this->apcuEnabled) || $this->apcuEnabled) {
                        $this->setApcuCache($key, $value);
                    }
                    return $value;
                }
            }
        }

        return null;
    }

    public function remove($key, $method = 'file') {
        if (($method === 'apcu' && $this->apcuEnabled) || $this->apcuEnabled) {
            $this->removeApcuCache($key);
        } else {
            if ($method === 'file' && $this->cachePath !== null) {
                $this->removeFileCache($key);
            }
        }
    }

    private function setFileCache($key, $value) {
        $filePath = $this->getFilePath($key);
        file_put_contents($filePath, serialize($value));
    }

    private function getFileCache($key) {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath)) {
            return unserialize(file_get_contents($filePath));
        }
        return null;
    }

    private function removeFileCache($key) {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function setApcuCache($key, $value) {
        if ($this->apcuEnabled) {
            apcu_store($key, $value);
        }
    }

    private function getApcuCache($key) {
        if ($this->apcuEnabled) {
            return apcu_fetch($key);
        }
        return false;
    }

    private function removeApcuCache($key) {
        if ($this->apcuEnabled) {
            apcu_delete($key);
        }
    }

    private function getFilePath($key) {
        return $this->cachePath . '/' . md5($key) . '.cache';
    }
}