<?php

namespace Raitone\Blook;

use Illuminate\Support\Facades\File;
use Illuminate\View\ComponentAttributeBag;

class Blook
{
    const ATTRIBUTES = "attributes";
    const ASSETS = "assets";
    const DEFAULT = "default";
    const DEFINITIONS = "@definitions.php";
    const FILE_SUFFIX = ".blade.php";
    const SLOTS = "slots";

    public ?string $component;
    public ?string $variation;
    public ?bool $explore;

    public array $queryParams;
    private array $components;
    public string $componentsPath;
    public array $componentsDefinitions;
    public array $componentsWithDefinitions;

    public string $fileSuffix;
    public string $definitionFilename;
    private string $rootGroupName;

    public function __construct(
        string $component = null,
        string $variation = null,
        array $queryParams = [],
        bool $explore = false
    ) {

        $this->component = $component;
        $this->variation = $variation;
        $this->queryParams = $queryParams;
        $this->explore = $explore;

        $this->rootGroupName = config('blook.root_group_name');
        $this->componentsPath = base_path(config('blook.path'));
        $this->componentsDefinitions = include_once($this->componentsPath . self::DEFINITIONS);
        $this->componentsWithDefinitions = array_keys($this->componentsDefinitions);

        /*
        * Since both controllers use a Blook object to get content, we go through the component
        * listing only if when explicitly asked (on index), for performance reasons. This avoid to loop through
        * files and folder each time a 'blook.show' page is called.
        */
        $this->components = $this->explore ? $this->getAllComponents($this->componentsPath, 1) : [];
    }

    public function getComponentShowRoute() : string
    {
        // Preparing iframe route to show component
        if ($this->component) {
            $routeName = 'blook.show';
            $context = [$this->component];
        }

        // Preparing iframe route to show component with variation
        if ($this->variation) {
            $routeName = 'blook.show.variation';
            $context = [$this->component, $this->variation];
        }

        // Repopulating with query params
        foreach ($this->queryParams as $param => $value) {
            $context[$param] = $value;
        }

        return isset($routeName) ? route($routeName, $context) : "";
    }

    public function getComponentDetails() : array
    {
        $componentAttributes = [];
        $componentRelativePath = str_replace(".", "/", $this->component) . self::FILE_SUFFIX;
        $fullComponentPath = $this->componentsPath . $componentRelativePath;
        $componentCode = is_file($fullComponentPath) ? File::get($fullComponentPath) : "";

        $attributes = [];
        $slots = [];
        $assets = [];

        if($this->componentHasProperty(self::ATTRIBUTES)){
            $attributes = $this->loadComponentProperty(self::ATTRIBUTES);
        }

        if($this->componentHasProperty(self::SLOTS)){
            $slots = $this->loadComponentProperty(self::SLOTS);
        }

        if($this->componentHasProperty(self::ASSETS)){
            $assets = $this->loadComponentProperty(self::ASSETS);
        }

        // Query overrides variation overrides default
        $attributes = array_merge(
            $attributes,
            $this->queryParams
        );

        $componentAttributes = new ComponentAttributeBag($attributes);

        return [
            "fullComponentPath" => $fullComponentPath,
            "componentName" => $this->component ?? "",
            "componentCode" => $componentCode,
            self::ATTRIBUTES => $componentAttributes,
            "variation" => $this->variation,
            self::SLOTS => $slots,
            self::ASSETS => $assets,
        ];
    }

    public function loadComponentProperty(string $property)
    {
        $defaultProperty = [];
        $variationProperty = [];

        if( $this->componentHasDefaultDefinition()
            && array_key_exists($property, $this->componentsDefinitions[$this->component][self::DEFAULT])
        ){
            $defaultProperty = $this->componentsDefinitions[$this->component][self::DEFAULT][$property];
        }
        if( $this->variation
            && array_key_exists($property, $this->componentsDefinitions[$this->component][$this->variation])
        ){
            $variationProperty = $this->componentsDefinitions[$this->component][$this->variation][$property];
        }

        return array_merge($defaultProperty, $variationProperty);
    }

    public function getAllComponents($dir, $level) : array
    {

        $main = [];
        $items = scandir($dir);
        $fileSuffix = ".blade.php";

        // Cleans ".", ".." and definition file
        unset($items[array_search(self::DEFINITIONS, $items, true)]);
        unset($items[array_search('.', $items, true)]);
        unset($items[array_search('..', $items, true)]);

        foreach ($items as $item) {

            $folderName = $item . '/';
            $fullPath = $dir . '/' . $item;

            // Exploring subfolder if folder and not in banlist
            if (is_dir($fullPath) && $this->shouldCollectItem($folderName)) {
                $main[$item] = [
                    "type" => "folder",
                    "children" => $this->getAllComponents($fullPath, $level + 1)
                ];
            } else {

                $cleanName = $this->getComponentName($item);
                $relativePath = $this->getRelativePath($dir);
                $componentFullName = str_replace("/", ".", substr(explode($fileSuffix, $relativePath . '/' . $item)[0], 1));

                if ($this->shouldCollectItem($componentFullName)) {

                    $finalItem = [
                        "type" => "file",
                        "path" => $relativePath . '/' . $item,
                        "directory" => $relativePath,
                        "name" => $cleanName,
                        "fullname" => $componentFullName,
                        "filename" => $item,
                        "variations" => $this->getItemVariations($componentFullName)
                    ];

                    // Components in root folder are put in specified folder name
                    if ($level == 1) {
                        $main[$this->rootGroupName]["type"] = "folder";
                        $main[$this->rootGroupName]["children"][] = $finalItem;
                    } else {
                        $main[] = $finalItem; // Collecting item
                    }
                }
            }
        }

        return $main;
    }

    public function getComponents() : array
    {
        return $this->components;
    }

    private function getComponentName(string $filename) : string
    {
        return explode(self::FILE_SUFFIX, $filename)[0];
    }

    private function getRelativePath(string $filename) : string
    {
        return explode("/components/", $filename)[1];
    }

    private function getItemVariations($item) : array
    {
        $variations = [];
        if (in_array($item, $this->componentsWithDefinitions)) {
            unset($this->componentsDefinitions[$item][self::DEFAULT]);
            $variations = $this->componentsDefinitions[$item];
        }
        return $variations;
    }

    public function componentHasProperty(string $property)
    {
        if(! $this->componentHasDefinitions()){
            return false;
        }
        $propertyInDefault = $this->componentHasDefaultDefinition()
            && array_key_exists($property, $this->componentsDefinitions[$this->component][self::DEFAULT]);
        $propertyInVariation = $this->variation
            ? array_key_exists($property, $this->componentsDefinitions[$this->component][$this->variation])
            : false;
        return $propertyInDefault || $propertyInVariation;
    }


    private function componentHasDefinitions()
    {
        return in_array($this->component, $this->componentsWithDefinitions);
    }

    private function componentHasDefaultDefinition()
    {
        return in_array(self::DEFAULT, array_keys($this->componentsDefinitions[$this->component]));
    }

    private function shouldCollectItem($item) : bool
    {
        return !in_array($item, config('blook.banlist'));
    }
}
