<?php

namespace Drupal\search_api_attachments\Plugin\search_api_attachments;

use Drupal\Core\Form\FormStateInterface;
use Drupal\search_api_attachments\TextExtractorPluginBase;
use Drupal\file\Entity\File;

/**
 * Provides pdftotext extractor.
 *
 * @SearchApiAttachmentsTextExtractor(
 *   id = "pdftotext_extractor",
 *   label = @Translation("Pdftotext Extractor"),
 *   description = @Translation("Adds Pdftotext extractor support."),
 * )
 */
class PdftotextExtractor extends TextExtractorPluginBase {

  /**
   * Extract file with Pdftotext command line tool.
   *
   * @param \Drupal\file\Entity\File $file
   *   A file object.
   *
   * @return string
   *   The text extracted from the file.
   */
  public function extract(File $file) {
    if (in_array($file->getMimeType(), $this->getPdfMimeTypes())) {
      $filepath = $this->getRealpath($file->getFileUri());
      // UTF-8 multibyte characters will be stripped by escapeshellargs() for
      // the default C-locale.
      // So temporarily set the locale to UTF-8 so that the filepath remains
      // valid.
      $backup_locale = setlocale(LC_CTYPE, '0');
      setlocale(LC_CTYPE, 'en_US.UTF-8');
      // Pdftotext descriptions states that '-' as text-file will send text to
      // stdout.
      $cmd = escapeshellcmd('pdftotext') . ' ' . escapeshellarg($filepath) . ' -';
      // Restore the locale.
      setlocale(LC_CTYPE, $backup_locale);
      // Support UTF-8 commands.
      // @see http://www.php.net/manual/en/function.shell-exec.php#85095
      shell_exec("LANG=en_US.utf-8");
      return shell_exec($cmd);
    }
    else {
      return NULL;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['pdftotext'] = [
      '#type' => 'markup',
      '#markup' => $this->t('No configuration needed for this extraction method.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    ];
    return $form;
  }

}
