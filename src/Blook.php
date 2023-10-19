<?php

namespace Raitone\Blook;

use Illuminate\Support\Facades\File;
use Illuminate\View\ComponentAttributeBag;

class Blook {

    public ?string $component;
    public ?string $variation;
    public ?bool $explore;

    public array $params; // GET query
    private array $components;
    public string $componentsPath;
    public array $componentsDefinitions;
    public array $componentsWithDefinitions;

    public string $fileSuffix;
    public string $definitionFilename;
    private string $rootGroupName;

    public function __construct(
        string $component=null,
        string $variation=null,
        array $params=[],
        bool $explore=false)
    {

        $this->component = $component;
        $this->variation = $variation;
        $this->params = $params;
        $this->explore = $explore;
        $this->definitionFilename = "@definitions.php";
        $this->fileSuffix = ".blade.php";

        $this->rootGroupName = config('blook.root_group_name');
        $this->componentsPath = base_path(config('blook.path'));
        $this->componentsDefinitions = include_once($this->componentsPath.$this->definitionFilename);
        $this->componentsWithDefinitions = array_keys($this->componentsDefinitions);

        /*
        * Since both controllers use a Blook object to get content, we go through the component
        * listing only if when explicitly asked (on index), for performance reasons. This avoid to loop through
        * files and folder each time a 'blook.show' page is called.
        */
        $this->components = $this->explore ? $this->getAllComponents($this->componentsPath, 1) : [];
    }


    public function getComponentShowRoute()
    {
        // Preparing iframe route to show component
        if($this->component){
            $routeName = 'blook.show';
            $context = [$this->component];
        }

        // Preparing iframe route to show component with variation
        if($this->variation){
            $routeName = 'blook.show.variation';
            $context = [$this->component, $this->variation];
        }

        // TODO DOM FIN D A WAYRepopulating context with query args if there are some
        /*foreach($request->query() as $arg => $value){
            $context[$arg] = $value;
        }*/

        return isset($routeName) ? route($routeName, $context) : "";
    }

    public function getComponentDetails()
    {
        if(!$this->component){
            return [];
        }

        $componentAttributes = [];

        $componentRelativePath = str_replace(".", "/", $this->component) . $this->fileSuffix;
        $fullComponentPath = $this->componentsPath.$componentRelativePath;
        $componentCode = File::get($fullComponentPath);

        // Passing all get params in all cases
        $attributes = $this->params;

        if($this->variation && in_array($this->component, $this->componentsWithDefinitions)){
            $attributes = array_merge(
                $attributes,
                $this->componentsDefinitions[$this->component][$this->variation]["attributes"],
            );
        }

        $componentAttributes = new ComponentAttributeBag($attributes);

        return [
            "fullComponentPath" => $fullComponentPath,
            "componentName" => $this->component,
            "componentCode" => $componentCode,
            "attributes" => $componentAttributes,
            "variation" => $this->variation
        ];
    }

    public function getComponents()
    {
        return $this->components;
    }

    private function getComponentName(string $filename)
    {
        return explode($this->fileSuffix, $filename)[0];
    }

    private function getRelativePath(string $filename)
    {
        return explode("/components/", $filename)[1];
    }

    public function getAllComponents($dir, $level){
        
        $fileSuffix = ".blade.php";
        $items = scandir($dir);

        unset($items[array_search($this->definitionFilename, $items, true)]);
        unset($items[array_search('.', $items, true)]);
        unset($items[array_search('..', $items, true)]);
    
        $main = [];

        foreach($items as $item){
            if(is_dir($dir.'/'.$item)){

                if(! in_array($item.'/', config('blook.banlist'))){
                    $main[$item] = [
                        "type" => "folder",
                        "children" => $this->getAllComponents($dir.'/'.$item, $level + 1)
                    ];
                }

            }else{

                $cleanName = $this->getComponentName($item);
                $relativePath = $this->getRelativePath($dir);
                $componentFullName = str_replace("/", ".", substr(explode($fileSuffix, $relativePath.'/'.$item)[0], 1));


                if(! in_array($componentFullName, config('blook.banlist'))){

                    // Getting variations if some exist
                    $variations = [];
                    if(in_array($componentFullName, $this->componentsWithDefinitions)){
                        $variations = $this->componentsDefinitions[$componentFullName];
                    }

                    if($level == 1){
                        $main[$this->rootGroupName]["type"] = "folder";
                        $main[$this->rootGroupName]["children"][] = [
                            "type" => "file",
                            "path" => $relativePath.'/'.$item,
                            "directory" => $relativePath,
                            "name" => $cleanName,
                            "fullname" => $componentFullName,
                            "filename" => $item,
                            "variations" => $variations
                        ];
                    }else{
                        $main[] = [
                            "type" => "file",
                            "path" => $relativePath.'/'.$item,
                            "directory" => $relativePath,
                            "fullname" => $componentFullName,
                            "name" => $cleanName,
                            "filename" => $item,
                            "variations" => $variations
                        ];
                    }
                }
            }
        }

        return $main;
    }

    
}
