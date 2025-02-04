
# Integracja Kicket API x Jaracz

Zmiany były wykonywane głównie w obrębie modułu Repertuar, a reszta jest zależna już od Kicket API. 

Moduł podczas instalacji tworzy `field` dla `entity_type = 'repertuar'` oraz `bundle = 'repertuar'`. Podczas deinstalacji pola zostają usunięte zgodnie z logiką dodawania, więc moduł nie powinien zostawiać po sobie śladów odnośnie encji i paczek, ale pozostawia informacje o dodanych w okresie działania modułu wpisach w Repertuarze.

Pola, które zostały wprowadzone:
```php
$fields = [
        'field_api_id' => ['type' => 'string', 'label' => 'API ID'],
        'field_event_title' => ['type' => 'string', 'label' => 'Tytuł wydarzenia'],
        'field_price' => ['type' => 'decimal', 'label' => 'Cena'],
        'field_date' => ['type' => 'string', 'label' => 'Data'],
        'field_cancelled' => ['type' => 'boolean', 'label' => 'Odwołane'],
        'field_available_places' => ['type' => 'integer', 'label' => 'Dostępne miejsca'],
        'field_cast' => ['type' => 'text_long', 'label' => 'Obsada'],
        'field_picture_url' => ['type' => 'string', 'label' => 'Zdjęcie'],
        'field_reservation_form' => ['type' => 'string', 'label' => 'Formularz rezerwacji'],
        'field_scene_name' => ['type' => 'string', 'label' => 'Scena'],
        'field_show_url' => ['type' => 'string', 'label' => 'Wydarzenia zbiorcze'],
        'field_related_events' => ['type' => 'boolean', 'label' => 'Inne terminy wydarzeń'],
    ];
```

W module Repertuaru wprowadziliśmy zmiany, które pozwalają na dodawanie wydarzeń z Kicket API, nawet jeżeli nie mają refrencji do `Spektakl`, w ten sposób wydarzenie będzie traktowane poprawnie przez napisany kod. Dodatkowo dodaliśmy obsługę zmiennych z Kicket API, które nie muszą być dodawane do formularza wyświetleń, ponieważ są stosowane w `template_preprocess_repertuar()` jako zmienne globalne, więc dostęp do nich jest łatwy.

Wprowadzone zmienne to:
```php
$variables['event_title'] = $repertuar->get('field_event_title')->value ?? 'Brak tytułu';
$variables['event_price'] = (float) $repertuar->get('field_price')->value ?? 0;
$variables['event_image'] = $repertuar->get('field_picture_url')->value ?? '';
$variables['event_available_places'] = (int) $repertuar->get('field_available_places')->value ?? 0;
$variables['event_cancelled'] = (bool) $repertuar->get('field_cancelled')->value;
$variables['event_related_events'] = (bool) $repertuar->get('field_related_events')->value;
$variables['event_scene_name'] = $repertuar->get('field_scene_name')->value ?? 'Brak tytułu';
$variables['event_show_url'] = $repertuar->get('field_show_url')->value ?? '';
```
## Strona podziękowania za zakup biletu
W konfiguracji Kicket API, które zostało dodane do zakładki Konfiguracja -> Kicket API, jest pole "Strona docelowa dla JavaScript", po wskazaniu strony w `autocomplete` osadzi się plik JS z modułu (tylko dla wskazanej strony), który ma za zadanie wyświetlanie informacji pozakupowych (po zrealizowanie zakupu przez Kicket). Informacje pozakupowe i wykonanywana funkcja pochodzi z głównego pliku kicket.com (https://kicket.com/embeddables/embedded-manager.js). 

Na stronie docelowej należy dodać sekcję HTML, która będzie miała strukturę:
```html
<div class="kicket-typ-container">
    <div id="response-container">&nbsp;</div>
    <a class="kicket-button" href="" id="ticket-url">Pobierz bilety</a>
</div>
```
`#response-container` jest wpisany w pliku JS modułu jako identyfikator w który wrzucamy obrobione dane rozszerzone o podsumowaniu zakupienia. Można dowolnie nim manipulować.

`<a class="kicket-button" href="" id="ticket-url">Pobierz bilety</a>` - parametry `href` oraz `id="ticket-url"` nie mogą zostać zastąpione, ponieważ skrypt Kicket poszukuje po identyfikatorze oraz pustym hrefie, jeżeli zostaną te elementy usunięte to przycisk nie będzie dynamicznie podmieniał linku do zakupu biletu. 

## CRON
Moduł ma wbudowany CRON, który powinien się wykonywać co 5 minut. Prosiłbym o dodanie linku do wykonywania CRON do osobnego CRON, który będzie go odświeżał co 5 minut lub zmienić zasadę działania CRON w module tak, aby odświeżanie API robiło się faktycznie co 5 minut. Jeżeli CRON nie zostanie poprawnie osadzony to mogą na stronie pojawiać się przestrzałe i nieaktualne treści, a w szczególności przyciski zakupowe, które będą kierowały do pustej sprzedaży. 

Wdrożenie CRON:
```php
function kicket_api_cron()
{
    $service = \Drupal::service('kicket_api.service');
    $offers = $service->fetchOffers();

    if (!empty($offers)) {
        $service->updateRepertuar($offers);
        \Drupal::logger('kicket_api')->notice("Automatyczna aktualizacja repertuaru zakończona.");
    } else {
        \Drupal::logger('kicket_api')->warning("Brak ofert do aktualizacji w CRON-ie.");
    }
}
```


Reszta zmian jest dostępna w przesłanych commitach, aby dokładnie zobaczyć co się podziało, reszta plików poza modułowymi nie została przez nas zmieniana. 