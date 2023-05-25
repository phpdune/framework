<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dune\Pine;

use Dune\Pine\CaptureLayout;
use Dune\Pine\Parser\Parser;

class PineCompiler
{
    /**
     * Dune\Pine\CaptureLayout instance
     *
     * @var CaptureLayout
     */
    protected CaptureLayout $layoutCompiler;

    /**
     * CaptureLayout
     * Parser
     *
     * instance setting
     */
    public function __construct(CaptureLayout $capture)
    {
        $this->layoutCompiler = $capture;
    }
    /**
     * converting all pine syntax to php
     *
     * @param  string  $template
     *
     * @return string
     */
    public function compile(string $template): string
    {
        $template = $this->layoutCompiler->layout($template);
        $template = $this->compileError($template);
        $template = $this->compileSession($template);
        $template = $this->compileCsrf($template);
        $template = $this->varCompile($template);
        $template = $this->varCompileReal($template);
        $template = $this->compileForeach($template);
        $template = $this->compileFor($template);
        $template = $this->compileWhile($template);
        $template = $this->compilePHP($template);
        $template = $this->compileIf($template);
        $template = $this->compileIsset($template);
        $template = $this->compileEmpty($template);
        $template = $this->addNamespace($template);
        return $template;
    }
    /**
     * <p-error name> {{ $message }} </error>
     * if the name input field didn't passed the validation the error message will get in the $message variable
     *
     * @param  string  $template
     *
     * @return string
     */
     public function compileError(string $template): string
     {
         $template = preg_replace('/<p-error\s+([a-zA-Z0-9_-]+)>(.*?){{\s*\$message\s*}}(.*?)<\/p-error>/s', '<?php if (errorHas(\'$1\')) : ?>\n$2{{ error(\'$1\') }}$3\n<?php endif; ?>', $template);
         return $template;
     }
    /**
     * if you want to show a value from session if the session exists
     * <p-session key> {{ $valur }} </p-session>
     *
     * @param  string  $template
     *
     * @return string
     */
     public function compileSession(string $template): string
     {
         $template = preg_replace('/<p-session\s+([a-zA-Z0-9_-]+)>(.*?){{\s*\$value\s*}}(.*?)<\/p-session>/s', '<?php if (Session::has(\'$1\')) : ?>\n$2{{ Session::get(\'$1\') }}$3\n<?php endif; ?>', $template);
         return $template;
     }
    /**
     * add a hidden method containing csrf token value
     * <p-csrf>
     *
     * @param  string  $template
     *
     * @return string
     */
     public function compileCsrf(string $template): string
     {
         $template = preg_replace('/<p-csrf>/', '{! csrf() !}', $template);
         return $template;
     }
    /**
     * {{ $var }} will output htmlspecialchars escaped string
     *
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
     * {! $var !} equivalent to echo($var)
     *
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
     * <p-foreach $users as $user>
     * do something
     * </p-foreach>
     *
     * will convert to php foreach loop
     *
     * @param  string  $template
     *
     * @return string
     */
     protected function compileForeach(string $template): string
     {

         $template = preg_replace('/<p-foreach\s+(\S+)\s+as\s+(\S+)\s*>/', '<?php foreach($1 as $2): ?>', $template);
         $template = preg_replace('/<\/p-foreach>/', '<?php endforeach; ?>', $template);
         return $template;
     }
    /**
     * @param  string  $template
     *
     * @return string
     */
     protected function compileFor(string $template): string
     {
         $template = preg_replace('/\{\s*for\(/', '<?php for(', $template);
         $template = preg_replace('/\)\s*\}/', '): ?>', $template);
         $template = preg_replace('/\{\s*endfor\s*\}/', '<?php endfor; ?>', $template);

         return $template;
     }
    /**
     * <p-while condition>
     * do something
     * </p-while>
     *
     * will convert to php while loop
     *
     * @param  string  $template
     *
     * @return string
     */
     protected function compileWhile(string $template): string
     {
         $template = preg_replace('/<p-while\s+(.*?)\s*>(.*?)<\/p-while>/s', '<?php while($1): ?>$2<?php endwhile; ?>', $template);

         return $template;
     }
    /**
     * <php></php> equivalent to <?php?>
     *
     * @param  string  $template
     *
     * @return string
     */
     protected function compilePHP(string $template): string
     {
         $template = preg_replace('/<php>/', '<?php ', $template);
         $template = preg_replace('/<\/php>/', ' ?>', $template);

         return $template;
     }
    /**
     * <p-if condition>
     * <p-elseif condition>
     * <p-else>
     * </p-if>
     *
     * will conve to php if statement
     *
     * @param  string  $template
     *
     * @return string
     */
     protected function compileIf(string $template): string
     {
         $template = preg_replace('/<p-if\s+(.*?)>\s*(.*?)\s*<\/p-if>/s', '<?php if($1): ?> $2 <?php endif; ?>', $template);
         $template = preg_replace('/<p-elseif\s*(.*?)\s*>/', '<?php elseif($1): ?>', $template);
         $template = preg_replace('/<p-else>/', '<?php else: ?>', $template);

         return $template;
     }

    /**
     * use static classes without namespace in pine
     *
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
    /**
     * <p-isset $var>
     * do something
     * </p-isset>
     *
     * equivalent to if(isset($var))..
     *
     * @param  string  $template
     *
     * @return string
     */
     public function compileIsset(string $template): string
     {
         $template = preg_replace('/<p-isset\s+(\$\w+)\s*>/', '<?php if(isset($1)) : ?>', $template);
         $template = preg_replace('/<\/p-isset>/', '<?php endif; ?>', $template);
         return $template;
     }
    /**
     * <p-empty $var >
     * do something
     * </p-empty>
     *
     * equivalent to if(empty($var))...
     *
     * @param  string  $template
     *
     * @return string
     */
     public function compileEmpty(string $template): string
     {
         $template = preg_replace('/<p-empty\s+(\$\w+)\s*>/', '<?php if(empty($1)) : ?>', $template);
         $template = preg_replace('/<\/p-empty>/', '<?php endif; ?>', $template);
         return $template;
     }
}