Так же [тестовое задание с переписанным кодом для ABCP](https://github.com/Dead4W/noda_soft_hr_test/tree/feature/ABCP-1-refactoring/php/v3)

### В этом проекте используется несколько интересных решений:
 - [Нестандартная Laravel структура файлов](app/Common)
   - Контроллеры, ресурсы и тд разделены по сущностям. Например UserController следует искать в `app/User/Controller`.
   - Общие абстрактные классы/контракты или хелперы находятся в `app/Common`
     - Все основные файлы фреймворка вынесены в `app/Common/Framework`
 - [Глубокие ресурсы](app/Common/DeepJsonResource), если в обычном проекте нужно делать `return CarResource::collection($cars)` то тут можно вернуть `return new ResponseResource($cars)`
   - Это дает стандартизацию ответов
   - Если $cars - это `Paginator` то в таком случае автоматически прокинется информация о `current_page`, `last_page`, `limit`, `total`
   - Если у Car есть relation, то он также будет завернут в свой ресурс, например Car->user будет завернут в UserResource.
 - [Архитектура драйверов](app/Car)
   - У каждой фичи есть контракт и описание
   - У каждого драйвера свой набор фич
   - Все фичи по умолчанию должны вызываться в CarDriverOrchestrator, в нем менеджмент фичами. Например у драйвера может быть фича `OpenDoor` и `OpenDoors`, в зависимости от зависимого API. В таком случае в DriverOrchestrator у нас есть функция `openDoors(array $doorNumbers)` и внутри проверка на сущестование фичей.
 - [Пример оптимизации ресурсивной функции](app/RecursiveVsNotRecursive)
   - Дан массив бесконечной глубины дерева файлов, на выходе плоский массив путей файлов
   - Пример оптимизации через ссылки, указатели и стек.
   - 2x быстрее, ~2x меньше памяти

### TODO list:
 - ~~Упростить DeepJsonResource~~
   - ~~Рефакторинг на упрощение~~
 - Дописать `CarDriverOrchestrator` для текущих фич
 - Дописать borrow/release для Car
   - Использовать `Semaphore`
   - Использовать `DB::transaction`
   - Юзер может вызывать фичи только если он занял машину
   - Список машин юзера
   - Проверка занятых юзером машин
 - Добавить ещё пару фич для демонстрации, например `ApiFeature`
 - Сделать Unit тесты
   - ~~На DeepJsonResource~~
   - На Car
   - На драйвер
 - Добавить `Actions` в Car и демки для них
 - Добавить работу с валютами, кошельками, деньгами у юзера
   - Создание кошельков RUB/USD
   - Передача денег между кошельками
     - ? Блокчейн с историей либо обычный баланс + логи
   - Динамический курс, пересчет раз в сутки пн-пт
     - Во избежания багов следуется сделать все валютные сделки через main валюту, например USD. Если по какой-то причине RUB => USD => AMD => RUB выдаст не тоже количество денег из-за float ошибок или ещё чего-то, будет плохо.
