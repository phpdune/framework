<?php

declare(strict_types=1);

namespace Dune\Views;

class Compiler
{
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected static function compile(string $template): string
    {
        $template = self::varCompile($template);
        $template = self::varCompileReal($template);
        $template = self::compileForeach($template);
        $template = self::compileFor($template);
        $template = self::compileWhile($template);
        $template = self::compilePHP($template);
        $template = self::compileIf($template);
        $template = self::addNamespace($template);
        return $template;
    }
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected static function varCompile(string $template): string
    {
        $template = preg_replace('/{{/','<?php echo htmlspecialchars(',$template);
        $template = preg_replace('/}}/',', ENT_QUOTES); ?>', $template);
        return $template;
    }
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected static function varCompileReal(string $template): string
    {
        $template = preg_replace('/{!/','<?php echo ',$template);
        $template = preg_replace('/!}/','; ?>', $template);
        return $template;
    }
    /**
     *
     * @param  string  $template
     *
     * @return string
     */
     protected static function compileForeach(string $template): string
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
     protected static function compileFor(string $template): string
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
     protected static function compileWhile(string $template): string
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
     protected static function compilePHP(string $template): string
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
     protected static function compileIf(string $template): string
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
     * @param string $tag
     *
     * @return string
     */
     protected static function compileExtends(string $template, string $tag): ?string
     {
         return $template = str_replace($tag, '', $template);
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
     protected static function addNamespace(string $template): ?string
     {
       $template = preg_replace('/Session::/','\Dune\Session\Session::',$template);
       $template = preg_replace('/Cookie::/','\Dune\Cookie\Cookie::',$template);
       $template = preg_replace('/Grape::/','\Coswat\Grapes\Grape::',$template);
       return $template;
     }
}
