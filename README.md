# LOHTML Documentation

Fluent PHP classes for programmatically building and manipulating HTML documents and elements with a chainable, object-oriented API.

---

## **What is LOHTML?**

**LOHTML** is a simple, **fluent PHP library** for building HTML documents and websites **programmatically**. It lets you create and manipulate HTML using **chainable methods**, so you can write clean, readable, and maintainable code.

Think of it as a **PHP-based HTML builder** — no need to write raw HTML strings or deal with complex templating engines.

---

## 🌐 Demo

Test LOHTML live on our official webpage: 
🔗 [LOHTML Demo](https://lacasuriortodoxe.ro/academia/open-source/lohtml/)

More open-source projects: 
🔗 [Open Source projects](https://lacasuriortodoxe.ro/academia/open-source/)

---

## 🙏 Supporters

We are grateful to our supporters:

- **[NETCreator Hosting](https://netcreator.us)** and **[NETCreator Regio](http://regio.netcreator.us)**, two web hosting companies providing free and paid hosting in multiple locations.

---

## **Quick Start**

### **1. Initialize a Website**

Start by creating a new `LOHTML` object. This represents your entire HTML document.

```php
$site = new LOHTML();
```

---

### **2. Configure the Document**

Set up the **title, language, description, meta tags, styles, and scripts** for your website.

```php
$site->language('en');
$site->title('My Awesome Website');
$site->description('A website built with LOHTML.');
$site->meta('charset', 'utf-8');
$site->style('styles.css');          // Add external CSS
$site->script('script.js');          // Add external JS
$site->css('body { background: #f0f0f0; }');  // Add inline CSS
$site->js('console.log("Hello!");'); // Add inline JS
$site->responsive(true);             // Enable mobile-friendly viewport
```

---

### **3. Build the Body**

Use `$site->body` to add content to the `<body>` of your website. You can **chain methods** to create nested elements.

#### **Basic Example**

```php
$site->body->h1()->content('Welcome to My Website!');
$site->body->p()->content('This is a paragraph.');
$site->body->link('https://example.com')->content('Visit Example.com');
```

#### **Adding Classes and IDs**

Use `.` for classes and `#` for IDs when adding elements.

```php
$container = $site->body->div('.container #main');
$container->h2()->content('Section Title');
$container->p()->content('Some text inside the container.');
```

#### **Nested Elements**

You can **chain methods** to create nested structures.

```php
$site->body->div('.menu .blue')
    ->ul()
        ->li()->link('#home')->content('Home')
        ->parent() // Go back to <ul>
        ->li()->link('#about')->content('About');
```

#### **Real-World Example**

Here’s how you can build a **complete webpage** with LOHTML:

```php
// Initialize
$site = new LOHTML();

// Configure
$site->language('en');
$site->title('Sample Website');
$site->description('This is an example for a website built with LOHTML.');
$site->meta('charset', 'utf-8');
$site->style('sample.css');
$site->script('sample.js');
$site->css('.container { background: yellow; padding: 20px; width: 500px; margin: 20px auto; }');
$site->js('alert("I am using LOHTML!");');
$site->responsive(true);
$site->meta('author', 'Lacasuri Ortodoxe');

// Build the body
$container = $site->body->div('.container');
$container->p()->link('https://lacasuriortodoxe.ro/academia/open-source/')->content('Click here to open a sample website!');
$container->p()->b()->i()->content('Bold Italic')->parent()->text()->content(' and ')->parent()->u()->content('Bold Underline.');
$container->p()->image('sample.png');

// Print the website
echo $site->html();
```

---

## **Working with `$site->head`**

You can also **directly manipulate the `<head>`** section of your document.

```php
$site->head->meta('keywords', 'PHP, HTML, LOHTML');
$site->head->add('link')
    ->attribute('rel', 'icon')
    ->attr('href', 'favicon.ico');
```

---

## **LOElement - Standalone Usage (Advanced)**

While **LOHTML** is the main way to build websites, you can also use **LOElement** directly for custom HTML fragments.

### **Creating an Element**

```php
$div = new LOElement('div');
$div->id('my-div');
$div->class('box');
$div->content('Hello, world!');

echo $div->html();
// Output: <div id="my-div" class="box">Hello, world!</div>
```

### **Adding Children**

```php
$div = new LOElement('div');
$div->add('p')->content('First paragraph');
$div->add('p')->content('Second paragraph');

echo $div->html();
// Output: <div><p>First paragraph</p><p>Second paragraph</p></div>
```

### **Chaining Methods**

```php
$div = new LOElement('div');
$div->id('container')
    ->class('main')
    ->add('h1')->content('Title')
    ->parent() // Go back to div
    ->add('p')->content('Content');

echo $div->html();
// Output: <div id="container" class="main"><h1>Title</h1><p>Content</p></div>
```

---

## **Method Reference**

### **LOHTML Methods**

These methods are used to **configure the entire HTML document**.


| Method                  | Description                             | Example                                       |
| ----------------------- | --------------------------------------- | --------------------------------------------- |
| `title($text)`          | Sets the `<title>` of the page.         | `$site->title('My Page');`                    |
| `language($lang)`       | Sets the `lang` attribute for `<html>`. | `$site->language('en');`                      |
| `description($text)`    | Sets the meta description.              | `$site->description('My page description.');` |
| `meta($name, $content)` | Adds a meta tag.                        | `$site->meta('author', 'John Doe');`          |
| `style($url)`           | Adds a CSS stylesheet.                  | `$site->style('styles.css');`                 |
| `script($url)`          | Adds a JavaScript file.                 | `$site->script('script.js');`                 |
| `css($code)`            | Adds inline CSS.                        | `$site->css('body { color: red; }');`         |
| `js($code)`             | Adds inline JavaScript.                 | `$site->js('alert("Hi!");');`                 |
| `responsive($bool)`     | Enables/disables responsive viewport.   | `$site->responsive(true);`                    |
| `html()`                | Generates the full HTML document.       | `echo $site->html();`                         |


---

### **LOElement Methods**

These methods are used to **build and manipulate HTML elements** (via `$site->body` or `$site->head`).

#### **Core Methods**


| Method                     | Description                                             | Example                                   |
| -------------------------- | ------------------------------------------------------- | ----------------------------------------- |
| `add($tag)`                | Adds a child element. Use `.` for classes, `#` for IDs. | `$site->body->add('div.container#main');` |
| `content($text)`           | Sets the text content.                                  | `$div->content('Hello!');`                |
| `id($value)`               | Sets the `id` attribute.                                | `$div->id('my-id');`                      |
| `class($value)`            | Sets the `class` attribute.                             | `$div->class('my-class');`                |
| `classAdd($value)`         | Adds a class.                                           | `$div->classAdd('new-class');`            |
| `classDel($value)`         | Removes a class.                                        | `$div->classDel('old-class');`            |
| `attribute($name, $value)` | Sets an attribute.                                      | `$div->attribute('data-role', 'button');` |
| `attr($name, $value)`      | Short for `attribute()`.                                | `$div->attr('style', 'color: red;');`     |
| `parent()`                 | Returns the parent element.                             | `$child->parent()->add('p');`             |
| `prepend($element)`        | Prepends a child or text.                               | `$div->prepend('First ');`                |
| `append($element)`         | Appends a child or text.                                | `$div->append(' Last');`                  |
| `remove($element)`         | Removes a child element.                                | `$div->remove($child);`                   |
| `html()`                   | Renders the element as HTML.                            | `echo $div->html();`                      |


#### **Convenience Methods for Common Tags**

You can use these **shortcut methods** to quickly add common HTML elements:

- **Text & Links**: `text()`, `link($href)`, `a($href)`, `image($src)`
- **Headings**: `h1()`, `h2()`, `h3()`, `h4()`, `h5()`, `h6()`
- **Text Formatting**: `p()`, `strong()`, `em()`, `b()`, `i()`, `u()`, `mark()`, `small()`, `code()`, `pre()`, `blockquote()`
- **Lists**: `ul()`, `ol()`, `li()`, `dl()`, `dt()`, `dd()`
- **Tables**: `table()`, `thead()`, `tbody()`, `tfoot()`, `tr()`, `th()`, `td()`, `caption()`
- **Media**: `video($src, $controls, $poster)`, `audio($src, $controls)`, `iframe($src)`
- **Structural**: `div()`, `span()`, `header()`, `footer()`, `nav()`, `section()`, `article()`, `aside()`, `main()`, `figure()`, `hr()`, `br()`
- **Scripts & Styles**: `script($src, $type)`, `style($href)`

**Example:**

```php
$site->body
    ->h1()->content('Welcome')
    ->p()->content('This is a paragraph with ')
    ->strong()->content('bold text')
    ->parent()
    ->text()->content(' and ')
    ->em()->content('italic text')
    ->parent()
    ->text()->content('.');
```

---

## **Tips & Tricks**

### **1. Chaining for Readability**

Use **method chaining** to keep your code clean:

```php
$site->body->div('.container')
    ->h1()->content('Title')
    ->p()->content('Paragraph 1')
    ->p()->content('Paragraph 2');
```

### **2. Use `parent()` to Navigate**

When you need to **go back up** the DOM tree, use `parent()`:

```php
$ul = $site->body->ul();
$ul->li()->content('Item 1');
$ul->li()->content('Item 2');
// Or with chaining:
$site->body->ul()
    ->li()->content('Item 1')
    ->parent()
    ->li()->content('Item 2');
```

### **3. Shorthand for IDs and Classes**

Use `.` for classes and `#` for IDs when adding elements:

```php
$site->body->div('.container #main');
// Equivalent to:
$div = $site->body->add('div');
$div->class('container');
$div->id('main');
```

### **4. Dynamic Content**

Use **loops and variables** to generate dynamic content:

```php
$items = ['Home', 'About', 'Contact'];
$ul = $site->body->ul();
foreach ($items as $item) {
    $ul->li()->a("#$item")->content($item);
}
```

---

## **Full Example: Building a Complete Page**

```php
// Initialize
$site = new LOHTML();

// Configure the document
$site->language('en');
$site->title('My Website');
$site->description('A website built with LOHTML.');
$site->meta('charset', 'utf-8');
$site->style('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
$site->script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js');
$site->responsive(true);

// Build the header
$header = $site->body->header('.bg-dark .text-white .p-4');
$header->div('.container')
    ->h1()->content('Welcome to My Website')
    ->p()->content('A simple, clean, and modern website.');

// Build the navigation
$nav = $site->body->nav('.bg-light.p-2');
$nav->div('.container')
    ->ul('.nav')
        ->li()->a('#home')->content('Home')
        ->parent()
        ->li()->a('#about')->content('About')
        ->parent()
        ->li()->a('#contact')->content('Contact');

// Build the main content
$main = $site->body->main('.container .my-4');
$main->section()
    ->h2()->content('About Us')
    ->p()->content('We are a team of developers who love building cool things with PHP and HTML.');

$main->section()
    ->h2()->content('Our Services')
    ->ul()
        ->li()->content('Web Development')
        ->li()->content('PHP Programming')
        ->li()->content('HTML/CSS Design');

// Build the footer
$footer = $site->body->footer('.bg-dark .text-white .p-4');
$footer->div('.container .text-center')
    ->p()->content('&copy; 2026 My Website. All rights reserved.');

// Print the website
echo $site->html();
```

---

## **Summary**

- **LOHTML** is for **building entire HTML documents** (title, meta, styles, scripts, etc.).
- `$site->body` and `$site->head` are **LOElement** objects for adding content.
- Use **chainable methods** to create nested structures easily.
- **Shorthand syntax** (`.class`, `#id`) makes adding elements quick and intuitive.
- **LOElement** can also be used **standalone** for custom HTML fragments.

---

## **Need Help?**

- Check the **method reference** for all available options.
- Experiment with **chaining** and **navigation** (`parent()`).
- Use **loops and variables** for dynamic content.

Happy coding! 🚀
