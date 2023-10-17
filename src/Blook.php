<?php

namespace Raitone\Blook;

use Illuminate\Support\Facades\File;
use Illuminate\View\ComponentAttributeBag;

class Blook {

    public ?string $component;
    public ?string $variation;

    public array $params; // GET query
    public array $components;
    public string $componentsPath;
    public array $componentsVariations;
    public array $componentsWithVariations;

    public string $fileSuffix;
    private string $rootGroupName;

    public function __construct(string $component=null, string $variation=null, array $params=[])
    {
        $this->component = $component;
        $this->variation = $variation;
        $this->params = $params;
        $this->fileSuffix = ".blade.php";

        $shouldListComponents = is_null($this->component);

        $this->rootGroupName = config('blook.root_group_name');
        $this->componentsPath = base_path(config('blook.path'));
        $this->componentsVariations = config('blook.variations');
        $this->componentsWithVariations = array_keys($this->componentsVariations);

        /*
        * Since both controllers use a Blook object to get content, we go through the component
        * listing only if component is not provided, for performance reasons. This avoid to loop through
        * files and folder when a blook.show page is called.
        */
        $this->components = $shouldListComponents ? $this->getAllComponents($this->componentsPath, 1) : [];
    }

    public function getComponentDetails()
    {
        $componentAttributes = [];

        $componentRelativePath = str_replace(".", "/", $this->component) . $this->fileSuffix;
        $fullComponentPath = $this->componentsPath.$componentRelativePath;
        $componentCode = File::get($fullComponentPath);

        // Passing all get params in all cases
        $attributes = $this->params;

        if($this->variation && in_array($this->component, $this->componentsWithVariations)){
            $attributes = array_merge(
                $attributes,
                $this->componentsVariations[$this->component][$this->variation]["attributes"],
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

        unset($items[array_search('.', $items, true)]);
        unset($items[array_search('..', $items, true)]);
    
        $main = [];

        foreach($items as $item){
            if(is_dir($dir.'/'.$item)){
                $main[$item] = [
                    "type" => "folder",
                    "children" => $this->getAllComponents($dir.'/'.$item, $level + 1)
                ];
            }else{

                $cleanName = $this->getComponentName($item);
                $relativePath = $this->getRelativePath($dir);
                $componentFullName = str_replace("/", ".", substr(explode($fileSuffix, $relativePath.'/'.$item)[0], 1));


                if(! in_array($componentFullName, config('blook.banlist'))){

                    // Getting variations if some exist
                    $variations = [];
                    if(in_array($componentFullName, $this->componentsWithVariations)){
                        $variations = $this->componentsVariations[$componentFullName];
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
