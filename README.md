Phapr
=====
Phapr is an expressive PHP project build framework.

[![Build Status](https://travis-ci.org/panlatent/phapr.svg)](https://travis-ci.org/panlatent/phapr)
[![Coverage Status](https://coveralls.io/repos/github/panlatent/phapr/badge.svg)](https://coveralls.io/github/panlatent/phapr)
[![Latest Stable Version](https://poser.pugx.org/phapr/phapr/v/stable.svg)](https://packagist.org/packages/phapr/phapr)
[![Total Downloads](https://poser.pugx.org/phapr/phapr/downloads.svg)](https://packagist.org/packages/phapr/phapr) 
[![Latest Unstable Version](https://poser.pugx.org/phapr/phapr/v/unstable.svg)](https://packagist.org/packages/phapr/phapr)
[![License](https://poser.pugx.org/phapr/phapr/license.svg)](https://packagist.org/packages/phapr/phapr)

    ! Notice: Phapr does not have a stable version, don't use it in production environments!

What's Phapr?
-------------
Phapr 是一个 PHP 项目的构建框架。它直接使用 PHP 代码作为构建语言，并基于强大的 `symfony/expression` 组件定制了 DSL，围绕现代 PHP 
项目的开发实践，提供了丰富的功能与模块。

最为重要的是：

 > Phapr 更注重你的构建体验

使用 Phapr 意味着你可以：

+ 直接使用的所有 PHP 特性并利用现有 PHP 代码
+ 编写更有表达力的构建脚本
+ 轻松的拓展 Phapr 并获得 IDE 支持

长久以来，[Phing](https://www.phing.info/) 是构建 PHP 项目的首选工具（可能也是唯一的）。它是一个基于 Apache Ant 的构建工具，有着丰富
的模块和第三方资源，在其他与系统集成和 IDE 支持方面也有着得天独厚的优势。但是为了编写构建逻辑，必须学习编写冗长 XML，且难以使用标准的方式拓展。
PHP 不同与 Java， 它是一个灵活的语言，可以直接用来编写构建脚本。

Phapr 出现的目的并不是替代 Phing，而是提供了另一种选择。Phing 依然是一个强大的构建系统，如果你想拥有一个稳定的、能够在各方面获得良好支持
的构建系统，那么仍旧它是你的首选工具。

但是，如果你想有一点改变，请尝试一下 **Phapr**。

Features
--------

Installation
------------
It's recommended that you use [Composer](https://getcomposer.org/) to install this project.

```bash
$ composer global require --prefer-dist phapr/phapr 
```

This will install the library and all required dependencies. The project requires **PHP 7.1** or newer.

Suggest you add the Composer global `vendor/bin` to the system environment variable:

   > Please replace COMPOSER_HOME to your composer global path.
   
Bash:
```bash
echo 'export PATH="COMPOSER_HOME/vendor/bin:$PATH"' >> ~/.bashrc
```

Zsh:
```bash
echo 'export PATH="COMPOSER_HOME/vendor/bin:$PATH"' >> ~/.zshrc
```

Usage
-----

See the example [Simple Build](examples/simple-build.php) .

Documentation
-------------

+ Read the Documentation: [English](https://docs.phapr.com/) , [Chinese](https://docs.phapr.com/zh/latest/) .

Community
---------

License
-------
The Phapr is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
