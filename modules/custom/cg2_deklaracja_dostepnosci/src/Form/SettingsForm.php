<?php

namespace Drupal\cg2_deklaracja_dostepnosci\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure CG2 Deklaracja dostępności settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cg2_deklaracja_dostepnosci_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cg2_deklaracja_dostepnosci.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $default = [
      'wstep' => '
        <p>
          %podmiot% zobowiązuje się zapewnić
          dostępność swojej strony internetowej zgodnie z przepisami ustawy z dnia 4 kwietnia 2019 r.
          o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych.
          Deklaracja dostępności dotyczy strony internetowej
          %url%
        </p>',

      'status_1' => '
        <p>
          Strona internetowa jest zgodna z ustawą z dnia 4 kwietnia 2019 r. o
          dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych.
        </p>
      ',

      'status_2' => '
        <p>
          Strona internetowa jest niezgodna z ustawą z dnia 4 kwietnia 2019 r.
          o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów
          publicznych z powodu niezgodności lub wyłączeń wymienionych poniżej:
        </p>
      ',

      'status_3' => '
        <p>
          Strona internetowa jest niezgodna z ustawą z dnia 4 kwietnia 2019 r.
          o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów
          publicznych z powodu niezgodności lub wyłączeń wymienionych poniżej:
        </p>
      ',

      'informacje-zwrotne' => '
        <p>
          W przypadku problemów z dostępnością strony internetowej prosimy o kontakt. Osobą kontaktową jest
          %osoba-kontaktowa%,
          %osoba-email%. Kontaktować można się także dzwoniąc na numer telefonu
          %osoba-telefon%. Tą samą drogą można składać wnioski o udostępnienie informacji niedostępnej oraz składać żądania zapewnienia dostępności.
        </p>
      ',
      'obsluga-wnioskow' => '
        <p>Każdy ma prawo wystąpić z żądaniem zapewnienia dostępności cyfrowej tej strony internetowej lub jej elementów</p>

        <p>
        W zgłoszeniu prosimy o podanie:
        </p>
        <ul>
          <li>swojego imienia i nazwiska,</li>
          <li>swoich danych kontaktowych,</li>
          <li>dokładnego adres podstrony, na której jest niedostępny cyfrowo element lub treść
          opisu, na czym polega problem i jaki sposób jego rozwiązania byłby najlepszy według osoby występującej z żądaniem.</li>
        </ul>
        <p>
          Na zgłoszenie odpowiemy najszybciej jak to możliwe, nie później niż w ciągu 7 dni od jego
          otrzymania.
        </p>
        <p>
          Jeżeli ten termin będzie dla nas zbyt krótki poinformujemy o tym osobę wskazaną w żądaniu.
          W tej informacji podamy nowy termin, do którego poprawimy zgłoszone błędy lub przygotujemy
          informacje w alternatywny sposób. Ten nowy termin nie będzie dłuższy niż 2 miesiące.
        </p>
        <p>
          Jeżeli nasza reakcja, sposób zapewnienia dostępności lub zaproponowany dostęp alternatywny, będzie niewłaściwy w ocenie osoby występującej z żądaniem, ma ona prawo do złożenia skargi.
        </p>
      ',
    ];

    //----------------------------------------

    $form['fieldset_wstep'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Wstęp'),
    ];

    $form['fieldset_wstep']['a11y-podmiot'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nazwa podmiotu publicznego'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-podmiot'),
      '#required' => TRUE,
    ];

    $form['fieldset_wstep']['opis-wstep'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Tekst wstępu'),
      '#required' => TRUE,
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-wstep')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-wstep')['value'] : $default['wstep'],
      '#description' => $this->t(
        'Tekst wstępu, który będzie wyświetlany na stronie deklaracji dostępności.
        Dostępne są następujące zmienne:
        <ul>
          <li>%podmiot% - dane z pola "Nazwa podmiotu publicznego", zmienna obowiązkowa</li>
          <li>%url% - adres strony internetowej, zmienna obowiązkowa</li>
        </ul>'
      ),
    ];

    //----------------------------------------

    $form['fieldset_daty'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Daty publikacji i aktualizacji'),
    ];

    $form['fieldset_daty']['a11y-data-publikacja'] = [
      '#type' => 'date',
      '#title' => $this->t('Data publikacji strony'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-data-publikacja'),
      '#required' => TRUE,
    ];

    $form['fieldset_daty']['a11y-data-aktualizacja'] = [
      '#type' => 'date',
      '#title' => $this->t('Data ostatniej aktualizacji strony'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-data-aktualizacja'),

    ];

    $form['fieldset_dostepnosc'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Stan dostępności cyfrowej'),
    ];

    $form['fieldset_dostepnosc']['a11y-status'] = [
      '#type' => 'select',
      '#title' => $this->t('Status zgodności z ustawą o dostępności cyfrowej'),
      '#options' => [
        '1' => $this->t('Zgodne'),
        '2' => $this->t('Częściowo zgodne'),
        '3' => $this->t('Niezgodne')
      ],
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-status'),
      '#required' => TRUE,
    ];

    $form['fieldset_dostepnosc']['opis-status-1'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Tekst statusu dostępności zgodnej'),
      '#required' => TRUE,
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-status-1')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-status-1')['value'] : $default['status_1'],
      '#description' => $this->t(
        'Treść statusu, który będzie wyświetlany na stronie deklaracji dostępności.'
      ),
    ];

    $form['fieldset_dostepnosc']['opis-status-2'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Tekst statusu dostępności częściowo zgodnej'),
      '#required' => TRUE,
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-status-2')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-status-2')['value'] : $default['status_2'],
      '#description' => $this->t(
        'Treść statusu, który będzie wyświetlany na stronie deklaracji dostępności.'
      ),
    ];

    $form['fieldset_dostepnosc']['opis-status-3'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Tekst statusu dostępności niezgodnej'),
      '#required' => TRUE,
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-status-3')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-status-3')['value'] : $default['status_3'],
      '#description' => $this->t(
        'Treść statusu, który będzie wyświetlany na stronie deklaracji dostępności.'
      ),
    ];


    $form['fieldset_dostepnosc']['a11y-ocena-opis'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Opis niedostępności'),
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-ocena-opis')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-ocena-opis')['value'] : '',
      '#description' => $this->t(
        'W przypadku częściowej lub pełnej niezgodności, podaj opis niedostępności.
        W takiej sytuacji podmiot szamieszcza informacje:
          <ul>
          <li>które wymagania nie zostały spełnione, na przykład ”filmy nie posiadają napisów dla osób głuchych”, „formularz kontaktowy nie posiada dowiązanych etykiet tekstowych”, „mapa placówek medycznych nie jest dostępna” itp.</li>
          <li>powody wyłączenia, na przykład „filmy zostały opublikowane przed wejściem w życie ustawy o dostępności cyfrowej”, „poprawienie dostępności strony niosłoby za sobą nadmierne obciążenia dla podmiotu publicznego”, „mapy są wyłączone z obowiązku zapewniania dostępności” itp.</li>
          </ul>
          W przypadku wskazania nadmiernego obciążenia jako powodu braku dostępności cyfrowej,
          dołącza się zatwierdzoną przez kierownika jednostki informację o przeprowadzonej analizie,
          o której mowa w art. 8, ust. 3 ustawy o dostępności cyfrowej.'
      ),
    ];

    $form['fieldset_dostepnosc']['a11y-ocena'] = [
      '#type' => 'url',
      '#title' => $this->t('Link do wyniku analizy o nadmiernym obciążeniu'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-ocena'),
      '#description' => $this->t('W przypadku wskazania nadmiernego obciążenia jako powodu braku dostępności cyfrowej,
      dołącz zatwierdzoną przez kierownika jednostki informację o przeprowadzonej analizie,
      o której mowa w art. 8, ust. 3 ustawy o dostępności cyfrowej.')
    ];



    $form['fieldset_przygotowaniec'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Przygotowanie deklaracji dostępności'),
    ];

    $form['fieldset_przygotowaniec']['a11y-data-sporzadzenie'] = [
      '#type' => 'date',
      '#title' => $this->t('Data sporządzenia Deklaracji Dostępności'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-data-sporzadzenie'),
      '#required' => TRUE,
    ];

    $form['fieldset_przygotowaniec']['a11y-data-przeglad'] = [
      '#type' => 'date',
      '#title' => $this->t('Data ostatniego przegląu Deklaracji Dostępności'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-data-przeglad'),
      '#required' => FALSE,
    ];

    $form['fieldset_przygotowaniec']['a11y-audytor'] = [
      '#type' => 'select',
      '#title' => $this->t('W jaki sposób została przeprowadzona ocena dostępności?'),
      '#options' => [
        '1' => $this->t('Samoocena'),
        '2' => $this->t('Podmiot zewnętrzny'),
      ],
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-audytor'),
      '#required' => TRUE,
      '#description' => $this->t(
        '„Podmiot zewnętrzny” to firma, osoba lub organizacja, która przeprowadziła przegląd,
        badanie lub audyt dostępności cyfrowej strony internetowej lub aplikacji mobilnej.
        Raport z informacjami powinien być podstawą do przygotowania Deklaracji Dostępności,
        w tym określenia poziomu dostępności i opisania ewentualnych braków.'
      )
    ];

    $form['fieldset_przygotowaniec']['a11y-audytor-nazwa'] = [
      '#type' => 'textfield',
      '#format' => 'basic_html',
      '#title' => $this->t('Nazwa podmiotu zewnętrznego, który przeprowadził badanie dostępności'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-audytor-nazwa'),
    ];

    $form['fieldset_informacje'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Informacje zwrotne i dane kontaktowe'),
    ];

    $form['fieldset_informacje']['a11y-osoba'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Imię i nazwisko osoby odpowiedzialnej za kontakt w sprawie niedostępności'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-osoba'),
      '#required' => TRUE,
    ];

    $form['fieldset_informacje']['a11y-email'] = [
      '#type' => 'email',
      '#title' => $this->t('Adres poczty elektronicznej osoby kontaktowej'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-email'),
      '#required' => TRUE,
    ];

    $form['fieldset_informacje']['a11y-telefon'] = [
      '#type' => 'tel',
      '#title' => $this->t('Numer telefonu do osoby kontaktowej'),
      '#default_value' => $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-telefon'),
      '#required' => TRUE,
    ];

    $form['fieldset_informacje']['opis-informacje-zwrotne'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Tekst sekcji "Informacje zwrotne i dane kontaktowe"'),
      '#required' => TRUE,
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-informacje-zwrotne')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-informacje-zwrotne')['value'] : $default['informacje-zwrotne'],
      '#description' => $this->t(
        'Treść statusu, który będzie wyświetlany na stronie deklaracji dostępności.
        Dostępne są następujące zmienne:
        <ul>
          <li>%osoba-kontaktowa% - dane z pola "Imię i nazwisko osoby odpowiedzialnej za kontakt w sprawie niedostępności", zmienna wymagana</li>
          <li>%osoba-email% - dane z pola "Adres poczty elektronicznej osoby kontaktowej", zmienna wymagana</li>
          <li>%osoba-telefon% - dane z pola "Numer telefonu do osoby kontaktowej", zmienna wymagana</li>
        </ul>'
      ),
    ];

    $form['fieldset_skargi'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Obsługa wniosków i skarg związanych z dostępnością'),
    ];

    $form['fieldset_skargi']['opis-obsluga-wnioskow'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Tekst sekcji "Obsługa wniosków i skarg związanych z dostępnością"'),
      '#required' => TRUE,
      '#default_value' =>
      isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-obsluga-wnioskow')['value'])
        ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('opis-obsluga-wnioskow')['value']
        : $default['obsluga-wnioskow'],
      '#description' => $this->t(
        'Treść statusu, który będzie wyświetlany na stronie deklaracji dostępności.',
      ),
    ];


    $form['fieldset_skargi']['a11y-skargi'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Sposoby zgłaszania skarg'),
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-skargi')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-skargi')['value'] : '',
      '#description' => 'Podaj informacje o możliwości składania skarg i wniosków oraz o sposobie dostępu do procedury, np.:
        <ul>
        <li>pisemnie pocztą na adres: …</li>
        <li>pocztą elektroniczną na adres: …</li>
        <li>poprzez system ePUAP - adres skrytki na ePUAP: …</li>
        <li>telefonicznie na numer: ….</li>
        </ul>',
      '#required' => TRUE,
    ];


    $form['a11y-architektura'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Sekcja z informacjami o dostępności architektonicznej'),
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-architektura')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-architektura')['value'] : '',
      '#description' => $this->t('Podaj nazwę adres podmiotu publicznego, a poniżej opisz koniecznie kluczowe elementy dostępności architektonicznej konkretnego budynku:
      <ol>
          <li>Opis dostępności wejścia do budynku i przechodzenia przez obszary kontroli;</li>
          <li>Opis dostępności korytarzy, schodów i wind;</li>
          <li>Opis dostosowań, np. pochylni, platform, informacji głosowych, pętli indukcyjnych;</li>
          <li>Informacje o miejscu i sposobie korzystania z miejsc parkingowych wyznaczonych dla osób z niepełnosprawnościami;</li>
          <li>Informacja o prawie wstępu z psem asystującym i ewentualnych uzasadnionych ograniczeniach;</li>
      </ol>
      Koniecznie podaj też informację o możliwości skorzystania z tłumacza języka migowego na miejscu lub online. Jeżeli nie ma takiej możliwości, także o tym poinformuj.<br>
      Wprawdzie obowiązkowo musisz opisać dostępność budnku siedziby podmiotu, ale rozważ czy nie ma innych budynków, które także warto opisać, bo np. jest w nim biuro obsługi klienta.<br>
      Oprócz tego możesz dodać inne informacje, które mogą być przydatne dla osób z niepełnosprawnościami, które będą korzystać z danego budynku, np. jak dojechać do budynku transportem publicznym.<br>
      Dobrą praktyką jest również dodanie fotografii budynku. Rozważ także dodanie filmu z tłumaczem języka migowego wyjaśniającym jak wygląda dostępność budynku oraz przygotowanie tej samej informacji w tzw. tekście łatwym do czytania i rozumienia (dedykowanym dla osób z niepełnosprawnością intelektualną).')
    ];


    $form['a11y-aplikacje'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Sekcja z informacjami o aplikacjach'),
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-aplikacje')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-aplikacje')['value'] : '',
      '#description' => $this->t(
        'Jeżeli podmiot publiczny posiada i udostępnia aplikacje mobilne w treści deklaracji wymienia te
        aplikacje wraz z linkami do pobrania. Może to wyglądać następująco: <br>
        <i>
        Ministerstwo Cyfryzacji udostępnia następujące aplikacje mobilne:
        <ul>
        <li>mObywatel w wersji dla systemu Android</li>
        <li>mObywatel w wersji dla systemu iOS.</li>
        </ul></i>
        Nazwy aplikacji są linkami prowadzącymi do miejsca, z którego można pobrać aplikację, na przykład do sklepu z aplikacjami.'
      )
    ];

    $form['a11y-inne'] = [
      '#type' => 'text_format',
      '#format' => 'basic_html',
      '#title' => $this->t('Sekcja z informacjami nieobowiązkowymi'),
      '#default_value' => isset($this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-inne')['value']) ? $this->config('cg2_deklaracja_dostepnosci.settings')->get('a11y-inne')['value'] : '',
      '#description' => $this->t(
        'W tej sekcji dodaj informacje, które mogą być użyteczne pod kątem zapewnienia i informowania o
        dostępności. Takie elementy mogą być dodane do Deklaracji wedle uznania, np.:<br>

        <ul>
        <li>informacja o zapewnieniu możliwości skorzystania z tłumacza języka migowego,</li>
        <li>możliwość właczenia audiodeskrypcji na stronie.</li>
        </ul>
        Sekcja musi być rozpoczęta od nagłówka poziomu drugiego (H2).
      '
      )
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    if ($form_state->getValue('a11y-status') != 1 && empty(trim(strip_tags($form_state->getValue('a11y-ocena-opis')['value'])))) {
      $form_state->setErrorByName('a11y-ocena-opis', $this->t('Uzupełnij opis niedostępności.'));
    }

    if ($form_state->getValue('a11y-audytor') == 2 && empty($form_state->getValue('a11y-audytor-nazwa'))) {
      $form_state->setErrorByName('a11y-audytor-nazwa', $this->t('Uzupełnij opis nazwę audytora.'));
    }

    if (!empty(trim(strip_tags(($form_state->getValue('a11y-inne')['value'])))) && strpos($form_state->getValue('a11y-inne')['value'], '<h2') !== 0) {
      $form_state->setErrorByName('a11y-inne', $this->t('Dodaj nagłówk H2 do sekcji informacje nieobowiązkowe.'));
    }

    //----------------------------------------

    $opisWstepError = [];

    if (strpos($form_state->getValue('opis-wstep')['value'], '%podmiot%') === FALSE) {
      $opisWstepError[] = $this->t('Uzupełnij zmienną %podmiot%.');
    }

    if (strpos($form_state->getValue('opis-wstep')['value'], '%url%') === FALSE) {
      $opisWstepError[] = $this->t('Uzupełnij zmienną %url%.');
    }

    if (!empty($opisWstepError)) {
      $form_state->setErrorByName('opis-wstep', implode(' ', $opisWstepError));
    }

    $opisInformacjeError = [];

    if (strpos($form_state->getValue('opis-informacje-zwrotne')['value'], '%osoba-kontaktowa%') === FALSE) {
      $opisInformacjeError[] = $this->t('Uzupełnij zmienną %osoba-kontaktowa%.');
    }

    if (strpos($form_state->getValue('opis-informacje-zwrotne')['value'], '%osoba-email%') === FALSE) {
      $opisInformacjeError[] = $this->t('Uzupełnij zmienną %osoba-email%.');
    }

    if (strpos($form_state->getValue('opis-informacje-zwrotne')['value'], '%osoba-telefon%') === FALSE) {
      $opisInformacjeError[] = $this->t('Uzupełnij zmienną %osoba-telefon%.');
    }

    if (!empty($opisInformacjeError)) {
      $form_state->setErrorByName('opis-informacje-zwrotne', implode(' ', $opisInformacjeError));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Na razie nie wiem jak inaczej to zrobić
    $fields = [
      'a11y-podmiot',
      'a11y-data-publikacja',
      'a11y-data-aktualizacja',
      'a11y-status',
      'a11y-ocena-opis',
      'a11y-ocena',
      'a11y-data-sporzadzenie',
      'a11y-data-przeglad',
      'a11y-audytor',
      'a11y-audytor-nazwa',
      'a11y-osoba',
      'a11y-email',
      'a11y-telefon',
      'a11y-skargi',
      'a11y-architektura',
      'a11y-aplikacje',
      'a11y-inne',
      'opis-wstep',
      'opis-status-1',
      'opis-status-2',
      'opis-status-3',
      'opis-informacje-zwrotne',
      'opis-obsluga-wnioskow'
    ];

    foreach ($fields as $v) {
      $this->config('cg2_deklaracja_dostepnosci.settings')
        ->set($v, $form_state->getValue($v))
        ->save();
    }

    parent::submitForm($form, $form_state);
  }
}
