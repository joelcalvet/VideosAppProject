# Projecte VideosApp

## Descripció del projecte

**VideosApp** és una aplicació desenvolupada per gestionar vídeos de manera eficient. Utilitzem **TDD (Test-Driven Development)** i el patró **AAA (Arrange, Act, Assert)** per garantir la qualitat i robustesa del codi.

---

## Sprint 1: Creació del projecte

### Objectius
- Crear un projecte anomenat **VideosAppJoel** amb Jetstream Livewire, PHPUnit, suport per a equips (Teams) i SQLite com a base de dades.

### Activitats
- Creació de tests a `Helpers` per verificar la creació d’usuaris (usuari bàsic i professor) amb nom, email, contrasenya xifrada i associació a un equip.
- Implementació dels **helpers** a la carpeta `app`.
- Configuració de credencials d’usuaris a `config` amb valors extrets de `.env`.

---

## Sprint 2: Millores i noves funcionalitats

### Objectius
- Corregir errors detectats a l’Sprint 1.
- Configurar PHPUnit per utilitzar una base de dades temporal.
- Afegir funcionalitats bàsiques per gestionar vídeos.

### Activitats
- Configuració de `phpunit` per a una base de dades temporal.
- Creació de la migració per a la taula de vídeos amb els camps: `id`, `title`, `description`, `url`, `published_at`, `previous`, `next`, `series_id`.
- Desenvolupament del `VideosController` i el model `Video`.
- Implementació d’un helper per a vídeos predeterminats.
- Configuració del `DatabaseSeeder` per afegir usuaris i vídeos predeterminats.
- Creació del layout `VideosAppLayout`.
- Desenvolupament de la ruta i vista per mostrar vídeos.
- Escriptura de tests per comprovar la formatació de dates dels vídeos.
- Afegir tests per verificar la visualització de vídeos existents i la gestió de vídeos inexistents.
- Instal·lació i configuració de **Larastan** per analitzar i corregir errors del codi.

---

## Sprint 3: Correcció d'errors i permisos d'usuari

### Objectius
- Corregir errors del Sprint 2.
- Implementar un sistema de permisos i rols per a usuaris.

