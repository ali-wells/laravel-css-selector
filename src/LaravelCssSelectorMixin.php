<?php

namespace BigBoca\LaravelCssSelector;

use DOMXPath;
use DOMDocument;
use DOMNodeList;
use Illuminate\Support\Str;
use Illuminate\Testing\Assert as PHPUnit;
use Symfony\Component\CssSelector\CssSelectorConverter;

class LaravelCssSelectorMixin
{
    public function getSelectorContents()
    {
        return function (string $selector): DOMNodeList {
            $dom = new DOMDocument();

            @$dom->loadHTML(
                mb_convert_encoding($this->getContent(), 'HTML-ENTITIES', 'UTF-8'),
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );

            $xpath = new DOMXPath($dom);
            $converter = new CssSelectorConverter();
            $xpathSelector = $converter->toXPath($selector);

            $elements = $xpath->query($xpathSelector);

            return $elements;
        };
    }

    /**
     * Assert that the element matching the given selector has the given value.
     *
     * @return \Closure
     */
    public function assertValue()
    {
        return function (string $selector, string $value) {
            $selectorContents = $this->getSelectorContents($selector);

            if (empty($selectorContents)) {
                PHPUnit::fail("The selector '{$selector}' was not found in the response.");
            }

            foreach ($selectorContents as $element) {
                if (Str::contains($element->textContent, $value)) {
                    PHPUnit::assertTrue(true);

                    return $this;
                }
            }

            PHPUnit::fail("The selector '{$selector}' did not contain the value '{$value}'.");

            return $this;
        };
    }

    /**
     * Assert that the element matching the given selector is present.
     *
     * @return \Closure
     */
    public function assertPresent()
    {
        return function (string $selector) {
            $selectorContents = $this->getSelectorContents($selector);

            if (!$selectorContents->length) {
                PHPUnit::fail("The selector '{$selector}' was not found in the response.");
                return $this;
            }

            PHPUnit::assertTrue(true);

            return $this;
        };
    }

    /**
     * Assert that the element matching the given selector is not visible.
     *
     * @return \Closure
     */
    public function assertMissing()
    {
        return function (string $selector) {
            $selectorContents = $this->getSelectorContents($selector);

            if ($selectorContents->length) {
                PHPUnit::fail("The selector '{$selector}' was found in the response and was expected to be missing.");
                return $this;
            }

            PHPUnit::assertTrue(true);

            return $this;
        };
    }
}
