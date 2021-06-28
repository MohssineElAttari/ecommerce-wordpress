<?php

namespace Weglot\Client\Endpoint;

use Weglot\Client\Api\LanguageCollection;
use Weglot\Client\Factory\Languages as LanguagesFactory;
use Languages\Languages;
/**
 * Class Languages
 * @package Weglot\Client\Endpoint
 */
class LanguagesList extends Endpoint
{
    const METHOD = 'GET';
    const ENDPOINT = '/languages';

    public function getLanguages(){
        $data = Languages::$defaultLanguages;
        return $data;
    }

    /**
     * @return LanguageCollection
     */
    public function handle()
    {
        $languageCollection = new LanguageCollection();
        $data = Languages::$defaultLanguages;

        $data = array_map(function($data) {
            return array(
                'internal_code' => $data['code'],
                'english' => $data['english'],
                'local' => $data['local'],
                'rtl' => $data['rtl'],
                'external_code' => ($data['code'] == 'tw') ? 'zh-tw' : $data['code'],
            );
        }, $data);

        foreach ($data as $language) {
            $factory = new LanguagesFactory($language);
            $languageCollection->addOne($factory->handle());
        }

        return $languageCollection;
    }
}
