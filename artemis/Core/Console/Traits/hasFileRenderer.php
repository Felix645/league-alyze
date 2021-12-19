<?php


namespace Artemis\Core\Console\Traits;


use Artemis\Resource\Extensions\CustomBladeExtension;


trait hasFileRenderer
{
    private $view_path = ROOT_PATH . 'artemis/Core/Views';

    private $cache_path = ROOT_PATH . 'artemis/Core/Views/cache';

    private function render(string $view, array $data = []) : string
    {
        $blade = new CustomBladeExtension($this->view_path, $this->cache_path);
        return $this->replaceCharacters($blade->run($view, $data));
    }

    private function replaceCharacters(string $content) : string
    {
        $content = str_replace('\tab', '    ', $content);
        return "<?php\n\n\n" . str_replace('\s', ' ', $content);
    }
}