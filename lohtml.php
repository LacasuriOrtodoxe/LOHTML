<?php

/**
 * LOElement Class
 *
 * Represents an HTML element with support for tags, attributes, content, children, and parents.
 * Provides a fluent interface for building and manipulating HTML elements programmatically.
 */
class LOElement
{
    // Properties to store the element's tag, content, children, attributes, and parent
    public $tag;        // The HTML tag name (e.g., 'div', 'span')
    public $contents;   // The text content inside the element
    public $children;   // Array of child LOElement objects
    public $attributes; // Associative array of HTML attributes (e.g., ['id' => 'my-id', 'class' => 'my-class'])
    public $parents;    // Reference to the parent LOElement object

    /**
     * Constructor
     *
     * @param string $tag   The HTML tag name (default: 'div')
     * @param LOElement|null $parent The parent element (default: null)
     */
    public function __construct($tag = 'div', $parent = null)
    {
        $this->tag        = $tag;
        $this->children   = [];
        $this->contents   = '';
        $this->attributes = [];
        $this->parents    = $parent;
        return $this;
    }

    /**
     * Set or get an attribute
     *
     * @param string $name  The attribute name (e.g., 'id', 'class')
     * @param string|null $value The attribute value (default: null, returns the current value)
     * @return LOElement|string|null Returns $this for chaining or the attribute value
     */
    public function attribute($name, $value = null)
    {
        if ($value === null) {
            // Get the attribute value if $value is null
            return $this->attributes[$name] ?? null;
        } elseif ($value === '') {
            // Remove the attribute if $value is an empty string
            if (isset($this->attributes[$name])) {
                unset($this->attributes[$name]);
            }
        } else {
            // Set the attribute value
            $this->attributes[$name] = $value;
        }
        return $this;
    }

    // Alias for attribute()
    public function attr($name, $value = null)
    {
        return $this->attribute($name, $value);
    }

    // Convenience methods for common attributes
    public function id($value = null) { return $this->attribute('id', $value); }
    public function css($style = null) { return $this->attr('style', $style); }
    public function onclick($data = null) { return $this->attr('onclick', $data); }
    public function width($data = null) { return $this->attr('width', $data); }
    public function height($data = null) { return $this->attr('height', $data); }

    /**
     * Set or get the 'class' attribute
     *
     * @param string|null $value The class value (default: null, returns the current value)
     * @return LOElement|string|null Returns $this for chaining or the class value
     */
    public function class($value = null)
    {
        return $this->attribute('class', $value);
    }

    /**
     * Add a class to the element
     *
     * @param string $value The class to add
     * @return LOElement Returns $this for chaining
     */
    public function classAdd($value)
    {
        $classlist = $this->class();
        if ($classlist == null) {
            $classlist = '';
        }
        $classlist = explode(' ', $classlist);
        if (!in_array($value, $classlist)) {
            $classlist[] = $value;
        }
        $this->class(trim(implode(' ', $classlist)));
        return $this;
    }

    /**
     * Remove a class from the element
     *
     * @param string $value The class to remove
     * @return LOElement Returns $this for chaining
     */
    public function classDel($value)
    {
        $classlist = $this->class();
        if ($classlist == null) {
            $classlist = '';
        }
        $classlist = explode(' ', $classlist);
        foreach ($classlist as $n => $c) {
            if ($c == $value) {
                unset($classlist[$n]);
            }
        }
        $this->class(trim(implode(' ', $classlist)));
        return $this;
    }

    /**
     * Set or get the text content of the element
     *
     * @param string|null $value The content (default: null, returns the current content)
     * @return LOElement|string|null Returns $this for chaining or the content
     */
    public function content($value = null)
    {
        if ($value === null) {
            return $this->contents ?? null;
        } else {
            $this->contents = $value;
            return $this;
        }
    }

    /**
     * Generate the HTML string for the element and its children
     *
     * @return string The HTML representation of the element
     */
    public function html()
    {
        $html = '';
        if($this->tag != 'lo-text') {
            $html = '<' . $this->tag;
            // Add all attributes
            foreach ($this->attributes as $_n => $_v) {
                $html .= ' ' . $_n . '="' . htmlspecialchars($_v) . '"';
            }
        }
        // Handle self-closing tags (e.g., <br />, <img />)
        if (self::isSelfClosing($this->tag)) {
            $html .= '/>';
        } else {
            if($this->tag != 'lo-text') {
                // Open tag
                $html .= '>';
            }
            // Add content
            $html .= $this->contents;
            // Recursively add children
            foreach ($this->children as $child) {
                $html .= $child->html();
            }
            if($this->tag != 'lo-text') {
                // Close tag
                $html .= '</' . $this->tag . '>';
            }
        }
        return $html;
    }

