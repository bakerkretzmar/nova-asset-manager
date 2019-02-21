Nova Asset Manager
==================

A tool and field to manage files in [Laravel Nova](https://nova.laravel.com). Very heavily based on [`InfinetyES/Nova-Filemanager`](https://github.com/InfinetyES/Nova-Filemanager).

## Usage

By default, this package will try to use a custom `assets` filesystem disk. To use a different disk, add a top-level `asset_manager_disk` key to `config/filesystems.php`, for example:

```php
'asset_manager_disk' => env('ASSET_MANAGER_DISK', 'assets')
```

## Credits

- [Nova-Filemanager](https://github.com/InfinetyES/Nova-Filemanager)

## License

The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for more information.