### Activitats
- Instal·lació del paquet **[spatie/laravel-permission](https://spatie.be/docs/laravel-permission)**.
- Creació d’una migració per afegir el camp `super_admin` a la taula `users`.
- Implementació de les funcions `testedBy()` i `isSuperAdmin()` al model `User`.
- Assignació del rol `superadmin` al professor a la funció `create_default_professor` dels helpers.
- Creació de la funció `add_personal_team()` per separar la lògica de creació d’equips.
- Implementació de funcions per crear usuaris:
    - `create_regular_user()` (Regular, regular@videosapp.com, 123456789).
    - `create_video_manager_user()` (Video Manager, videosmanager@videosapp.com, 123456789).
    - `create_superadmin_user()` (Super Admin, superadmin@videosapp.com, 123456789).
- Creació de `define_gates()` i `create_permissions()` per gestionar permisos.

#### A `app/Providers/AppServiceProvider`
- Registre de polítiques d’autorització.
- Definició de gates d’accés.
- Afegir permisos i usuaris predeterminats (`superadmin`, `regular user`, `video manager`) al `DatabaseSeeder`.
- Publicació de stubs personalitzats (exemple: [Personalització de stubs en Laravel](https://laravel.com/docs/stubs)).

#### Tests
- Creació de `VideosManageControllerTest` a `tests/Feature/Videos` amb:
    - `user_with_permissions_can_manage_videos()`
    - `regular_users_cannot_manage_videos()`
    - `guest_users_cannot_manage_videos()`
    - `superadmins_can_manage_videos()`
    - `loginAsVideoManager()`
    - `loginAsSuperAdmin()`
    - `loginAsRegularUser()`
- Creació de `UserTest` a `tests/Unit` per verificar `isSuperAdmin()`.

#### Noves funcionalitats
- Comprovació que usuaris amb permisos accedeixen a `/videos/manage`.
- Creació de `VideosManageController` amb les funcions: `testedBy`, `index`, `store`, `show`, `edit`, `update`, `delete`, `destroy`.
- Afegir funció `index` a `VideosController`.
- Revisió de 3 vídeos creats a `helpers` i afegits al `DatabaseSeeder`.
- Creació de vistes CRUD amb permisos:
    - `resources/views/videos/manage/index.blade.php`
    - `resources/views/videos/manage/create.blade.php`
    - `resources/views/videos/manage/edit.blade.php`
    - `resources/views/videos/manage/delete.blade.php`
- Afegir taula CRUD a `index.blade.php`.
- Afegir formulari amb atribut `data-qa` a `create.blade.php`.
- Afegir confirmació d’eliminació a `delete.blade.php`.
- Creació de `resources/views/videos/index.blade.php` per veure vídeos i accedir al detall.
- Creació i assignació de permisos per al CRUD de vídeos.
- Tests a `VideoTest`:
    - `user_without_permissions_can_see_default_videos_page`
    - `user_with_permissions_can_see_default_videos_page`
    - `not_logged_users_can_see_default_videos_page`
- Tests a `VideosManageControllerTest`:
    - `user_with_permissions_can_see_add_videos`
    - `user_without_videos_manage_create_cannot_see_add_videos`
    - `user_with_permissions_can_store_videos`
    - `user_without_permissions_cannot_store_videos`
    - `user_with_permissions_can_destroy_videos`
    - `user_without_permissions_cannot_destroy_videos`
    - `user_with_permissions_can_see_edit_videos`
    - `user_without_permissions_cannot_see_edit_videos`
    - `user_with_permissions_can_update_videos`
    - `user_without_permissions_cannot_update_videos`
    - `user_with_permissions_can_manage_videos`
    - `regular_users_cannot_manage_videos`
    - `guest_users_cannot_manage_videos`
    - `superadmins_can_manage_videos`
- Creació de rutes `/videos/manage` amb middleware.
- Rutes CRUD accessibles només per a usuaris logejats; ruta `index` pública.
- Afegir `navbar` i `footer` a `resources/layouts/videosapp`.
- Verificació amb **Larastan**.

#### Resum
- Afegit a `resources/markdown/terms` el resum del Sprint 3.
- Tots els fitxers verificats amb Larastan.

---

## Sprint 4: Gestió d’usuaris i correcció d’errors

### Objectius
- Corregir errors del Sprint 4.
- Afegir funcionalitats per gestionar usuaris amb un CRUD complet.
- Garantir que els tests anteriors segueixin funcionant.

### Activitats

#### Correcció d’errors
- Resolució d’errors detectats al Sprint 4, incloent problemes amb rutes i permisos.
- Reorganització de rutes a `web.php` per evitar conflictes entre rutes dinàmiques (com `/users/{id}`) i estàtiques (com `users/manage`).
- Correcció d’errors CSRF (codi 419) als tests aplicant `$this->actingAs($user)` a cada sol·licitud POST, PUT i DELETE.

#### Model i base de dades
- Afegit el camp `user_id` a la taula `videos` mitjançant una migració per associar cada vídeo amb l’usuari que l’ha creat.
- Modificació del model `Video` per incloure la relació amb `User` (`belongsTo`).
- Actualització del `VideosController` i `VideosManageController` per gestionar el camp `user_id` al crear vídeos.
- Modificació dels helpers (com `VideoHelper`) per assignar el `user_id` correctament.

#### Controladors
- Creació de `UsersManageController` amb les funcions:
    - `testedBy`
    - `index`
    - `store`
    - `edit`
    - `update`
    - `delete`
    - `destroy`
- Creació de `UsersController` amb:
    - `index`
    - `show`

#### Vistes
- Creació de vistes CRUD a `resources/views/users/manage/` amb accés restringit als usuaris amb permisos `manage users`:
    - **`index.blade.php`:** Taula amb el llistat d’usuaris (CRUD).
    - **`create.blade.php`:** Formulari per afegir usuaris amb atribut `data-qa` per facilitar tests.
    - **`edit.blade.php`:** Formulari per editar usuaris amb atribut `data-qa`.
    - **`delete.blade.php`:** Confirmació d’eliminació d’un usuari.
- Creació de `resources/views/users/index.blade.php`:
    - Llista pública de tots els usuaris amb opció de cerca.
    - Enllaç al detall de l’usuari (ruta `users.show`) mostrant els seus vídeos.

#### Helpers
- A `app/Helpers/UserHelper.php`, afegits permisos de gestió d’usuaris per al CRUD (`manage users`).
- Assignació automàtica del permís `manage users` als usuaris `superadmin`.

#### Tests
- **A `UserTest` (tests/Feature/UserTest.php):**
    - `user_without_permissions_can_see_default_users_page`
    - `user_with_permissions_can_see_default_users_page`
    - `not_logged_users_cannot_see_default_users_page`
    - `user_without_permissions_can_see_user_show_page`
    - `user_with_permissions_can_see_user_show_page`
    - `not_logged_users_cannot_see_user_show_page`
- **A `UsersManageControllerTest` (tests/Feature/UsersManageControllerTest.php):**
    - `loginAsVideoManager`
    - `loginAsSuperAdmin`
    - `loginAsRegularUser`
    - `user_with_permissions_can_see_add_users`
    - `user_without_users_manage_create_cannot_see_add_users`
    - `user_with_permissions_can_store_users`
    - `user_without_permissions_cannot_store_users`
    - `user_with_permissions_can_destroy_users`
    - `user_without_permissions_cannot_destroy_users`
    - `user_with_permissions_can_see_edit_users`
    - `user_without_permissions_cannot_see_edit_users`
    - `user_with_permissions_can_update_users`
    - `user_without_permissions_cannot_update_users`
    - `user_with_permissions_can_manage_users`
    - `regular_users_cannot_manage_users`
    - `guest_users_cannot_manage_users`
    - `superadmins_can_manage_users`
- Correcció de tests anteriors fallits (com els de `UsersManageControllerTest`) per errors CSRF mitjançant `$this->actingAs($user)` en cada sol·licitud.

#### Rutes
- Creació de rutes a `routes/web.php`:
    - Rutes CRUD a `/users/manage` amb middleware `['auth', 'can:manage users']`:
        - `GET /users/manage` (index)
        - `GET /users/manage/create` (create)
        - `POST /users/manage` (store)
        - `GET /users/manage/{id}/edit` (edit)
        - `PUT /users/manage/{id}` (update)
        - `GET /users/manage/{id}/delete` (delete)
        - `DELETE /users/manage/{id}` (destroy)
    - Rutes públiques amb middleware `auth`:
        - `GET /users` (index)
        - `GET /users/{id}` (show)
- Rutes accessibles només per a usuaris autenticats, amb navegació entre pàgines assegurada.

#### Altres
- Verificació de tots els fitxers creats amb **Larastan** per garantir la qualitat del codi.
- Documentació del Sprint 4 afegida a `resources/markdown/terms.md`.

---

## Sprint 5: Consolidació de la gestió d’usuaris

### Objectius
- Corregir errors del Sprint 4.
- Consolidar la funcionalitat del camp `user_id` a la taula de vídeos i completar la gestió d’usuaris amb un CRUD robust.

### Activitats

#### Correcció d’errors
- Resolució d’errors pendents del Sprint 4, com problemes amb la creació de professors per defecte (`it_can_create_default_user_and_teacher`) a causa de permisos faltants (`manage videos`).
- Correcció de tests anteriors afectats per modificacions al codi, assegurant compatibilitat amb sprints previs.

#### Model i base de dades
- Afegit el camp `user_id` a la taula `videos` mitjançant una migració per rastrejar l’usuari que afegeix cada vídeo.
- Modificació del model `Video` per incloure la relació `belongsTo` amb `User`.
- Actualització del `VideosController`, `VideosManageController` i helpers (com `VideoHelper`) per gestionar correctament el camp `user_id` al crear vídeos.

#### Controladors
- Creació (o consolidació) de `UsersManageController` amb les funcions:
    - `testedBy`
    - `index`
    - `store`
    - `edit`
    - `update`
    - `delete`
    - `destroy`
- Creació (o consolidació) de `UsersController` amb:
    - `index`
    - `show`

#### Vistes
- Creació de vistes CRUD a `resources/views/users/manage/` amb accés restringit als usuaris amb permisos `manage users`:
    - **`index.blade.php`:** Taula amb el llistat d’usuaris per al CRUD.
    - **`create.blade.php`:** Formulari per afegir usuaris amb atribut `data-qa` per facilitar tests.
    - **`edit.blade.php`:** Formulari per editar usuaris amb atribut `data-qa`.
    - **`delete.blade.php`:** Confirmació d’eliminació d’un usuari.
- Creació de `resources/views/users/index.blade.php`:
    - Llista de tots els usuaris amb funcionalitat de cerca.
    - Enllaç al detall de l’usuari (ruta `users.show`) mostrant els seus vídeos associats.

#### Helpers
- A `app/Helpers/UserHelper.php`, afegits permisos de gestió d’usuaris (`manage users`) per al CRUD.
- Assignació automàtica del permís `manage users` als usuaris `superadmin`.

#### Tests
- **A `UserTest` (tests/Feature/UserTest.php):**
    - `user_without_permissions_can_see_default_users_page`
    - `user_with_permissions_can_see_default_users_page`
    - `not_logged_users_cannot_see_default_users_page`
    - `user_without_permissions_can_see_user_show_page`
    - `user_with_permissions_can_see_user_show_page`
    - `not_logged_users_cannot_see_user_show_page`
- **A `UsersManageControllerTest` (tests/Feature/UsersManageControllerTest.php):**
    - `loginAsVideoManager`
    - `loginAsSuperAdmin`
    - `loginAsRegularUser`
    - `user_with_permissions_can_see_add_users`
    - `user_without_users_manage_create_cannot_see_add_users`
    - `user_with_permissions_can_store_users`
    - `user_without_permissions_cannot_store_users`
    - `user_with_permissions_can_destroy_users`
    - `user_without_permissions_cannot_destroy_users`
    - `user_with_permissions_can_see_edit_users`
    - `user_without_permissions_cannot_see_edit_users`
    - `user_with_permissions_can_update_users`
    - `user_without_permissions_cannot_update_users`
    - `user_with_permissions_can_manage_users`
    - `regular_users_cannot_manage_users`
    - `guest_users_cannot_manage_users`
    - `superadmins_can_manage_users`
- Correcció de tests anteriors fallits per canvis al model o controladors, com l’addició de `user_id`.

#### Rutes
- Creació de rutes a `routes/web.php`:
    - Rutes CRUD a `/users/manage` amb middleware `['auth', 'can:manage users']`:
        - `GET /users/manage` (index)
        - `GET /users/manage/create` (create)
        - `POST /users/manage` (store)
        - `GET /users/manage/{id}/edit` (edit)
        - `PUT /users/manage/{id}` (update)
        - `GET /users/manage/{id}/delete` (delete)
        - `DELETE /users/manage/{id}` (destroy)
    - Rutes amb middleware `auth`:
        - `GET /users` (index)
        - `GET /users/{id}` (show)
- Assegurada la navegació entre pàgines per a usuaris autenticats.

#### Altres
- Verificació de tots els fitxers creats o modificats amb **Larastan** per garantir la qualitat del codi.
- Documentació del Sprint 5 afegida a `resources/markdown/terms.md`.

---

### Resum del Sprint 5

- **Correcció d’errors:** Resolts problemes del Sprint 4, incloent errors de permisos i tests fallits.
- **Nova funcionalitat:** Consolidat el camp `user_id` als vídeos i implementat un CRUD complet per a usuaris amb vistes i permisos associats.
- **Tests:** Afegits i corregits tests per assegurar la funcionalitat del CRUD d’usuaris i la integració amb vídeos.

---

### Descripció del 6è Sprint:

En aquest sprint, he treballat en les següents tasques per avançar en el desenvolupament del projecte VideosApp:

- **Correcció d’errors del 5è sprint:** He revisat i corregit els errors detectats al sprint anterior per assegurar-me que l’aplicació funcioni correctament.
- **Correcció de tests:** Quan he modificat el codi, alguns tests de sprints anteriors han fallat, així que els he arreglat per mantenir la integritat del projecte.
- **Modificació dels vídeos:** He actualitzat el sistema per permetre assignar vídeos a sèries, implementant la relació necessària.
- **Creació de vídeos per a usuaris regulars:** He afegit les funcions del CRUD (create, read, update, delete) al `VideoController` perquè els usuaris regulars puguin crear vídeos. També he afegit botons per al CRUD a la vista de vídeos.
- **Migració de sèries:** He creat la migració per a la taula `series` amb els camps `id`, `title`, `description`, `image` (nullable), `user_name`, `user_photo_url` (nullable) i `published_at` (nullable).
- **Model de sèries:** He desenvolupat el model `Serie` amb les funcions `testedby`, `videos` (per a la relació 1:N), `getFormattedCreatedAtAttribute()`, `getFormattedForHumansCreatedAtAttribute()` i `getCreatedAtTimestampAttribute()`.
- **Relació 1:N als vídeos:** He afegit la relació 1:N al model `Video` per connectar-lo amb les sèries.
- **SeriesManageController:** He creat el controlador `SeriesManageController` amb les funcions `testedby`, `index`, `store`, `edit`, `update`, `delete` i `destroy` per gestionar les sèries.
- **SeriesController:** He implementat el controlador `SeriesController` amb les funcions `index` i `show` per mostrar les sèries.
- **Funció create_series() a Helpers:** He afegit la funció `create_series()` al fitxer de helpers, creant tres sèries inicials per al sistema.
- **Vistes per al CRUD de sèries:** He creat les següents vistes, accessibles només per als usuaris amb permisos:
    - `resources/views/series/manage/index.blade.php`
    - `resources/views/series/manage/create.blade.php`
    - `resources/views/series/manage/edit.blade.php`
    - `resources/views/series/manage/delete.blade.php`
- **Vista index.blade.php:** He afegit una taula per al CRUD de sèries a la vista `index.blade.php`.
- **Vista create.blade.php:** He creat un formulari per afegir sèries, utilitzant l’atribut `data-qa` per facilitar l’identificació en els tests.
- **Vista edit.blade.php:** He afegit una taula per al CRUD de sèries a la vista d’edició.
- **Vista delete.blade.php:** He implementat una confirmació per a l’eliminació de sèries, incloent l’opció de desassignar els vídeos associats en lloc d’eliminar-los.
- **Vista series/index.blade.php:** He creat la vista `resources/views/series/index.blade.php`, que mostra totes les sèries, permet buscar-les i, al clicar en una sèrie, mostra els vídeos associats.
- **Permisos de gestió de sèries:** He afegit els permisos per al CRUD de sèries al fitxer de helpers i els he assignat als usuaris superadmin.
- **Tests a SerieTest:** He creat la funció `serie_have_videos()` al fitxer `tests/Unit/SerieTest.php` per verificar la relació entre sèries i vídeos.
- **Tests a SeriesManageControllerTest:** He implementat les següents funcions al fitxer `SeriesManageControllerTest`:
    - `loginAsVideoManager`
    - `loginAsSuperAdmin`
    - `loginAsRegularUser`
    - `user_with_permissions_can_see_add_series`
    - `user_without_series_manage_create_cannot_see_add_series`
    - `user_with_permissions_can_store_series`
    - `user_without_permissions_cannot_store_series`
    - `user_with_permissions_can_destroy_series`
    - `user_without_permissions_cannot_destroy_series`
    - `user_with_permissions_can_see_edit_series`
    - `user_without_permissions_cannot_see_edit_series`
    - `user_with_permissions_can_update_series`
    - `user_without_permissions_cannot_update_series`
    - `user_with_permissions_can_manage_series`
    - `regular_users_cannot_manage_series`
    - `guest_users_cannot_manage_series`
    - `videomanagers_can_manage_series`
    - `superadmins_can_manage_series`
- **Rutes de sèries:** He creat les rutes per al CRUD de `series/manage` amb el middleware corresponent (`auth` i `can:manage series`). També he afegit les rutes per a l’índex i el show de sèries, visibles només per a usuaris autenticats.
- **Navegació:** He implementat la navegació entre pàgines per garantir una experiència d’usuari fluida.
- **Documentació a terms:** He afegit una descripció del que he fet en aquest sprint a `resources/markdown/terms`.
- **Comprovació amb Larastan:** He verificat tots els fitxers creats amb **Larastan** per assegurar-me que no hi ha errors d’anàlisi estàtica.

---
