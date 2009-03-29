<?php
require_once ('Fynd/Cache/IStorer.php');
require_once ('Fynd/Object.php');
/**
 * Cache the content in file system.
 * The cache file format:
 * [timestamp]\n
 * [ttl(second)]\n
 * [hits]\n
 * ['serialized']\n
 * \n
 * [cache content]
 *
 */
class Fynd_Cache_FileSystem extends Fynd_Object implements Fynd_Cache_IStorer
{
    protected $_cachePath;
    /**
     * 
     * @param string $key 
     * @param mixed $var 
     * @param int $ttl 
     * @return bool 
     * @see Fynd_Cache_IStorer::add()
     */
    public function add($key, $var, $ttl)
    {
        if(empty($key))
        {
            return false;
        }
        if(empty($var))
        {
            return false;
        }
        if(is_resource($var))
        {
            return false;
        }
        //FIXME:mybe a security problem.
        $cacheFilePath = $this->_cachePath . $key . ".cache";
        if(file_exists($cacheFilePath))
        {
            return false;
        }
        $cacheString = "";
        $serialized = false;
        if(is_scalar($var))
        {
            $cacheString = $var;
        }
        else
        {
            $cacheString = serialize($var);
            $serialized = true;
        }
        $cacheFileContent = $this->_createCacheFile(time(), $ttl, 0, $cacheString, $serialized);
        $fail = file_put_contents($cacheFilePath, $cacheFileContent, LOCK_EX);
        if($fail === false)
        {
            return false;
        }
        return true;
    }
    /**
     * 
     * @return bool Returns TRUE on success or FALSE on failure. 
     * @see Fynd_Cache_IStorer::clear()
     */
    public function clear()
    {
        return $this->_clearDir($this->_cachePath);
    }
    /**
     * 
     * @param string $key 
     * @return bool Returns TRUE on success or FALSE on failure. 
     * @see Fynd_Cache_IStorer::delete()
     */
    public function delete($key)
    {
        $cacheFilePath = $this->_cachePath . $key . ".cache";
        if(! file_exists($cacheFilePath))
        {
            return false;
        }
        $fail = @unlink($cacheFilePath);
        if(false === $fail)
        {
            return false;
        }
        return true;
    }
    /**
     * 
     * @param string $key 
     * @return mixed In failure will return FALSE.please use "===" 
     * @see Fynd_Cache_IStorer::fetch()
     */
    public function fetch($key)
    {
        $cacheFilePath = $this->_cachePath . $key . ".cache";
        if(! file_exists($cacheFilePath))
        {
            return false;
        }
        $lines = file($cacheFilePath);
        if(false === $lines)
        {
            return false;
        }
        $timestamp = intval(trim($lines[0]));
        $ttl = intval(trim($lines[1]));
        $now = time();
        if($ttl !== 0 && ($now - $timestamp) > $ttl)
        {
            $this->delete($key);
            return false;
        }
        $hits = intval(trim($lines[2]));
        $hits ++;
        $serialized = tirm($lines[3]);
        unset($lines[0]);
        unset($lines[1]);
        unset($lines[2]);
        unset($lines[3]);
        unset($lines[4]);
        $cacheContent = implode('', $lines);
        $cacheFileContent = $this->_createCacheFile($timestamp, $ttl, $hits, $cacheContent, ! empty($serialized));
        $fail = @file_put_contents($cacheFilePath, $cacheFileContent, LOCK_EX);
        if(false === $fail)
        {
            return false;
        }
        $cacheVar = null;
        if(! empty($serialized))
        {
            $cacheVar = unserialize($cacheContent);
            if(false === $cacheVar)
            {
                return false;
            }
        }
        else
        {
            $cacheVar = $cacheContent;
        }
        return $cacheVar;
    }
    /**
     * 
     * @see Fynd_Cache_IStorer::init()
     */
    public function init()
    {
        $this->_cachePath = Fynd_Env::getAppPath() . "caches/";
    }
    /**
     * 
     * @param string $key 
     * @param mixed $var 
     * @param int $ttl 
     * @return bool 
     * @see Fynd_Cache_IStorer::store()
     */
    public function store($key, $var, $ttl)
    {
        if(empty($key))
        {
            return false;
        }
        if(empty($var))
        {
            return false;
        }
        if(is_resource($var))
        {
            return false;
        }
        //FIXME:mybe a security problem.
        $cacheFilePath = $this->_cachePath . $key . ".cache";
        $cacheString = "";
        $serialized = false;
        if(is_scalar($var))
        {
            $cacheString = $var;
        }
        else
        {
            $cacheString = serialize($var);
            $serialized = true;
        }
        $cacheFileContent = $this->_createCacheFile(time(), $ttl, 0, $cacheString, $serialized);
        $fail = file_put_contents($cacheFilePath, $cacheFileContent, LOCK_EX);
        if($fail === false)
        {
            return false;
        }
        return true;
    }
    /**
     * @param int $timestamp
     * @param int $ttl
     * @param int $hits
     * @param string $var
     * @param bool $serialized
     * @return string
     */
    private function _createCacheFile($timestamp, $ttl, $hits, $var, $serialized)
    {
        $file = "";
        $file .= $timestamp . "\n";
        $file .= $ttl . "\n";
        $file .= $hits . "\n";
        if($serialized === true)
        {
            $file .= "serialized\n";
        }
        else
        {
            $file .= "\n";
        }
        $file .= $var;
        return $file;
    }
    /**
     * @param string $path
     * @return bool
     */
    private function _clearDir($path)
    {
        $dirObj = dir($path);
        $fail = false;
        while(false !== ($item = $dirObj->read()))
        {
            if('.' == $item || '..' == $item)
            {
                continue;
            }
            $itemFullPath = $path . $item;
            if(is_dir($itemFullPath))
            {
                $this->_clearDir($itemFullPath);
                $fail = @rmdir($itemFullPath);
            }
            else
            {
                $fail = @unlink($itemFullPath);
            }
        }
        $dirObj->close();
        if(false === $fail)
        {
            return false;
        }
        return true;
    }
}
?>