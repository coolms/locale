<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 * 
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Mapping\Traits;

use CmsLocale\Mapping\LocaleInterface;

trait LocalizableTrait
{
    /**
     * @var LocaleInterface
     *
     * @ORM\ManyToOne(targetEntity="CmsLocale\Mapping\LocaleInterface")
     * @ORM\JoinColumn(nullable=true)
     * @Form\Type("ObjectSelect")
     * @Form\Filter({"name":"StripTags"})
     * @Form\Filter({"name":"StringTrim"})
     * @Form\Required(false)
     * @Form\AllowEmpty(true)
     * @Form\Attributes({})
     * @Form\Options({
     *      "empty_option":"Select language",
     *      "label":"Select language",
     *      "text_domain":"CmsLocale",
     *      "target_class":"CmsLocale\Mapping\LocaleInterface",
     *      "property":"name",
     *      "find_method":{
     *          "name":"findBy",
     *          "params":{
     *              "criteria":{"state":true},
     *          },
     *      }})
     */
    protected $locale;

    /**
     * @param LocaleInterface $locale
     */
    public function setLocale(LocaleInterface $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return LocaleInterface
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