    /**
     * Add a child element to this element
     *
     * @param string $tag The tag and optional ID/classes (e.g., 'div#my-id.my-class')
     * @return LOElement The newly created child element
     */
    public function add($tag = 'div')
    {
        $tag = preg_replace('/\s+/', ' ', $tag);
        $tag = explode(' ', $tag);
        foreach ($tag as $k => $data) {
            if ($k == 0) {
                // First part is the tag name
                $element = new LOElement($data, $this);
            } elseif (str_starts_with($data, '#')) {
                // Parts starting with '#' are IDs
                $element->id(substr($data, 1));
            } elseif (str_starts_with($data, '.')) {
                // Parts starting with '.' are classes
                $element->classAdd(substr($data, 1));
            }
        }
        $this->append($element);
        return $element;
    }

    /**
     * Set or get the parent element
     *
     * @param LOElement|null $par The parent element (default: null, returns the current parent)
     * @return LOElement|null Returns $this for chaining or the parent element
     */
    public function parent($par = null)
    {
        if ($par != null) {
            $this->parents = $par;
        }
        return $this->parents;
    }

    /**
     * Prepend a child element or text to this element
     *
     * @param LOElement|string $element The element or text to prepend
     * @return LOElement Returns $this for chaining
     */
    public function prepend($element)
    {
        if (is_object($element)) {
            $element->parent($this);
            array_unshift($this->children, $element);
        } else {
            $this->contents = $element . $this->contents;
        }
        return $this;
    }

    /**
     * Append a child element or text to this element
     *
     * @param LOElement|string $element The element or text to append
     * @return LOElement Returns $this for chaining
     */
    public function append($element)
    {
        if (is_object($element)) {
            $element->parent($this);
            $this->children[] = $element;
        } else {
            $this->contents .= $element;
        }
        return $this;
    }

    /**
     * Remove a child element from this element
     *
     * @param LOElement|null $el The element to remove (default: null, removes this element from its parent)
     * @return void
     */
    public function remove($el = null)
    {
        if (isset($el)) {
            foreach ($this->children as $a => $b) {
                if ($b == $el) {
                    unset($this->children[$a]);
                }
            }
        } elseif ($this->parents != null) {
            $this->parent()->remove($this);
        }
    }

    /**
     * Check if a tag is self-closing (e.g., <br>, <img>)
     *
     * @param string $tag The tag name
     * @return bool True if the tag is self-closing
     */
    public static function isSelfClosing($tag)
    {
        $list = ['base', 'meta', 'area', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr', 'command', 'keygen', 'menuitem', 'frame'];
        return in_array($tag, $list);
    }
    
    // --- Text type ---
    public function text() { return $this->add('lo-text'); }

    // --- Convenience methods for common HTML tags ---

    public function link($href, $data = '') { return $this->add('a ' . $data)->attribute('href', $href); }
    public function a($href, $data = '') { return $this->add('a ' . $data)->attribute('href', $href); }
    public function image($src, $data = '') { return $this->add('img ' . $data)->attribute('src', $src); }
    public function div($data = '') { return $this->add('div ' . $data); }
    public function span($data = '') { return $this->add('span ' . $data); }
    public function br() { return $this->add('br'); }
    public function hr($data = '') { return $this->add('hr ' . $data); }
    public function figure($data = '') { return $this->add('figure ' . $data); }

    public function video($src, $controls = true, $poster = '', $data = '')
    {
        $el = $this->add('video ' . $data);
        $el->attribute('src', $src);
        if ($poster !== '') $el->attribute('poster', $poster);
        if ($controls) $el->attribute('controls', 'controls');
        return $el;
    }

    public function audio($src, $controls = true, $data = '')
    {
        $el = $this->add('audio ' . $data);
        $el->attribute('src', $src);
        if ($controls) $el->attribute('controls', 'controls');
        return $el;
    }

    public function iframe($src, $data = '') { return $this->add('iframe ' . $data)->attribute('src', $src); }
    public function script($src = null, $type = null)
    {
        $el = $this->add('script');
        if ($type !== null) $el->attribute('type', $type);
        if ($src !== null) $el->attribute('src', $src);
        return $el;
    }

    // --- Common HTML tags ---
    public function h1($data = '') { return $this->add('h1 ' . $data); }
    public function h2($data = '') { return $this->add('h2 ' . $data); }
    public function h3($data = '') { return $this->add('h3 ' . $data); }
    public function h4($data = '') { return $this->add('h4 ' . $data); }
    public function h5($data = '') { return $this->add('h5 ' . $data); }
    public function h6($data = '') { return $this->add('h6 ' . $data); }

    public function p($data = '') { return $this->add('p ' . $data); }
    public function pre($data = '') { return $this->add('pre ' . $data); }
    public function code($data = '') { return $this->add('code ' . $data); }
    public function blockquote($data = '') { return $this->add('blockquote ' . $data); }
    public function em($data = '') { return $this->add('em ' . $data); }
    public function strong($data = '') { return $this->add('strong ' . $data); }
    public function i($data = '') { return $this->add('i ' . $data); }
    public function b($data = '') { return $this->add('b ' . $data); }
    public function small($data = '') { return $this->add('small ' . $data); }
    public function mark($data = '') { return $this->add('mark ' . $data); }

    public function ul($data = '') { return $this->add('ul ' . $data); }
    public function ol($data = '') { return $this->add('ol ' . $data); }
    public function li($data = '') { return $this->add('li ' . $data); }
    public function dl($data = '') { return $this->add('dl ' . $data); }
    public function dt($data = '') { return $this->add('dt ' . $data); }
    public function dd($data = '') { return $this->add('dd ' . $data); }

    public function table($data = '') { return $this->add('table ' . $data); }
    public function thead($data = '') { return $this->add('thead ' . $data); }
    public function tbody($data = '') { return $this->add('tbody ' . $data); }
    public function tfoot($data = '') { return $this->add('tfoot ' . $data); }
    public function tr($data = '') { return $this->add('tr ' . $data); }
    public function th($data = '') { return $this->add('th ' . $data); }
    public function td($data = '') { return $this->add('td ' . $data); }
    public function caption($data = '') { return $this->add('caption ' . $data); }

    public function nav($data = '') { return $this->add('nav ' . $data); }
    public function header($data = '') { return $this->add('header ' . $data); }
    public function main($data = '') { return $this->add('main ' . $data); }
    public function section($data = '') { return $this->add('section ' . $data); }
    public function article($data = '') { return $this->add('article ' . $data); }
    public function aside($data = '') { return $this->add('aside ' . $data); }
    public function footer($data = '') { return $this->add('footer ' . $data); }
    public function details($data = '') { return $this->add('details ' . $data); }
    public function summary($data = '') { return $this->add('summary ' . $data); }
}

/**
 * LOHTML Class
 *
 * Represents an HTML document with <html>, <head>, and <body> elements.
 * Provides methods for setting the title, description, styles, scripts, and other meta tags.
 */
class LOHTML
{
    public $root;   // The <html> element
    public $head;   // The <head> element
    public $body;   // The <body> element
    private $head_title;      // The <title> element
    private $head_description; // The meta description element
    private $head_responsive;  // The viewport meta element

