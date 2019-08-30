<?php
/**
 * MVC
 *
 * Suporte à requisições WEB com MVC.
 * carregamento de classes semelhantes como propriedades.
 * @version 1.00000.00.00000
 * @copyright De Souza Informática - 2016
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
 *
 */

namespace system\model\actions;

	interface Action{
		
		//----- PROPRIEDADES -----
		
		//----- CONSTANTES -----
		
		//----- FUNÇÕES -----
		
		/**
		 * Executa ação com a entity
		 * @param Entity $entity
		 * @param int $action
		 */
		public function exec($entity);
		
		/**
		 * Exporta stado da ação
		 * @return mixed
		 */
		public function status();

		/**
		 * Define cláusula where
		 *
		 * @param string where
		 */
		public function setWhere($where);
	}