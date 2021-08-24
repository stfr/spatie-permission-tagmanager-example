<?php

namespace App\Mails;

use App\Exceptions\TagManagerException;
use App\Helpers\TagManagerHelper;
use \Spatie\MailTemplates\TemplateMailable;

use App\Models\User;
use App\Traits\TagManager;
use ReflectionClass;
use ReflectionProperty;

class TagManagerMailable extends TemplateMailable
{

    const requiredTags = [];
    const requiredObjects = [];


    private $calculateTags = null;

    /**
     * Tags or objects list
     *
     * @var array
     */
    protected $tagObjects = [];
    protected $manualTags = [];



    /**
     * Store objects for tag replacement
     *
     * @param Object $object
     * @return self
     */
    public function addTagObject($object)
    {
        $this->tagObjects[get_class($object)] = $object;
        return $this;
    }


    /**
     * Store a manual tag
     *
     * @param string $tagName
     * @param string $tagValue
     * @return self
     */
    public function addManualTag($tagName, $tagValue)
    {
        $this->manualTags[$tagName] = $tagValue;
        return $this;
    }




    /**
     *  Return datas for tag replacement
     *
     * @return array
     */
    public function buildViewData()
    {
        if (is_null($this->calculateTags)) {

            $dataClass = parent::buildViewData();
            $this->searchForMissingTag();

            $dataTags = $this->getDatasFromTags();

            $this->calculateTags = array_merge($dataClass, $dataTags);
        }
        return $this->calculateTags;
    }


    /**
     * Return class variables 
     *
     * @return array
     */
    public static function getVariables(): array
    {
        $variables = parent::getVariables();
        $variablesFromTags = static::getRequiredTags();

        return array_unique( array_merge($variables, $variablesFromTags) );
    }


    /**
     * Check if required tags & objects exists
     *
     * @return void
     */
    private function searchForMissingTag()
    {

        $tagClasses = array_keys($this->tagObjects);
        $manualTags = array_keys($this->manualTags);


        foreach (static::requiredObjects as $class) {

            if (!in_array($class, $tagClasses)) {
                throw new TagManagerException("TagManager: Missing tag object \"" . $class . "\"");
            }
        }

        foreach (static::requiredTags as $tagName) {
            if (!in_array($tagName, $manualTags)) {
                throw new TagManagerException("TagManager: Missing manual tag \"" . $tagName . "\"");
            }
        }
    }
    /**
     * Return an associative array [tag => value]  of all tags
     * @return array [tag=>valeur]
     */
    public function getDatasFromTags()
    {
        return TagManagerHelper::getTagValues($this->manualTags, $this->tagObjects);
    }
    /**
     * Return listing of required (so usable) tags
     *
     * @return array Liste des tags
     */
    public static function getRequiredTags()
    {
        return array_keys( TagManagerHelper::getTagValues(array_flip(static::requiredTags), array_flip(static::requiredObjects)));
    }






    /**
     * Return data replacement for post-render
     *
     * @return array|null
     */
    public function getCalculateTags()
    {
        return $this->calculateTags;
    }

    public function getHtmlLayout(): string {
        //to change (default email template)
        return view('emails.default', ['body' => '{{{ body }}}'])->render();
    }
}