    public function __construct()
    {
        // Initialize the root, head, and body elements
        $this->root = new LOElement('html');
        $this->head = new LOElement('head');
        $this->body = new LOElement('body');

        // Use a placeholder to split the root element for inserting head and body
        $this->root->contents = '%%%%%%%%%LO%%%%%%%%%';

        // Initialize head elements
        $this->head_title = $this->head->add("title");
        $this->head_description = $this->head->add("meta");
        $this->head_description->attribute("name", "description");
        $this->head_responsive = $this->head->add("meta");
        $this->head_responsive->attribute("name", "viewport");
    }

    // --- Head-related methods ---

    /**
     * Set the document title
     *
     * @param string $title The title text
     */
    public function title($title)
    {
        $this->head_title->content($title);
    }

    /**
     * Add a stylesheet link to the head
     *
     * @param string $url The URL of the stylesheet
     * @return LOElement The <link> element
     */
    public function style($url)
    {
        return $this->head->add('link')->attribute("rel", "stylesheet")->attribute("href", $url);
    }

    /**
     * Add a script to the head
     *
     * @param string $url The URL of the script
     * @return LOElement The <script> element
     */
    public function script($url)
    {
        return $this->head->add('script')->attribute("src", $url);
    }

    /**
     * Add inline CSS to the head
     *
     * @param string $style The CSS code
     * @return LOElement The <style> element
     */
    public function css($style = '')
    {
        return $this->head->add('style')->content($style);
    }

    /**
     * Add a meta tag to the head
     *
     * @param string|null $name The meta name (default: null)
     * @param string|null $content The meta content (default: null)
     * @return LOElement The <meta> element
     */
    public function meta($name = null, $content = null)
    {
        $el = $this->head->add('meta');
        if ($name !== null) $el->attribute('name', $name);
        if ($content !== null) $el->attribute('content', $content);
        return $el;
    }

    /**
     * Add inline JavaScript to the head
     *
     * @param string $style The JavaScript code
     * @return LOElement The <script> element
     */
    public function js($style = '')
    {
        return $this->head->add('script')->content($style);
    }

    /**
     * Set the document language
     *
     * @param string $lang The language code (e.g., 'en')
     */
    public function language($lang)
    {
        $this->root->attribute("lang", $lang);
    }

    /**
     * Set the meta description
     *
     * @param string $description The description text
     */
    public function description($description)
    {
        $this->head_description->attribute("content", $description);
    }

    /**
     * Enable or disable responsive viewport meta tag
     *
     * @param bool $bool True to enable, false to disable
     */
    public function responsive($bool)
    {
        if ($bool) {
            $this->head_responsive->attribute("content", "width=device-width, initial-scale=1.0");
        } else {
            $this->head_responsive->attribute("content", "");
        }
    }

    /**
     * Generate a unique ID
     *
     * @return string A unique ID
     */
    public static function id()
    {
        return md5(uniqid(mt_rand(), true));
    }

    /**
     * Generate the full HTML document
     *
     * @return string The HTML document as a string
     */
    public function html()
    {
        $html  = '<!DOCTYPE html>';
        $main  = $this->root->html();
        // Split the root HTML at the placeholder to insert head and body
        $main  = explode('%%%%%%%%%LO%%%%%%%%%', $main);
        $html .= $main[0];
        $html .= $this->head->html();
        $html .= $this->body->html();
        $html .= $main[1];
        return $html;
    }
}
