<?php

use Weglot\Util\Url;
use Weglot\Client\Api\LanguageEntry;

class UrlTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $languages;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->languages = array (
            'en' => new LanguageEntry( 'en' , 'en' , 'English' , 'English' , false),
            'fr' => new LanguageEntry( 'fr' , 'fr' , 'French' , 'Français' , false),
            'es' => new LanguageEntry( 'es' , 'es' , 'Spanish' , 'Espanol' , false),
            'de' => new LanguageEntry( 'de' , 'de' , 'German' , 'Deutsch' , false),
            'kr' => new LanguageEntry( 'kr' , 'kr' , 'unknown' , 'unknown' , false),
        );
        parent::__construct($name, $data, $dataName);
    }

    public function testSimpleUrlDefaultEnWithEsUrl()
    {
        $profile = [
            'url' => 'https://weglot.com/es/pricing',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['de'], $this->languages['es']],
            'prefix' => '',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://weglot.com',
                'getPathPrefix' => '',
                'getPath' => '/pricing',
                'getCurrentLanguage' => $this->languages['es'],
                'detectBaseUrl' => 'https://weglot.com/pricing',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' => 'https://weglot.com/pricing'),
                    array( 'language' => $this->languages['fr'], 'url' => 'https://weglot.com/fr/pricing'),
                    array( 'language' => $this->languages['de'], 'url' => 'https://weglot.com/de/pricing'),
                    array( 'language' => $this->languages['es'], 'url' => 'https://weglot.com/es/pricing')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testSimpleUrlDefaultFrWithEnUrl()
    {
        $profile = [
            'url' => 'https://www.ratp.fr/en/horaires',
            'default' =>  $this->languages['fr'],
            'languages' => [ $this->languages['en']],
            'prefix' => '',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://www.ratp.fr',
                'getPathPrefix' => '',
                'detectBaseUrl' => 'https://www.ratp.fr/horaires',
                'getPath' => '/horaires',
                'getCurrentLanguage' => $this->languages['en'],
                'getAllUrls' => [
                    array( 'language' => $this->languages['fr'], 'url' => 'https://www.ratp.fr/horaires'),
                    array( 'language' => $this->languages['en'], 'url' => 'https://www.ratp.fr/en/horaires'),
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testSimpleUrlDefaultFrWithEnUrlAndCustomPort()
    {
        $profile = [
            'url' => 'https://www.ratp.fr:3000/en/horaires',
            'default' =>  $this->languages['fr'],
            'languages' => [ $this->languages['en']],
            'prefix' => '',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://www.ratp.fr:3000',
                'getPathPrefix' => '',
                'detectBaseUrl' => 'https://www.ratp.fr:3000/horaires',
                'getPath' => '/horaires',
                'getCurrentLanguage' => $this->languages['en'],
                'getAllUrls' => [
                    array( 'language' => $this->languages['fr'], 'url' => 'https://www.ratp.fr:3000/horaires'),
                    array( 'language' => $this->languages['en'], 'url' => 'https://www.ratp.fr:3000/en/horaires'),
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testSimpleUrlDefaultFrWithFrUrl()
    {
        $profile = [
            'url' => 'https://www.ratp.fr/horaires',
            'default' =>  $this->languages['fr'],
            'languages' => [ $this->languages['en']],
            'prefix' => '',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://www.ratp.fr',
                'getPathPrefix' => '',
                'detectBaseUrl' => 'https://www.ratp.fr/horaires',
                'getPath' => '/horaires',
                'getCurrentLanguage' => $this->languages['fr'],
                'getAllUrls' => [
                    array( 'language' => $this->languages['fr'], 'url' => 'https://www.ratp.fr/horaires'),
                    array( 'language' => $this->languages['en'], 'url' => 'https://www.ratp.fr/en/horaires')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testUrlDefaultEnWithEsUrlAndPrefix()
    {
        $profile = [
            'url' => 'https://weglot.com/web/es/pricing',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['de'], $this->languages['es']],
            'prefix' => '/web',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://weglot.com',
                'getPathPrefix' => '/web',
                'getPath' => '/pricing',
                'getCurrentLanguage' => $this->languages['es'],
                'detectBaseUrl' => 'https://weglot.com/web/pricing',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' => 'https://weglot.com/web/pricing'),
                    array( 'language' => $this->languages['fr'], 'url' => 'https://weglot.com/web/fr/pricing'),
                    array( 'language' => $this->languages['de'], 'url' => 'https://weglot.com/web/de/pricing'),
                    array( 'language' => $this->languages['es'], 'url' =>  'https://weglot.com/web/es/pricing')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testUrlDefaultEnWithEsUrlAndTrailingSlashAndPrefix()
    {
        $profile = [
            'url' => 'http://weglotmultiv2.local/othersite/',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['de'], $this->languages['es']],
            'prefix' => '/othersite',
            'exclude' => [],
            'results' => [
                'getHost' => 'http://weglotmultiv2.local',
                'getPathPrefix' => '/othersite',
                'getPath' => '/',
                'getCurrentLanguage' => $this->languages['en'],
                'detectBaseUrl' => 'http://weglotmultiv2.local/othersite/',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' => 'http://weglotmultiv2.local/othersite/'),
                    array( 'language' => $this->languages['fr'], 'url' => 'http://weglotmultiv2.local/othersite/fr/'),
                    array( 'language' => $this->languages['de'], 'url' => 'http://weglotmultiv2.local/othersite/de/'),
                    array( 'language' => $this->languages['es'], 'url' => 'http://weglotmultiv2.local/othersite/es/')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testUrlDefaultEnWithEnUrlAndPrefixAsUrl()
    {
        $profile = [
            'url' => 'https://weglot.com/web',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['de'], $this->languages['es']],
            'prefix' => '/web',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://weglot.com',
                'getPathPrefix' => '/web',
                'getPath' => '/',
                'getCurrentLanguage' => $this->languages['en'],
                'detectBaseUrl' => 'https://weglot.com/web/',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' => 'https://weglot.com/web/'),
                    array( 'language' => $this->languages['fr'], 'url' => 'https://weglot.com/web/fr/'),
                    array( 'language' => $this->languages['de'], 'url' =>  'https://weglot.com/web/de/'),
                    array( 'language' => $this->languages['es'], 'url' => 'https://weglot.com/web/es/')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testUrlDefaultEnWithEnUrlAndPrefixAsUrlAndCustomPort()
    {
        $profile = [
            'url' => 'https://weglot.com:8080/web/es/',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['de'], $this->languages['es']],
            'prefix' => '/web',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://weglot.com:8080',
                'getPathPrefix' => '/web',
                'getPath' => '/',
                'getCurrentLanguage' => $this->languages['es'],
                'detectBaseUrl' => 'https://weglot.com:8080/web/',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' =>  'https://weglot.com:8080/web/'),
                    array( 'language' => $this->languages['fr'], 'url' =>  'https://weglot.com:8080/web/fr/'),
                    array( 'language' => $this->languages['de'], 'url' => 'https://weglot.com:8080/web/de/'),
                    array( 'language' => $this->languages['es'], 'url' =>  'https://weglot.com:8080/web/es/')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testUrlDefaultEnWithFrAndExclude()
    {
        $profile = [
            'url' => 'https://weglot.com/fr/pricing',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['kr']],
            'prefix' => '',
            'exclude' => [
                [ '\/admin\/.*' , null ]
            ],
            'results' => [
                'getHost' => 'https://weglot.com',
                'getPathPrefix' => '',
                'getPath' => '/pricing',
                'getCurrentLanguage' => $this->languages['fr'],
                'detectBaseUrl' => 'https://weglot.com/pricing',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' =>  'https://weglot.com/pricing'),
                    array( 'language' => $this->languages['fr'], 'url' =>  'https://weglot.com/fr/pricing'),
                    array( 'language' => $this->languages['kr'], 'url' =>  'https://weglot.com/kr/pricing')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);

        $profile['url'] = 'https://weglot.com/fr/admin/dashboard';
        $profile['results']['getPath'] = '/admin/dashboard';
        $profile['results']['detectBaseUrl'] = 'https://weglot.com/admin/dashboard';
        $profile['results']['getAllUrls'] = [
            array( 'language' => $this->languages['en'], 'url' =>  'https://weglot.com/admin/dashboard'),
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testUrlDefaultEnWithKrAndInverseExclude()
    {
        $profile = [
            'url' => 'https://weglot.com/kr/pricing',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['kr']],
            'prefix' => '',
            'exclude' => [
                ['^(?!/rgpd-wordpress/?|/optimiser-wordpress/?).*$' , null ]
            ],
            'results' => [
                'getHost' => 'https://weglot.com',
                'getPathPrefix' => '',
                'getPath' => '/pricing',
                'getCurrentLanguage' => $this->languages['kr'],
                'detectBaseUrl' => 'https://weglot.com/pricing',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' =>  'https://weglot.com/pricing'),
                ] // because it's excluded
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);

        $profile['url'] = 'https://weglot.com/kr/rgpd-wordpress';
        $profile['results']['getPath'] = '/rgpd-wordpress';
        $profile['results']['detectBaseUrl'] = 'https://weglot.com/rgpd-wordpress';
        $profile['results']['getAllUrls'] = [
            array( 'language' => $this->languages['en'], 'url' =>  'https://weglot.com/rgpd-wordpress'),
            array( 'language' => $this->languages['fr'], 'url' =>  'https://weglot.com/fr/rgpd-wordpress'),
            array( 'language' => $this->languages['kr'], 'url' =>  'https://weglot.com/kr/rgpd-wordpress')
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testUrlDefaultEnWithFrAndPrefixAndExclude()
    {
        $profile = [
            'url' => 'https://weglot.com/landing/fr/how-to-manage-your-translations',
            'default' =>  $this->languages['en'],
            'languages' => [ $this->languages['fr'], $this->languages['kr']],
            'prefix' => '/landing',
            'exclude' => [
                '\/admin\/.*'
            ],
            'results' => [
                'getHost' => 'https://weglot.com',
                'getPathPrefix' => '/landing',
                'getPath' => '/how-to-manage-your-translations',
                'getCurrentLanguage' => $this->languages['fr'],
                'detectBaseUrl' => 'https://weglot.com/landing/how-to-manage-your-translations',
                'getAllUrls' => [
                    array( 'language' => $this->languages['en'], 'url' =>  'https://weglot.com/landing/how-to-manage-your-translations'),
                    array( 'language' => $this->languages['fr'], 'url' =>  'https://weglot.com/landing/fr/how-to-manage-your-translations'),
                    array( 'language' => $this->languages['kr'], 'url' =>  'https://weglot.com/landing/kr/how-to-manage-your-translations')
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);

        $profile['url'] = 'https://weglot.com/landing/fr/admin/how-to-manage-your-translations';
        $profile['results']['getPath'] = '/admin/how-to-manage-your-translations';
        $profile['results']['detectBaseUrl'] = 'https://weglot.com/landing/admin/how-to-manage-your-translations';
        $profile['results']['getAllUrls'] = [
            array( 'language' => $this->languages['en'], 'url' =>  'https://weglot.com/landing/admin/how-to-manage-your-translations'),
            array( 'language' => $this->languages['fr'], 'url' =>  'https://weglot.com/landing/fr/admin/how-to-manage-your-translations'),
            array( 'language' => $this->languages['kr'], 'url' =>  'https://weglot.com/landing/kr/admin/how-to-manage-your-translations')
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    public function testSimpleUrlDefaultFrWithEnUrlAndQuery()
    {
        $profile = [
            'url' => 'https://www.ratp.fr/en/horaires?from=2018-06-04&to=2018-06-05',
            'default' =>  $this->languages['fr'],
            'languages' => [ $this->languages['en']],
            'prefix' => '',
            'exclude' => [],
            'results' => [
                'getHost' => 'https://www.ratp.fr',
                'getPathPrefix' => '',
                'detectBaseUrl' => 'https://www.ratp.fr/horaires?from=2018-06-04&to=2018-06-05',
                'getPath' => '/horaires',
                'getCurrentLanguage' => $this->languages['en'],
                'getAllUrls' => [
                    array( 'language' => $this->languages['fr'], 'url' =>  'https://www.ratp.fr/horaires?from=2018-06-04&to=2018-06-05'),
                    array( 'language' => $this->languages['en'], 'url' =>  'https://www.ratp.fr/en/horaires?from=2018-06-04&to=2018-06-05'),
                ]
            ]
        ];

        $url = $this->_urlInstance($profile);
        $this->_checkResults($url, $profile);
    }

    /**
     * @param array $profile
     * @return Url
     */
    protected function _urlInstance(array $profile)
    {
        return (new Url(
            $profile['url'],
            $profile['default'],
            $profile['languages'],
            $profile['prefix'],
            $profile['exclude'],
            null
        ));
    }

    /**
     * @param array $currentRequestAllUrls
     * @return string
     */
    protected function _generateHrefLangs(array $currentRequestAllUrls)
    {
        $render = '';
        foreach ($currentRequestAllUrls as $urlArray) {
            $render .= '<link rel="alternate" href="' .$urlArray['url']. '" hreflang="' .$urlArray['language']->getExternalCode(). '"/>'."\n";
        }
        return $render;
    }

    /**
     * @param Url $url
     * @param array $profile
     * @return void
     */
    protected function _checkResults(Url $url, array $profile)
    {
        // cloned $url, to be sure to have a `null` $baseUrl
        $cloned = clone $url;
        $this->assertEquals($profile['results']['getAllUrls'], $cloned->getAllUrls());

        // cloned $url, to be sure to have a `null` $baseUrl
        $cloned = clone $url;

        $this->assertEquals($profile['results']['detectBaseUrl'], $url->detectUrlDetails());

        $this->assertEquals($profile['results']['getHost'], $url->getHost());
        $this->assertEquals($profile['results']['getPathPrefix'], $url->getPathPrefix());
        $this->assertEquals($profile['results']['getPath'], $url->getPath());

        $this->assertEquals($profile['results']['getCurrentLanguage'], $url->getCurrentLanguage());

        $this->assertEquals($profile['results']['getAllUrls'], $url->getAllUrls());
        //$this->assertEquals($this->_generateHrefLangs($profile['results']['getAllUrls']), $url->generateHrefLangsTags());

        foreach ($profile['results']['getAllUrls'] as $urlArray) {
            $this->assertEquals($urlArray['url'], $url->getForLanguage($urlArray['language']));
        }
    }
}
