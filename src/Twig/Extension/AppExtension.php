<?php

namespace App\Twig\Extension;

use Symfony\Component\Intl\Locales;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var string[]
     */
    private array $localeCodes;

    /**
     * @var list<array{code: string, name: string}>|null
     */
    private ?array $locales = null;

    // The $locales argument is injected thanks to the service container.
    // See https://symfony.com/doc/current/service_container.html#binding-arguments-by-name-or-type
    public function __construct(string $locales, private CacheInterface $cache)
    {
        $localeCodes = explode('|', $locales);
        sort($localeCodes);
        $this->localeCodes = $localeCodes;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('locales', [$this, 'getLocales']),
        ];
    }

    /**
     * Takes the list of codes of the locales (languages) enabled in the
     * application and returns an array with the name of each locale written
     * in its own language (e.g. English, Français, Español, etc.).
     *
     * @return array<int, array<string, string>>
     */
    public function getLocales(): array
    {
        if (null !== $this->locales) {
            return $this->locales;
        }

        $this->locales = $this->cache->get(md5('locals'), function (ItemInterface $item) {
            $locales = [];
            foreach ($this->localeCodes as $localeCode) {
                $locales[] = ['code' => $localeCode, 'name' => Locales::getName($localeCode, $localeCode)];
            }
            $item->expiresAfter(3600 * 3);
            return $locales;
        });

        return $this->locales;
    }
}
