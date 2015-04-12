<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Entity;

use Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo,
    CmsDoctrineORM\Mapping\Common\Traits\NameableTrait,
    CmsDoctrineORM\Mapping\Common\Traits\StateableTrait,
    CmsLocale\Mapping\LocaleInterface,
    CmsCldr\Mapping\LanguageInterface,
    CmsCldr\Mapping\RegionInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="locales")
 * @ORM\HasLifecycleCallbacks
 */
class Locale implements LocaleInterface
{
    use NameableTrait,
        StateableTrait;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(length=14,type="string")
     * @Form\Exclude()
     */
    protected $id;

    /**
     * @var LanguageInterface
     *
     * @ORM\ManyToOne(targetEntity="CmsCldr\Mapping\LanguageInterface",inversedBy="locales")
     * @ORM\JoinColumn(nullable=false)
     * @Form\Type("ObjectSelect")
     */
    protected $language;

    /**
     * @var RegionInterface
     *
     * @ORM\ManyToOne(targetEntity="CmsCldr\Mapping\RegionInterface",inversedBy="locales")
     * @ORM\JoinColumn(nullable=true)
     * @Form\Type("ObjectSelect")
     */
    protected $region;

    /**
     * __construct
     *
     * @param LanguageInterface $language
     * @param RegionInterface   $region
     */
    public function __construct($language, $region = null)
    {
        $this->setLanguage($language);
        $this->setRegion($region);
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param LanguageInterface $language
     */
    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;
        $language->addLocale($this);
    }

    /**
     * @return LanguageInterface
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param RegionInterface $region
     */
    public function setRegion(RegionInterface $region = null)
    {
        $this->region = $region;
        $region->addLocale($this);
    }

    /**
     * @return RegionInterface
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getCanonicalName()
    {
        return \Locale::canonicalize($this->getId());
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $id = $this->getLanguage()->getId();
        if ($this->getRegion()) {
            $id .= '-' . $this->getRegion()->getId();
        }
        $this->setId($id);
    }
}
