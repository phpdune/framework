<?php

declare(strict_types=1);

namespace Dune\Views;

class Parser
{
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected static function parse(string $template): string
    {
        $template = self::varReplace($template);
        $template = self::varReplaceReal($template);
        $template = self::replaceForeach($template);
        $template = self::replaceFor($template);
        $template = self::replaceWhile($template);
        $template = self::replacePHP($template);
        $template = self::replaceIf($template);
        return $template;
    }
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected static function varReplace(string $template): string
    {
        $template = str_replace(
            '{{ ',
            '<?php echo htmlspecialchars(',
            $template
        );
        $template = str_replace(' }}', ', ENT_QUOTES); ?>', $template);
        return $template;
    }
    /**
     * @param  string  $template
     *
     * @return string
     */
    protected static function varReplaceReal(string $template): string
    {
        $template = str_replace('{!! ', '<?php echo ', $template);
        $template = str_replace(' !!}', '; ?>', $template);
        return $template;
    }
    /**
     *
     * @param  string  $template
     *
     * @return string
     */
     protected static function replaceForeach(string $template): string
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
     protected static function replaceFor(string $template): string
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
     protected static function replaceWhile(string $template): string
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
     protected static function replacePHP(string $template): string
     {
         $template = str_replace('{ php }', '<?php ', $template);
         $template = str_replace('{ endphp }', ' ?>', $template);
         return $template;
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
     protected static function replaceIf(string $template): string
     {
         $template = str_replace('{ if(', '<?php if( ', $template);
         $template = str_replace(') }', '): ?>', $template);
         $template = str_replace('{ else }', '<?php else: ?>', $template);
         $template = str_replace('{ elseif(', '<?php elseif(', $template);
         $template = str_replace(') }', '): ?>', $template);
         $template = str_replace('{ endif }', '<?php endif; ?>', $template);
         return $template;
     }
}