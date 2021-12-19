<?php


namespace Artemis\Core\Template;


use Artemis\Client\Facades\Gate;
use Artemis\Support\FileSystem;
use eftec\bladeone\BladeOne;


class BladeArtemis extends BladeOne
{
    /**
     * @inheritDoc
     */
    public function __construct($templatePath = null, $compiledPath = null, $mode = 0)
    {
        $old_mask = FileSystem::umask(0);

        parent::__construct($templatePath, $compiledPath, $mode);

        FileSystem::umask($old_mask);
    }

    /**
     * @inheritDoc
     */
    public function showError($id, $text, $critic = false, $alwaysThrow = false)
    {
        \ob_get_clean();

        if (($this->throwOnError || $alwaysThrow) && $critic === true) {
            return parent::showError($id, $text, $critic, $alwaysThrow);
        } else {
            if ($critic) {
                report(new \Exception("BladeOne Error [$id] $text"));
                exit;
            }

            echo "<div style='background-color: red; color: black; padding: 3px; border: solid 1px black;'>";
            echo "BladeOne Error [$id]:<br>";
            echo "<span style='color:white'>$text</span><br></div>\n";

            if ($this->throwOnError) {
                error_log("BladeOne Error [$id] $text");
            }
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function compile($templateName = null, $forced = false)
    {
        $old_mask = FileSystem::umask(0);

        $compiled = parent::compile($templateName, $forced);

        FileSystem::umask($old_mask);

        return $compiled;
    }

    /**
     * @inheritDoc
     */
    public function getCompiledFile($templateName = '')
    {
        $templateName = (empty($templateName)) ? $this->fileName : $templateName;
        return $this->compiledPath . '/' . $templateName . '___' . \sha1($templateName) . $this->compileExtension;
    }

    /**
     * Compile the auth statements into valid PHP.
     *
     * @param string $expression
     *
     * @return string
     */
    protected function compileAuth($expression = '')
    {
        if( $expression == '' ) {
            return $this->phpTag . "if( auth()->valid ): ?>";
        }

        $gate_check = Gate::class . '::allows' . $expression;

        return $this->phpTag . "if( $gate_check ): ?>";
    }

    /**
     * Compile the end-auth statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndAuth()
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compilers admin statements into valid PHP.
     *
     * @return string
     */
    protected function compileAdmin()
    {
        return $this->phpTag . "if( null !== auth()->user() && auth()->valid && (auth()->user() instanceof Artemis\Core\Auth\Interfaces\AdminAuthentication) && auth()->user()->isAdmin() ): ?>";
    }

    /**
     * Compiles end admin statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndAdmin()
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compile the guest statements into valid PHP.
     *
     * @param null $expression
     *
     * @return string
     */
    protected function compileGuest($expression = null)
    {
        return $this->phpTag . 'if( !auth()->valid ): ?>';
    }

    /**
     * Compile the guest statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndGuest()
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compiles csrf field into valid PHP.
     *
     * @param null $expression
     *
     * @return string
     */
    protected function compilecsrf($expression = null)
    {
        return '<?php echo csrf_field() ?>';
    }

    /**
     * Compiles method field into valid PHP.
     *
     * @param $expression
     *
     * @return string
     */
    protected function compileMethod($expression)
    {
        $v = $this->stripParentheses($expression);

        return "<input type='hidden' name='_method' value='{$this->phpTag}echo $v; " . "?>'/>";
    }
}