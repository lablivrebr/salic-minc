<?php
/**
 * Arquivo principal da aplicacao (bootstrap)
 * Define todos os caminhos onde os arquivos estao armazenados
 * Carrega as classes do Zend utilizadas durante toda a aplicacao
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @since 29/03/2010
 * @version 0.1
 * @copyright c 2010 - Ministcrio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

define('APPLICATION_PATH', realpath(__DIR__ . '/../..' . DIRECTORY_SEPARATOR . 'application'));

/* configuracao do caminho dos includes */
// Include path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../tests'),
    get_include_path(),
)));

define('APPLICATION_ENV', 'testing');

/** Zend_Application */
# require_once 'Zend/Application.php';

require_once APPLICATION_PATH . '/../vendor/autoload.php';


