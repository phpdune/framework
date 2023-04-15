<?php

declare(strict_types=1);

namespace Dune\Views;

class PineCompiler
{
    /**
     * @param  string  $template
     *
     * @return string
     */
    public function compile(string $template): string
    {
        $template = $this->varCompile($template);
        $template = $this->varCompileReal($template);
        $template = $this->compileForeach($template);
        $template = $this->compileFor($template);
        $template = $this->compileWhile($template);
        $template = $this->compilePHP($template);
        $template = $this->compileIf($template);
        $template = $this->addNamespace($template);
        return $template;
    }
    /**
     * @param  string  $template
     * @param string $tag
     *
     * @return string
     */
     public function compileExtends(string $template, string $tag): ?string
     {
         return $template = str_replace($tag, '', $template);
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected function varCompile(string $template): string
    {
        $template = preg_replace('/{{/', '<?php echo htmlspecialchars(', $template);
        $template = preg_replace('/}}/', ', ENT_QUOTES); ?>', $template);
        return $template;
    }
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected function varCompileReal(string $template): string
    {
        $template = preg_replace('/{!/', '<?php echo ', $template);
        $template = preg_replace('/!}/', '; ?>', $template);
        return $template;
    }
    /**
     *
     * @param  string  $template
     *
     * @return string
     */
     protected function compileForeach(string $template): string
     {
         $template = str_replace('{ foreach(', '<?php foreach(', $template);
         $template = str_replace(') }', '): ?>', $template);
         $template = str_replace('{ endforeach }', '<?php endforeach; ?>', $template);
         return $template;
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
     protected function compileFor(string $template): string
     {
         $template = str_replace('{ for(', '<?php for(', $template);
         $template = str_replace(') }', '): ?>', $template);
         $template = str_replace('{ endfor }', '<?php endfor; ?>', $template);
         return $template;
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
     protected function compileWhile(string $template): string
     {
         $template = str_replace('{ while(', '<?php while(', $template);
         $template = str_replace(') }', '): ?>', $template);
         $template = str_replace('{ endwhile }', '<?php endwhile; ?>', $template);
         return $template;
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
     protected function compilePHP(string $template): string
     {
         $template = str_replace('{ php', '<?php ', $template);
         $template = str_replace('endphp }', ' ?>', $template);
         return $template;
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
     protected function compileIf(string $template): string
     {
         $template = str_replace('{ if(', '<?php if( ', $template);
         $template = str_replace(') }', '): ?>', $template);
         $template = str_replace('{ else }', '<?php else: ?>', $template);
         $template = str_replace('{ elseif(', '<?php elseif(', $template);
         $template = str_replace(') }', '): ?>', $template);
         $template = str_replace('{ endif }', '<?php endif; ?>', $template);
         return $template;
     }

    /**
     * @param  string  $template
     *
     * @return string
     */
     protected function addNamespace(string $template): ?string
     {
         $template = preg_replace('/Session::/', '\Dune\Session\Session::', $template);
         $template = preg_replace('/Cookie::/', '\Dune\Cookie\Cookie::', $template);
         $template = preg_replace('/Grape::/', '\Coswat\Grapes\Grape::', $template);
         return $template;
     }
}
