# base91

base91 编解码的PHP实现，支持版本php>=7.1

基于[base91](http://base91.sourceforge.net/), 增加了16进制编解码，可用于16进制编码缩短

## 安装
```bash
composer require axpwx/base91
```

## 使用
### 编码普通字符串

```php
<?php
use Axpwx\Base91;

Base91::encode('encode this string');
//=> toX<5+UCmUW6GFso^zZ2(.A
```

### 解码普通字符串

```php
<?php
use Axpwx\Base91;

Base91::decode('toX<5+UCmUW6GFso^zZ2(.A');
//=> encode this string
```

## 编码16进制字符串

```php
<?php
use Axpwx\Base91;

Base91::encodeHex('356c1772936a14d19df15ae2f48342cf6ce8e187');
//=> fiGv9N=Y+[,*taK{GHUSo/fsI
//待编码的16进制字符串支持负数，支持非偶数前缀补0
```

## 解码16进制字符串

```php
<?php
use Axpwx\Base91;

Base91::decodeHex('fiGv9N=Y+[,*taK{GHUSo/fsI');
//=> 356c1772936a14d19df15ae2f48342cf6ce8e187
```

## License
MIT License (MIT). Please see [License File](https://github.com/axpwx/base91/blob/master/LICENSE) for more information.
