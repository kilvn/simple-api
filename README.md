# SIMPLE-API For PHP7+

临时写来写API用的，仅做参考！

#### Install

```shell
composer update -vvv
cp env.example .env
```

项目入口 `public/index.php`

nginx 伪静态规则：

```
try_files $uri $uri/ /index.php$is_args$args;
```

方法写在 `apps/api` 下， `apps/helpers` 为脚手架。

目前集成了 `zttp` `env` 扩展库，后期根据需求计划增加`mysql`扩展。
