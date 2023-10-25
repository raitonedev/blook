<?php

namespace Raitone\Blook\Services;

use Illuminate\Support\Facades\File;

class FileContextService{

    const MIX_CONFIG_FILE = "webpack.mix.js";
    const VITE_CONFIG_FILE = "vite.config.js";
    const WEBPACK_CONFIG_FILE = "webpack.config.js";

    const FILE_BUNDLER_MAPPING = [
        self::WEBPACK_CONFIG_FILE => "Webpack",
        self::MIX_CONFIG_FILE => "Webpack (Laravel Mix)",
        self::VITE_CONFIG_FILE => "Vite",
    ];

    public bool $hasDetectedConfig = false;
    public bool $hasDetectedPort = false;
    public string $bundler = "";
    public string $bundlerConfigFile = "";
    public string $bundlerConfig = "";
    public string $bundlerCustomPort = "";


    public function __construct(){

        $this->detectConfigFile();

        if($this->hasDetectedConfig){
            $this->bundlerConfig = File::get(base_path($this->bundlerConfigFile));
            $this->detectCustomPort();
        }

    }

    public function detectConfigFile()
    {
        foreach(array_keys(self::FILE_BUNDLER_MAPPING) as $file){
            if(file_exists(base_path($file))){
                $this->hasDetectedConfig = true;
                $this->bundler = self::FILE_BUNDLER_MAPPING[$file];
                $this->bundlerConfigFile = $file;
            }
        }
    }

    public function detectCustomPort()
    {
        if(str_contains($this->bundlerConfig, "port: ")){
            // Getting port
            $els = explode("port: ", $this->bundlerConfig);
            $port = explode("\n", $els[1])[0];
            if(is_numeric($port)){
                $this->hasDetectedPort = true;
                $this->bundlerCustomPort = $port;
            }
        }
    }
    
}