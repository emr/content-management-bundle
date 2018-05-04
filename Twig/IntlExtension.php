<?php

namespace Emr\CMBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;

class IntlExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $locale = 'en';

    public function __construct(RequestStack $requestStack = null)
    {
        if ($request = $requestStack->getCurrentRequest())
            $this->locale = $request->getLocale();
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('intl_language', [$this, 'language']),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('intl_language', [$this, 'language'])
        ];
    }

    public function language($language, $region = null, $displayLocale = null) {
        return Intl::getLanguageBundle()->getLanguageName($language, $region, $displayLocale ?? $this->locale);
    }

    public function getName()
    {
        return 'intl_extension';
    }
}