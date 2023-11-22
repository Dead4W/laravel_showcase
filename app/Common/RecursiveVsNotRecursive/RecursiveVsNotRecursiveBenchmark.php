<?php

namespace App\Common\ResursiveVsNotRecursive;

class RecursiveVsNotRecursiveBenchmark
{
    public function handle()
    {
        ini_set('memory_limit', '2048M');

        // Example tree memory peak usage: 97.14 MB
        $tree = $this->getDirectoryTree('.');

        /*
         * Пример $tree = ['a' => 'file', 'b' => ['c' => 'file']];
         * Глубина 1>=N>Infinity
         */

        $start = microtime(true);

        // optimized not recursive peak memory usage: 146 MB
        // optimized not recursive time usage: 0.33
        // $this->arrayToFlattenPathsNotRecursive($tree);

        // recursive peak memory usage: 162.01 MB
        // recursive time usage: 0.83
        // $this->arrayToFlattenPathsRecursive($tree);

        $duration = microtime(true) - $start;

        $peakMemoryUsage = memory_get_peak_usage(true);

        echo "Memory peak usage: " . $this->convertSizeToHuman($peakMemoryUsage) . PHP_EOL;
        echo "Time usage: " . $duration . PHP_EOL;
    }

    /**
     * Алгоритм нерекурсивного прохода
     * Работает через 1 цикл while
     * Если папка, тогда ложим в стек
     * При записи файла перемещаем указатель в массиве через next()
     * Если в элементе стека конец, тогда array_pop в стеке
     *
     * @param $tree
     * @return array
     */
    function arrayToFlattenPathsNotRecursive($tree): array
    {
        $stackTrees = [&$tree];
        $stackTreeIndex = 0;
        $dirPaths = [];
        $dirPathsPrecalc = '';

        $result = [];
        while (true) {
            $tmpTree = &$stackTrees[$stackTreeIndex];
            $dirPath = key($tmpTree);

            if ($dirPath === null) {
                $stackTreeIndex--;
                array_pop($dirPaths);
                $dirPathsPrecalc = implode('/', $dirPaths);

                if ($stackTreeIndex < 0) {
                    break;
                }

                next($stackTrees[$stackTreeIndex]);

                continue;
            }

            $dirTree = current($tmpTree);
            if ($dirTree === 'file') {
                if ($dirPathsPrecalc === '') {
                    $result[] = $dirPath;
                } else {
                    $result[] = $dirPathsPrecalc . '/' . $dirPath;
                }

                next($tmpTree);
                continue;
            }

            $dirPaths[] = $dirPath;
            $dirPathsPrecalc = implode('/', $dirPaths);
            $stackTreeIndex++;
            $stackTrees[$stackTreeIndex] = $dirTree;

        }

        return $result;
    }

    function arrayToFlattenPathsRecursive($array, $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->arrayToFlattenPathsRecursive($value, $prefix . $key . '/'));
            } else {
                $result[] = $prefix . $key;
            }
        }
        return $result;
    }

    function getDirectoryTree($path)
    {
        $files = [];
        if (is_dir($path)) {
            try {
                $dir = opendir($path);
            } catch (\Throwable) {
                return $files;
            }
            while (($file = readdir($dir)) !== false) {
                if ($file != "." && $file != "..") {
                    if (is_dir($path . '/' . $file)) {
                        $files[$file] = $this->getDirectoryTree($path . '/' . $file);
                    } else {
                        $files[$file] = 'file';
                    }
                }
            }
            closedir($dir);
        }
        return $files;
    }

    function convertSizeToHuman($size)
    {
        $unit = ['bytes', 'KB', 'MB', 'GB', 'TB'];
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }
}
