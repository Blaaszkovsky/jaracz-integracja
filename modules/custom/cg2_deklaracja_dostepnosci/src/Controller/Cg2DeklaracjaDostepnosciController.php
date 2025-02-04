<?php

namespace Drupal\cg2_deklaracja_dostepnosci\Controller;

use Drupal\Core\Controller\ControllerBase;
// https://www.drupal.org/project/field_states_ui
/**
 * Returns responses for CG2 Deklaracja dostępności routes.
 */
class Cg2DeklaracjaDostepnosciController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    // get cg3_deklaracja_dostepnosci.settings config
    $config = $this->config('cg2_deklaracja_dostepnosci.settings');

    // get all fields from config
    $fields = $config->getRawData();

    if (empty($fields)) {
      \Drupal::messenger()->addWarning($this->t('Brak konfiguracji deklaracji dostępności. Przejdź do <a href="/admin/config/system/cg2-deklaracja-dostepnosci">konfiguracji</a>.'));
      $build['content'] = [
        '#type' => 'item',
        '#markup' => '',
      ];

      return $build;
    }

    // get full site domain
    $site_url = \Drupal::request()->getSchemeAndHttpHost();

    $wstep = $config->get('opis-wstep')['value'];
    $wstep = str_replace('%podmiot%', '<span id="a11y-podmiot">' . $config->get('a11y-podmiot') . '</span>', $wstep);
    $wstep = str_replace('%url%', '<a id="a11y-url" href="' . $site_url . '">' . $config->get('a11y-podmiot') . '</a>', $wstep);


    $text = '
    <div class="ckeditor">
      <div id="a11y-wstep">'
      . $wstep . '
      </div>

      <h2>Daty publikacji i aktualizacji</h2>

      <p>
        Data publikacji strony internetowej:
        <span id="a11y-data-publikacja"> ' . (date('Y-m-d', strtotime($config->get('a11y-data-publikacja')))) . '.</span>';

    if (!empty($config->get('a11y-data-aktualizacja'))) {
      $text .= '<br> Data ostatniej istotnej
              aktualizacji: <span id="a11y-data-aktualizacja">' . (date('Y-m-d', strtotime($config->get('a11y-data-aktualizacja')))) . '.</span>
              ';
    }

    $text .= '
      </p>

      <h2 id="a11y-status">Stan dostępności cyfrowej</h2>';


    switch ($config->get('a11y-status')) {
      case '2':
        $text .= $config->get('opis-status-2')['value'] . $config->get('a11y-ocena-opis')['value'];

        if ($config->get('a11y-ocena')) {
          $text .=
            '<a id="a11y-ocena" href="' . $config->get('a11y-ocena') . '">
            Link do wyniku analizy o nadmiernym obciążeniu.
          </a>';
        }
        break;

      case '3':
        $text .= $config->get('opis-status-3')['value']  . $config->get('a11y-ocena-opis')['value'];

        if ($config->get('a11y-ocena')) {
          $text .=
            '<a id="a11y-ocena" href="' . $config->get('a11y-ocena') . '">
            Link do wyniku analizy o nadmiernym obciążeniu.
          </a>';
        }
        break;


      default:
        $text .= $config->get('opis-status-1')['value'] . $config->get('a11y-ocena-opis')['value'];;
        break;
    }

    $text .= '
      <h2>Przygotowanie deklaracji dostępności</h2>
      <p id="a11y-data-sporzadzenie">
        Data sporządzenia deklaracji: ' . (date('Y-m-d', strtotime($config->get('a11y-data-sporzadzenie')))) . '.<br>
        ';

    if ($config->get('a11y-data-przeglad')) {
      $text .= '
          Data ostatniego przeglądu deklaracji: : ' . (date('Y-m-d', strtotime($config->get('a11y-data-przeglad')))) . '.';
    }

    $text .= '</p>
      ';

    if ($config->get('a11y-audytor') == 1) {
      $text .= '
      <p id="a11y-audytor">
        Deklarację sporządzono na podstawie samooceny przeprowadzonej przez podmiot publiczny.
      </p>';
    } else {
      $text .= '
      <p id="a11y-audytor">
        Deklarację sporządzono na podstawie oceny przeprowadzonej przez
        ' . $config->get('a11y-audytor-nazwa') . '
      </p>';
    }

    $text .= '
      <h2>Skróty klawiaturowe</h2>
      <p>
        Na tej stronie internetowej można korzystać ze standardowych skrótów klawiaturowych.
      </p>';

    $informacje = $config->get('opis-informacje-zwrotne')['value'];
    $informacje = str_replace('%osoba-kontaktowa%', '<span id="a11y-osoba">' . $config->get('a11y-osoba') . '</span>', $informacje);
    $informacje = str_replace('%osoba-email%', '<a id="a11y-email" href="mailto:' . $config->get('a11y-email') . '">' . $config->get('a11y-email') . '</a>', $informacje);
    $informacje = str_replace('%osoba-telefon%', '<span id="a11y-telefon">' . $config->get('a11y-telefon') . '</span>', $informacje);
    $wnioski = $config->get('opis-obsluga-wnioskow')['value'];

    $text .= '<h2 id="a11y-kontakt">Informacje zwrotne i dane kontaktowe</h2>' . $informacje .
      '<h3 id="a11y-procedura">Obsługa wniosków i skarg związanych z dostępnością</h3>' . $wnioski;

    if (!empty($config->get('a11y-skargi'))) {
      $text .= '
        <h3>
          Skargi prosimy kierować:
        </h3>
        ' . $config->get('a11y-skargi')['value'];
    }

    $text .= '
      <p>
        Można również skontaktować się z <a href="https://bip.brpo.gov.pl/pl" target="_blank">Biurem Rzecznika Praw Obywatelskich <span class="sr-only">- otwórz na nowej stronie</span></a> i poprosić Rzecznika o
        podjęcie interwencji w swojej sprawie.
      </p>
    ';


    if (!empty($config->get('a11y-architektura')['value'])) {
      $text .= '
          <h2 id="a11y-architektura">
          Dostępność architektoniczna
          </h2>' . $config->get('a11y-architektura')['value'];
    }

    if (!empty($config->get('a11y-aplikacje')['value'])) {
      $text .= '
          <h2 id="a11y-aplikacje">Aplikacje mobilne</h2>' . $config->get('a11y-aplikacje')['value'];
    }

    if (!empty($config->get('a11y-inne'))) {
      $text .= $config->get('a11y-inne')['value'];
    }

    $text .= '
    </div>';


    $build['content'] = [
      '#type' => 'item',
      '#markup' => $text,
    ];

    return $build;
  }
}
